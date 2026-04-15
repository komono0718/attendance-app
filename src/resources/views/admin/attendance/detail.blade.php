@extends('layouts.app')

@section('content')
    <div class="attendance-detail-container">

        <div class="page-title-left">勤怠詳細</div>

        <form method="POST" action="/attendance/detail/{{ $attendance->id }}">
            @csrf

            <div class="detail-card">

                <!-- 名前 -->
                <div class="detail-row">
                    <div class="label">名前</div>
                    <div class="value name-value">
                        {{ $attendance->user->name }}
                    </div>
                </div>

                <!-- 日付 -->
                <div class="detail-row">
                    <div class="label">日付</div>
                    <div class="value">
                        <div class="time-range date-fix">
                            <span>{{ \Carbon\Carbon::parse($attendance->work_date)->format('Y年') }}</span>
                            <span class="dummy">〜</span>
                            <span>{{ \Carbon\Carbon::parse($attendance->work_date)->format('n月j日') }}</span>
                        </div>
                    </div>
                </div>

                <!-- 出勤・退勤 -->
                <div class="detail-row">
                    <div class="label">出勤・退勤</div>
                    <div class="value">
                        <div class="time-range">
                            <input type="time" name="clock_in"
                                value="{{ optional($attendance->clock_in)->format('H:i') }}">
                            <span>〜</span>
                            <input type="time" name="clock_out"
                                value="{{ optional($attendance->clock_out)->format('H:i') }}">
                        </div>
                    </div>
                </div>

                <!-- 休憩（2枠固定） -->
                @for ($i = 0; $i < 2; $i++)
                    @php
                        $break = $attendance->breakTimes->get($i);
                    @endphp

                    <div class="detail-row">
                        <div class="label">休憩{{ $i + 1 }}</div>
                        <div class="value">
                            <div class="time-range">
                                <input type="time" name="break_start_{{ $i }}"
                                    value="{{ $break && $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '' }}">
                                <span>〜</span>
                                <input type="time" name="break_end_{{ $i }}"
                                    value="{{ $break && $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '' }}">
                            </div>
                        </div>
                    </div>
                @endfor

                <!-- 備考 -->
                <div class="detail-row">
                    <div class="label">備考</div>
                    <div class="value">
                        <textarea name="note">{{ $attendance->note ?? '' }}</textarea>
                    </div>
                </div>

            </div>

            <!-- エラー -->
            @if ($errors->any())
                <div class="error-box">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- 修正ボタン -->
            <div class="detail-footer-out">
                <button type="submit" class="detail-button">修正</button>
            </div>

        </form>

    </div>
@endsection
