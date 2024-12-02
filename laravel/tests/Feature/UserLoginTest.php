<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use function PHPUnit\Framework\assertJson;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_que_loga_com_credenciais_validas_e_conta_ativa()
    {
        $user = User::query()->create([
            'name' => 'Test User',
            'email' => 'testUser@example.com',
            'password' => bcrypt('password12345678'),
            'is_active' => true,
        ]);

        $data = [
            'email' => $user->email,
            'password' => 'password12345678',
        ];

        $response = $this->post(route('login'), $data);

        $response->assertStatus(200);
    }

    #[Test]
    public function test_que_falha_ao_logar_com_credenciais_invalidas()
    {
        $data = [
            'email' => 'error@example.com',
            'password' => 'error@gemail.com',
        ];

        $response = $this->postJson(route('login'), $data);

        $response->assertStatus(401);

        $response->assertJson(['error' => 'Unauthorized']);
    }

    #[Test]
    public function test_que_falha_ao_logar_com_conta_inativa()
    {
        $user = User::query()->create([
            'name' => 'Test User',
            'email' => 'testUser@example.com',
            'password' => bcrypt('password12345678'),
            'is_active' => false,
        ]);

        $data = [
            'email' => $user->email,
            'password' => 'password12345678',
        ];

        $response = $this->postJson(route('login'), $data);

        $response->assertStatus(400);

        $response->assertJson(['error' => 'Sua conta ainda não foi ativada. Verifique seu e-mail.']);
    }

    #[Test]
    public function test_que_falha_ao_acessar_rotas_protegidas_apos_logout()
    {
        $user = User::factory()->create();

        $token = JWTAuth::fromUSer($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->getJson(route('logout'));

        $response->assertStatus(200);

        $response->assertJson(['message' => 'Usuário deslogado com sucesso.']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->getJson(route('show-contact'));

        $response->assertStatus(401);

    }
}
