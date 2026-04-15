@extends('layouts.app')

@section('content')
    <div class="attendance-list-container">

        <div class="page-title-left">申請一覧</div>

        <!-- タブ -->
        <div class="request-tabs">
            <a href="?status=pending" class="tab {{ request('status') != 'approved' ? 'active' : '' }}">承認待ち</a>
            <a href="?status=approved" class="tab {{ request('status') == 'approved' ? 'active' : '' }}">承認済み</a>
        </div>

        <!-- テーブル -->
        <div class="request-card">
            <table class="request-table">
                <thead>
                    <tr>
                        <th>状態</th>
                        <th>名前</th>
                        <th>対象日時</th>
                        <th>申請理由</th>
                        <th>申請日時</th>
                        <th>詳細</th>
                    </tr>
                </thead>

                <tbody>

                    @if (request('status') == 'approved')
                        @foreach ($approved as $request)
                            <tr>
                                <td>承認済み</td>
                                <td>{{ $request->attendance->user->name ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($request->attendance->work_date)->format('Y/m/d') }}</td>
                                <td>{{ $request->note }}</td>
                                <td>{{ $request->created_at->format('Y/m/d') }}</td>
                                <td>
                                    <a href="/admin/stamp_correction_request/detail/{{ $request->id }}" class="detail-link">
                                        詳細
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        @foreach ($pending as $request)
                            <tr>
                                <td>承認待ち</td>
                                <td>{{ $request->attendance->user->name ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($request->attendance->work_date)->format('Y/m/d') }}</td>
                                <td>{{ $request->note }}</td>
                                <td>{{ $request->created_at->format('Y/m/d') }}</td>
                                <td>
                                    <a href="/admin/stamp_correction_request/detail/{{ $request->id }}"
                                        class="detail-link">
                                        詳細
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
        </div>

    </div>
@endsection
