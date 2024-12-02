<?php

namespace Tests\Feature;

use App\Mail\ActivationMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{

    use RefreshDatabase;

    #[Test]
    public function test_que_registra_usuario_no_banco_de_dados()
    {
        $data = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'senha12345689',
            'password_confirmation' => 'senha12345689',
        ];

        $response = $this->postJson(route('register'), $data);

        $response->assertStatus(201);

        $response->assertJson([
            'message' => 'Cadastro realizado com sucesso! Verifique seu e-mail para ativar sua conta.'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'is_active' => false,
        ]);
    }

    #[Test]
    public function test_que_envia_email_de_ativacao_apos_registro()
    {
        $data = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'senha12345689',
            'password_confirmation' => 'senha12345689',
        ];

        Mail::fake();

        $this->postJson(route('register'), $data);

        Mail::assertSent(ActivationMail::class, function ($mail) use ($data) {
            return $mail->hasTo($data['email']);
        });

        $user = User::query()->where('email', $data['email'])->first();
        $activationLink = route('activate', ['token' => $user->activation_token]);

        Mail::assertSent(ActivationMail::class, function ($mail) use ($activationLink) {
            return str_contains($mail->render(), $activationLink);
        });
    }
}
