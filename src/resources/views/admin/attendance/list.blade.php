@extends('layouts.app')

@section('content')
    <div class="attendance-list-container">

        <div class="page-title-left">
            {{ \Carbon\Carbon::parse($date)->format('Y年n月j日') }}の勤怠
        </div>

        <div class="date-nav-box">
            <a href="?date={{ \Carbon\Carbon::parse($date)->subDay()->toDateString() }}" class="nav-btn">
                ← 前日
            </a>

            <div class="date-center">
                📅 {{ \Carbon\Carbon::parse($date)->format('Y/m/d') }}
            </div>

            <a href="?date={{ \Carbon\Carbon::parse($date)->addDay()->toDateString() }}" class="nav-btn">
                翌日 →
            </a>
        </div>

        <table class="attendance-table">

            <thead>
                <tr>
                    <th>名前</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->user->name }}</td>
                        <td>{{ optional($attendance->clock_in)->format('H:i') }}</td>
                        <td>{{ optional($attendance->clock_out)->format('H:i') }}</td>
                        <td>
                            @php
                                $totalBreak = 0;
                                foreach ($attendance->breakTimes as $break) {
                                    if ($break->break_end) {
                                        $totalBreak += strtotime($break->break_end) - strtotime($break->break_start);
                                    }
                                }
                            @endphp

                            {{ $totalBreak ? gmdate('H:i', $totalBreak) : '-' }}
                        </td>
                        <td>
                            @php
                                $workTime = 0;

                                if ($attendance->clock_in && $attendance->clock_out) {
                                    $workTime = strtotime($attendance->clock_out) - strtotime($attendance->clock_in);

                                    $totalBreak = 0;
                                    foreach ($attendance->breakTimes as $break) {
                                        if ($break->break_end) {
                                            $totalBreak +=
                                                strtotime($break->break_end) - strtotime($break->break_start);
                                        }
                                    }

                                    $workTime -= $totalBreak;
                                }
                            @endphp

                            {{ $workTime > 0 ? gmdate('H:i', $workTime) : '-' }}
                        </td>
                        <td>
                            <a href="/admin/attendance/{{ $attendance->id }}" class="detail-link">
                                詳細
                            </a>
                        </td>
                    </tr>
                @endforeach

            </tbody>

        </table>

    </div>
@endsection
