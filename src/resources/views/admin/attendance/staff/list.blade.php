@extends('layouts.app')

@section('content')
    <div class="attendance-list-container">

        <div class="page-title-left">
            {{ $user->name }}さんの勤怠
        </div>

        <div class="date-nav-box">
            <a href="?date={{ \Carbon\Carbon::parse($date)->subMonth()->toDateString() }}" class="nav-btn">
                ← 前月
            </a>

            <div class="date-center">
                📅 {{ \Carbon\Carbon::parse($date)->format('Y/m') }}
            </div>

            <a href="?date={{ \Carbon\Carbon::parse($date)->addMonth()->toDateString() }}" class="nav-btn">
                翌月 →
            </a>
        </div>

        <table class="attendance-table">

            <thead>
                <tr>
                    <th>日付</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($days as $day)
                    @php
                        $attendance = $day['attendance'];
                    @endphp

                    <tr>

                        <!-- 日付 -->
                        <td>
                            {{ \Carbon\Carbon::parse($day['date'])->isoFormat('MM/DD(ddd)') }}
                        </td>

                        <!-- 出勤 -->
                        <td>
                            @if ($attendance && $attendance->clock_in)
                                {{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}
                            @endif
                        </td>

                        <!-- 退勤 -->
                        <td>
                            @if ($attendance && $attendance->clock_out)
                                {{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}
                            @endif
                        </td>

                        <!-- 休憩 -->
                        <td>
                            @if ($attendance)
                                @php
                                    $totalBreak = 0;
                                    foreach ($attendance->breakTimes as $break) {
                                        if ($break->break_end) {
                                            $totalBreak +=
                                                strtotime($break->break_end) - strtotime($break->break_start);
                                        }
                                    }
                                @endphp

                                {{ $totalBreak ? gmdate('H:i', $totalBreak) : '-' }}
                            @endif
                        </td>

                        <!-- 合計 -->
                        <td>
                            @if ($attendance && $attendance->clock_in && $attendance->clock_out)
                                @php
                                    $workTime = strtotime($attendance->clock_out) - strtotime($attendance->clock_in);

                                    $totalBreak = 0;
                                    foreach ($attendance->breakTimes as $break) {
                                        if ($break->break_end) {
                                            $totalBreak +=
                                                strtotime($break->break_end) - strtotime($break->break_start);
                                        }
                                    }

                                    $workTime -= $totalBreak;
                                @endphp

                                {{ $workTime > 0 ? gmdate('H:i', $workTime) : '-' }}
                            @endif
                        </td>

                        <!-- 詳細 -->
                        <td>
                            <a href="/attendance/detail?date={{ $day['date'] }}" class="detail-link">
                                詳細
                            </a>
                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

        {{-- CSVボタン --}}
        @if (!empty($days))
            <div style="text-align:right; margin-top:20px;">
                <a href="/admin/attendance/csv/{{ $user->id }}" class="csv-button">
                    CSV出力
                </a>
            </div>
        @endif

    </div>
@endsection
