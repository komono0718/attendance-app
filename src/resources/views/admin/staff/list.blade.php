<h1>スタッフ一覧</h1>

<table border="1">

    <tr>
        <th>名前</th>
        <th>Email</th>
        <th>CSV</th>
    </tr>

    @foreach ($users as $user)
        <tr>

            <td>{{ $user->name }}</td>

            <td>{{ $user->email }}</td>

            <td>

                <a href="/admin/attendance/csv/{{ $user->id }}">
                    CSV出力
                </a>

            </td>

        </tr>
    @endforeach

</table>
