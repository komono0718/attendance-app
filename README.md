# 勤怠管理アプリ

## アプリケーション概要
本アプリケーションは、ユーザーの勤怠管理を目的としたWebアプリケーションです。  
ユーザーは出勤・休憩・退勤の打刻を行うことができ、管理者は全スタッフの勤怠を管理することができます。

---

## 機能一覧

### 一般ユーザー
- 会員登録
- ログイン / ログアウト
- 出勤打刻
- 休憩入 / 休憩戻
- 退勤打刻
- 勤怠一覧表示
- 勤怠詳細表示
- 勤怠修正申請
- 申請一覧表示

### 管理者
- 管理者ログイン / ログアウト
- 日次勤怠一覧表示
- 勤怠詳細確認 / 修正
- スタッフ一覧表示
- スタッフ別勤怠一覧表示
- 修正申請承認
- CSV出力

---

## 使用技術

- PHP 8.1
- Laravel 10
- MySQL
- Docker

---

# 環境構築

## Dockerビルド

1. リポジトリをクローン
git clone git@github.com:komono0718/attendance-app.git

2. Dockerコンテナ起動

```bash
docker compose up -d –-build
```

---

# Laravel環境構築

1. PHPコンテナに入る

```bash
docker compose exec php bash
```

2. composerインストール

```bash
composer install
```

3. .env作成

```bash
cp .env.example .env
```

4. アプリケーションキー作成

```bash
php artisan key:generate
```

5. マイグレーション

```bash
php artisan migrate
```

6. シーディング

```bash
php artisan db:seed
```

7. storageリンク

```bash
php artisan storage:link
```

# 使用技術
	•	PHP 8.1
	•	Laravel 10
	•	MySQL
	•	Docker
	•	Laravel Fortify（認証）

# URL
	•	開発環境
http://localhost/
	•	ログイン
http://localhost/login
	•	phpMyAdmin
http://localhost:8080/

# 機能一覧

## 認証
	•	会員登録
	•	ログイン
	•	ログアウト
	•	メール認証

## 勤怠管理（ユーザー）
	•	出勤打刻
	•	休憩入
	•	休憩戻
	•	退勤打刻
	•	勤怠一覧表示
	•	勤怠詳細表示
	•	勤怠修正申請
	•	申請一覧表示

## 管理者
	•	日次勤怠一覧表示
	•	勤怠詳細確認 / 修正
	•	スタッフ一覧表示
	•	スタッフ別勤怠一覧表示
	•	修正申請承認
	•	CSV出力

# テーブル設計
テーブル
説明
users
ユーザー
attendances
勤怠情報
break_times
休憩時間
attendance_correction_requests
勤怠修正申請

## ログイン情報

### 管理者
email: admin@test.com
password: password

### 一般ユーザー
email: user1@test.com
password: password

email: user2@test.com
password: password

# ER図
