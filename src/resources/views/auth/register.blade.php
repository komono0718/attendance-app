@extends('layouts.app')

@section('content')
    <div class="auth-page">
        <div class="auth-container">

            <div class="auth-title">会員登録</div>

            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf

                <div class="form-group">
                    <label>名前</label>
                    <input type="text" name="name" value="{{ old('name') }}">

                    @error('name')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>メールアドレス</label>
                    <input type="email" name="email" value="{{ old('email') }}">

                    @error('email')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>パスワード</label>
                    <input type="password" name="password">

                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>パスワード（確認用）</label>
                    <input type="password" name="password_confirmation">

                    @error('password_confirmation')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <button class="auth-button">登録する</button>

                <div class="auth-link">
                    <a href="{{ route('login') }}">ログインはこちら</a>
                </div>

            </form>

        </div>
    @endsection
