@extends('layouts.app')

@section('content')
    <div class="attendance-container">

        <div class="attendance-card">

            {{-- ステータス表示だけ --}}
            <div class="attendance-status">
                @if ($attendance->status == 'off')
                    勤務外
                @elseif ($attendance->status == 'working')
                    出勤中
                @elseif ($attendance->status == 'break')
                    休憩中
                @elseif ($attendance->status == 'finished')
                    退勤済
                @endif
            </div>

            <div class="attendance-date">
                {{ now()->format('Y年n月j日') }}
            </div>

            <div class="attendance-time" id="clock">
                {{ now()->format('H:i') }}
            </div>

            {{-- ボタンはここに置く --}}
            <div class="attendance-action">

                @if ($attendance->status === 'off')
                    <form method="POST" action="/attendance/clockin">
                        @csrf
                        <button class="attendance-button">出勤</button>
                    </form>
                @elseif ($attendance->status === 'working')
                    <div class="attendance-buttons">

                        <form method="POST" action="/attendance/clockout">
                            @csrf
                            <button class="btn-black">退勤</button>
                        </form>

                        <form method="POST" action="/attendance/break/start">
                            @csrf
                            <button class="btn-white">休憩入</button>
                        </form>

                    </div>
                @elseif ($attendance->status === 'break')
                    <form method="POST" action="/attendance/break/end">
                        @csrf
                        <button class="attendance-button">休憩戻</button>
                    </form>
                @elseif ($attendance->status === 'finished')
                    <p>お疲れ様でした。</p>
                @endif

            </div>

        </div>

    </div>

    <script>
        function updateClock() {
            const now = new Date();
            let hours = now.getHours().toString().padStart(2, '0');
            let minutes = now.getMinutes().toString().padStart(2, '0');
            document.getElementById('clock').textContent = hours + ':' + minutes;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
@endsection
