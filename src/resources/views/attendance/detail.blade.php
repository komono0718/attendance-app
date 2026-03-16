<h1>勤怠詳細</h1>

<p>日付: {{ $attendance->work_date }}</p>

<p>出勤: {{ $attendance->clock_in }}</p>

<p>退勤: {{ $attendance->clock_out }}</p>

<p>ステータス: {{ $attendance->status }}</p>

<br>

<a href="/attendance/list">一覧へ戻る</a>