<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 勤務外ステータスが表示される()
    {
        $user = User::factory()->create();

        // 勤怠なし = 勤務外
        $response = $this->actingAs($user)->get('/attendance');

        $response->assertSee('勤務外');
    }

    /** @test */
    public function 出勤中ステータスが表示される()
    {
        $user = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'status' => 'working',
            'work_date' => now(),
        ]);

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertSee('出勤中');
    }

    /** @test */
    public function 休憩中ステータスが表示される()
    {
        $user = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'status' => 'break',
            'work_date' => now(),
        ]);

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertSee('休憩中');
    }

    /** @test */
    public function 退勤済ステータスが表示される()
    {
        $user = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'status' => 'finished',
            'work_date' => now(),
        ]);

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertSee('退勤済');
    }

    /** @test */
    public function 出勤できる()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/attendance/clockin');

        // リダイレクト確認
        $response->assertStatus(302);

        // DB確認
        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'status' => 'working',
        ]);
    }

    /** @test */
    public function 出勤は1日1回しかできない()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        // 1回目
        $this->post('/attendance/clockin');

        $first = Attendance::first();

        sleep(1); // 時間差つける

        // 2回目
        $this->post('/attendance/clockin');

        $second = Attendance::first();

        // clock_inが変わってないこと
        $this->assertEquals($first->clock_in, $second->clock_in);
    }

    /** @test */
    public function 休憩開始できる()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        // まず出勤
        $this->post('/attendance/clockin');

        // 休憩開始
        $this->post('/attendance/break/start');

        // BreakTime確認
        $this->assertDatabaseHas('break_times', [
            'attendance_id' => \App\Models\Attendance::first()->id,
        ]);

        // ステータス確認
        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'status' => 'break',
        ]);
    }

    /** @test */
    public function 休憩終了できる()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->post('/attendance/clockin');
        $this->post('/attendance/break/start');
        $this->post('/attendance/break/end');

        $this->assertDatabaseCount('break_times', 1);

        $break = \App\Models\BreakTime::first();

        $this->assertNotNull($break);
        $this->assertNotNull($break->break_end);

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'status' => 'working',
        ]);
    }

    /** @test */
    public function 休憩は何回でもできる()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        // 出勤
        $this->post('/attendance/clockin');

        // 1回目
        $this->post('/attendance/break/start');
        $this->post('/attendance/break/end');

        // 2回目
        $this->post('/attendance/break/start');
        $this->post('/attendance/break/end');

        // 2件あること
        $this->assertEquals(2, \App\Models\BreakTime::count());
    }

    /** @test */
    public function 退勤できる()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        // 出勤
        $this->post('/attendance/clockin');

        // 退勤
        $this->post('/attendance/clockout');

        // clock_outが入ってるか
        $attendance = \App\Models\Attendance::first();

        $this->assertNotNull($attendance->clock_out);

        // ステータス確認
        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'status' => 'finished',
        ]);
    }

    /** @test */
    public function 自分の勤怠一覧が表示される()
    {
        $user = User::factory()->create();

        // 勤怠2件作成
        \App\Models\Attendance::create([
            'user_id' => $user->id,
            'work_date' => now()->subDays(1),
            'status' => 'finished'
        ]);

        \App\Models\Attendance::create([
            'user_id' => $user->id,
            'work_date' => now(),
            'status' => 'working'
        ]);

        $this->actingAs($user);

        $response = $this->get('/attendance/list');

        $response->assertStatus(200);

        // 日付が表示されているか
        $response->assertSee(now()->format('m/d'));
        $response->assertSee(now()->subDays(1)->format('m/d'));
    }

    /** @test */
    public function 前月の勤怠が表示される()
    {
        $user = User::factory()->create();

        // 3月のデータ
        \App\Models\Attendance::create([
            'user_id' => $user->id,
            'work_date' => now()->subMonth(),
            'status' => 'finished'
        ]);

        // 4月のデータ
        \App\Models\Attendance::create([
            'user_id' => $user->id,
            'work_date' => now(),
            'status' => 'working'
        ]);

        $this->actingAs($user);

        // 前月指定
        $response = $this->get('/attendance/list?date=' . now()->subMonth()->toDateString());

        $response->assertStatus(200);

        // 前月データが表示される
        $response->assertSee(now()->subMonth()->format('m/d'));
    }

    /** @test */
    public function 翌月の勤怠が表示される()
    {
        $user = User::factory()->create();

        // 今月
        \App\Models\Attendance::create([
            'user_id' => $user->id,
            'work_date' => now(),
            'status' => 'finished'
        ]);

        // 翌月
        \App\Models\Attendance::create([
            'user_id' => $user->id,
            'work_date' => now()->addMonth(),
            'status' => 'working'
        ]);

        $this->actingAs($user);

        // 翌月指定
        $response = $this->get('/attendance/list?date=' . now()->addMonth()->toDateString());

        $response->assertStatus(200);

        // 翌月データが表示される
        $response->assertSee(now()->addMonth()->format('m/d'));
    }

    /** @test */
    public function 勤怠詳細にユーザー名が表示される()
    {
        $user = User::factory()->create([
            'name' => 'テスト太郎'
        ]);

        $attendance = \App\Models\Attendance::create([
            'user_id' => $user->id,
            'work_date' => now(),
            'status' => 'working'
        ]);

        $this->actingAs($user);

        $response = $this->get('/attendance/detail/' . $attendance->id);

        $response->assertStatus(200);

        $response->assertSee('テスト太郎');
    }

    /** @test */
    public function 出勤退勤時間が表示される()
    {
        $user = User::factory()->create();

        $attendance = \App\Models\Attendance::create([
            'user_id' => $user->id,
            'work_date' => now(),
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
            'status' => 'finished'
        ]);

        $this->actingAs($user);

        $response = $this->get('/attendance/detail/' . $attendance->id);

        $response->assertSee('09:00');
        $response->assertSee('18:00');
    }

    /** @test */
    public function 出勤が退勤より後ならエラー()
    {
        $user = User::factory()->create();

        $attendance = \App\Models\Attendance::create([
            'user_id' => $user->id,
            'work_date' => now(),
            'status' => 'working'
        ]);

        $this->actingAs($user);

        $response = $this->post('/attendance/detail/' . $attendance->id, [
            'clock_in' => '18:00',
            'clock_out' => '09:00',
            'note' => 'テスト'
        ]);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function 休憩開始が退勤より後ならエラー()
    {
        $user = User::factory()->create();

        $attendance = \App\Models\Attendance::create([
            'user_id' => $user->id,
            'work_date' => now(),
            'status' => 'working'
        ]);

        $this->actingAs($user);

        $response = $this->post('/attendance/detail/' . $attendance->id, [
            'clock_in' => '09:00',
            'clock_out' => '17:00',
            'break_start_0' => '18:00',
            'break_end_0' => '19:00',
            'note' => 'テスト'
        ]);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function 備考未入力でエラー()
    {
        $user = User::factory()->create();

        $attendance = \App\Models\Attendance::create([
            'user_id' => $user->id,
            'work_date' => now(),
            'status' => 'working'
        ]);

        $this->actingAs($user);

        $response = $this->post('/attendance/detail/' . $attendance->id, [
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'note' => ''
        ]);

        $response->assertSessionHasErrors(['note']);
    }

    /** @test */
    public function 管理者は全ユーザーの勤怠を見れる()
    {
        // 管理者
        $admin = User::factory()->create([
            'is_admin' => true
        ]);

        // 一般ユーザー2人
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // 勤怠作成
        \App\Models\Attendance::create([
            'user_id' => $user1->id,
            'work_date' => now(),
            'status' => 'working'
        ]);

        \App\Models\Attendance::create([
            'user_id' => $user2->id,
            'work_date' => now(),
            'status' => 'finished'
        ]);

        $this->actingAs($admin);

        $response = $this->get('/admin/attendance/list');

        $response->assertStatus(200);

        // 両方のユーザーのデータが見える
        $response->assertSee($user1->name);
        $response->assertSee($user2->name);
    }

    /** @test */
    public function 管理者は前日の勤怠を見れる()
    {
        $admin = User::factory()->create([
            'is_admin' => true
        ]);

        $user = User::factory()->create();

        // 前日の勤怠
        \App\Models\Attendance::create([
            'user_id' => $user->id,
            'work_date' => now()->subDay(),
            'status' => 'working'
        ]);

        // 今日の勤怠
        \App\Models\Attendance::create([
            'user_id' => $user->id,
            'work_date' => now(),
            'status' => 'finished'
        ]);

        $this->actingAs($admin);

        // 前日指定
        $response = $this->get('/admin/attendance/list?date=' . now()->subDay()->toDateString());

        $response->assertStatus(200);

        // 前日のデータが表示される
        $response->assertSee(now()->subDay()->format('m/d'));
    }

    /** @test */
    public function 管理者は勤怠詳細を見れる()
    {
        $admin = User::factory()->create([
            'is_admin' => true
        ]);

        $user = User::factory()->create([
            'name' => '山田太郎'
        ]);

        $attendance = \App\Models\Attendance::create([
            'user_id' => $user->id,
            'work_date' => now(),
            'status' => 'working'
        ]);

        $this->actingAs($admin);

        $response = $this->get('/admin/attendance/' . $attendance->id);

        $response->assertStatus(200);

        $response->assertSee('山田太郎');
    }

    /** @test */
    public function 管理者は承認待ち申請を見れる()
    {
        $admin = User::factory()->create([
            'is_admin' => true
        ]);

        $user = User::factory()->create();

        $attendance = \App\Models\Attendance::create([
            'user_id' => $user->id,
            'work_date' => now(),
            'status' => 'working'
        ]);

        \App\Models\StampCorrectionRequest::create([
            'attendance_id' => $attendance->id,
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'note' => 'テスト',
            'status' => 'pending'
        ]);

        $this->actingAs($admin);

        $response = $this->get('/admin/stamp_correction_request/list');

        $response->assertStatus(200);

        $response->assertSee('テスト');
    }

    /** @test */
    public function 管理者は申請を承認できる()
    {
        $admin = User::factory()->create([
            'is_admin' => true
        ]);

        $user = User::factory()->create();

        $attendance = \App\Models\Attendance::create([
            'user_id' => $user->id,
            'work_date' => now(),
            'status' => 'working'
        ]);

        $requestData = \App\Models\StampCorrectionRequest::create([
            'attendance_id' => $attendance->id,
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'note' => 'テスト',
            'status' => 'pending'
        ]);

        $this->actingAs($admin);

        $this->post('/admin/stamp_correction_request/approve/' . $requestData->id);

        $this->assertDatabaseHas('stamp_correction_requests', [
            'id' => $requestData->id,
            'status' => 'approved'
        ]);
    }
}
