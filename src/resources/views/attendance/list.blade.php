<h1>管理者 勤怠一覧</h1>

<table border="1">

<tr>
<th>ユーザー</th>
<th>日付</th>
<th>出勤</th>
<th>退勤</th>
</tr>

@foreach($attendances as $attendance)

<tr>

<td>{{ $attendance->user->name }}</td>

<td>{{ $attendance->work_date }}</td>

<td>{{ $attendance->clock_in }}</td>

<td>{{ $attendance->clock_out }}</td>

</tr>

@endforeach

</table>