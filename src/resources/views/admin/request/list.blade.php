<h1>修正申請一覧（管理者）</h1>

<table border="1">

    <tr>
        <th>ID</th>
        <th>ユーザー</th>
        <th>出勤修正</th>
        <th>退勤修正</th>
        <th>備考</th>
        <th>状態</th>
        <th>操作</th>
    </tr>

    @foreach ($requests as $request)
        <tr>

            <td>{{ $request->id }}</td>
            <td>{{ $request->user_id }}</td>
            <td>{{ $request->requested_clock_in }}</td>
            <td>{{ $request->requested_clock_out }}</td>
            <td>{{ $request->note }}</td>
            <td>{{ $request->status }}</td>

            <td>

                @if ($request->status == 'pending')
                    <form method="POST" action="/admin/stamp_correction_request/approve/{{ $request->id }}">
                        @csrf
                        <button>承認</button>
                    </form>
                @endif

            </td>

        </tr>
    @endforeach

</table>
