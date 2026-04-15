@extends('layouts.app')

@section('content')
    <div class="auth-container">

        <div class="auth-message">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </div>

        <div class="auth-button-wrap">
            <a href="http://localhost:8025" target="_blank" class="auth-button auth-button-outline">
                認証はこちらから
            </a>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="auth-success">
                認証メールを再送しました。
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="auth-resend">
                認証メールを再送する
            </button>
        </form>

    </div>
@endsection
