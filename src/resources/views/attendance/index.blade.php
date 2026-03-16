<h1>勤怠打刻</h1>

@if (session('message'))
    <p>{{ session('message') }}</p>
@endif

<form method="POST" action="/attendance/clockin">
    @csrf
    <button>出勤</button>
</form>

<form method="POST" action="/attendance/break/start">
    @csrf
    <button>休憩入</button>
</form>

<form method="POST" action="/attendance/break/end">
    @csrf
    <button>休憩戻</button>
</form>

<form method="POST" action="/attendance/clockout">
    @csrf
    <button>退勤</button>
</form>

<br>

<a href="/attendance/list">勤怠一覧を見る</a>
