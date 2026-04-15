<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>勤怠管理</title>

    <link rel="stylesheet" href="/css/app.css">

</head>

<body
    class="{{ request()->is('login') || request()->is('register') || request()->is('admin/login') || request()->is('email/verify*') ? 'auth-page' : '' }}">

    <header class="header">

        <div class="header-left">
            <img src="{{ asset('images/logo.png') }}" class="logo">
        </div>

        @if (Auth::check())

            <div class="header-right">

                @if (Auth::user()->is_admin)
                    <a href="{{ url('/admin/attendance/list') }}">勤怠一覧</a>
                    <a href="{{ url('/admin/staff/list') }}">スタッフ一覧</a>
                    <a href="{{ url('/admin/stamp_correction_request/list') }}">申請一覧</a>
                @else
                    <a href="{{ url('/attendance') }}">勤怠</a>
                    <a href="{{ url('/attendance/list') }}">勤怠一覧</a>
                    <a href="{{ url('/stamp_correction_request/list') }}">申請</a>
                @endif

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">ログアウト</button>
                </form>

            </div>

        @endif

    </header>

    <main>

        @yield('content')

    </main>

</body>

</html>
