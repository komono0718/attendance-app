@extends('layouts.app')

@section('content')
    <div class="attendance-detail-container">

        <div class="page-title-left">勤怠詳細</div>

        <div class="detail-card">

            <div class="detail-row">
                <div class="label">名前</div>
                <div class="value">
                    {{ $request->attendance->user->name }}
                </div>
            </div>

            <div class="detail-row">
                <div class="label">日付</div>
                <div class="value date-fix">
                    <span>{{ \Carbon\Carbon::parse($request->attendance->work_date)->format('Y年') }}</span>
                    <span class="dummy">〜</span>
                    <span>{{ \Carbon\Carbon::parse($request->attendance->work_date)->format('n月j日') }}</span>
                </div>
            </div>

            <div class="detail-row">
                <div class="label">出勤・退勤</div>
                <div class="value">
                    <span class="time-text">
                        {{ \Carbon\Carbon::parse($request->clock_in)->format('H:i') }}
                    </span>
                    〜
                    <span class="time-text time-end">
                        {{ \Carbon\Carbon::parse($request->clock_out)->format('H:i') }}
                    </span>
                </div>
            </div>

            @for ($i = 0; $i < 2; $i++)
                @php
                    $break = $request->attendance->breakTimes->get($i);
                @endphp

                <div class="detail-row">
                    <div class="label">休憩{{ $i + 1 }}</div>
                    <div class="value">

                        @if ($break && ($break->break_start || $break->break_end))
                            <span class="time-text">
                                {{ $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '' }}
                            </span>
                            〜
                            <span class="time-text time-end">
                                {{ $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '' }}
                            </span>
                        @endif

                    </div>
                </div>
            @endfor

            <div class="detail-row">
                <div class="label">備考</div>
                <div class="value">
                    {{ $request->note }}
                </div>
            </div>

        </div>

        <div class="detail-footer-out">

            @if ($request->status === 'approved')
                <button class="approve-button done" disabled>承認済み</button>
            @else
                <form method="POST" action="/admin/request/approve/{{ $request->id }}">
                    @csrf
                    <button class="approve-button">承認</button>
                </form>
            @endif

        </div>

    </div>
@endsection
