<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegisterTest extends TestCase
{
    /** @test */
    public function 名前が未入力だとエラーになる()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function メール未入力でエラー()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function パスワード8文字未満でエラー()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@test.com',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function パスワード不一致でエラー()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'aaa',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function 正常登録できる()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);
    }
}
