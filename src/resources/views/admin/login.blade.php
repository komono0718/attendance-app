@extends('layouts.app')

@section('content')
    <div class="auth-page admin-login">
        <div class="auth-container">

            <div class="auth-title">管理者ログイン</div>

            <form method="POST" action="{{ route('login') }}" class="auth-form">
                @csrf

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

                <button class="auth-button">管理者ログインする</button>

            </form>

        </div>
    @endsection
