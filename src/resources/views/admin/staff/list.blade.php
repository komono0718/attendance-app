@extends('layouts.app')

@section('content')
    <div class="attendance-list-container">

        <div class="page-title-left">スタッフ一覧</div>

        <table class="attendance-table">

            <thead>
                <tr>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>詳細</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($users as $user)
                    <tr>

                        <td>{{ $user->name }}</td>

                        <td>{{ $user->email }}</td>

                        <td>
                            <a href="/admin/attendance/staff/{{ $user->id }}" class="detail-link">
                                詳細
                            </a>
                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

    </div>
@endsection
