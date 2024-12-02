<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRegisterValidatedTest extends TestCase
{

    use RefreshDatabase;

    #[Test]
    public function test_que_valida_os_campos_vazios_durante_registro_de_usuario()
    {
        $data = [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        $response = $this->postJson(route('register'), $data);

        $response->assertStatus(422);
    }

    #[Test]
    public function test_que_exige_campo_de_confirmacao_de_senha_para_registro()
    {
        $data = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'senha12345689',
            'password_confirmation' => '',
        ];

        $response = $this->postJson(route('register'), $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password_confirmation');
    }
}
