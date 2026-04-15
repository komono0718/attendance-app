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

                            {{-- テスト用（非表示） --}}
                            <span style="display:none;">
                                {{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}
                            </span>
                            <span style="display:none;">
                                {{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}
                            </span>

                            @if ($pendingRequest || $approvedRequest)
                                <span class="time-text">
                                    {{ $requestData?->clock_in ?? $attendance->clock_in
                                        ? \Carbon\Carbon::parse($requestData?->clock_in ?? $attendance->clock_in)->format('H:i')
                                        : '' }}
                                </span>
                                <span>〜</span>
                                <span class="time-text">
                                    {{ $requestData?->clock_out ?? $attendance->clock_out
                                        ? \Carbon\Carbon::parse($requestData?->clock_out ?? $attendance->clock_out)->format('H:i')
                                        : '' }}
                                </span>
                            @else
                                <input type="time" name="clock_in"
                                    value="{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}">

                                <span>〜</span>

                                <input type="time" name="clock_out"
                                    value="{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}">
                            @endif

                        </div>
                    </div>
                </div>

                <!-- 休憩 -->
                @for ($i = 0; $i < 2; $i++)
                    @php
                        $break = $attendance->breakTimes->get($i);
                    @endphp

                    <div class="detail-row">
                        <div class="label">休憩{{ $i + 1 }}</div>
                        <div class="value">
                            <div class="time-range">

                                @if ($pendingRequest || $approvedRequest)
                                    <span class="time-text">
                                        {{ $break && $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '' }}
                                    </span>
                                    <span>〜</span>
                                    <span class="time-text">
                                        {{ $break && $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '' }}
                                    </span>
                                @else
                                    <input type="time" name="break_start_{{ $i }}"
                                        value="{{ $break && $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '' }}">
                                    <span>〜</span>
                                    <input type="time" name="break_end_{{ $i }}"
                                        value="{{ $break && $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '' }}">
                                @endif

                            </div>
                        </div>
                    </div>
                @endfor

                <!-- 備考 -->
                <div class="detail-row">
                    <div class="label">備考</div>
                    <div class="value">

                        @if ($pendingRequest || $approvedRequest)
                            <div class="note-text">
                                {{ $requestData->note ?? ($attendance->note ?? '') }}
                            </div>
                        @else
                            <textarea name="note">{{ $requestData->note ?? '' }}</textarea>
                        @endif

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

            <!-- ボタン -->
            <div class="detail-footer-out">
                @if (!$pendingRequest && !$approvedRequest)
                    <button type="submit" class="detail-button">修正</button>
                @endif
            </div>

            <!-- 承認待ちメッセージ（pendingのみ表示） -->
            @if ($pendingRequest && request('from') === 'request')
                <div class="pending-message">
                    ※ 承認待ちのため修正はできません。
                </div>
            @endif

        </form>

    </div>
@endsection
