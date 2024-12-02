<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserContactsTest extends TestCase
{
    use RefreshDatabase;


    #[Test]
    public function test_que_o_usuario_logado_possa_ver_seus_contatos()
    {
        $user = User::query()->create([
            'name' => 'Test User',
            'email' => 'testUser@example.com',
            'password' => bcrypt('password12345678'),
            'is_active' => true,
        ]);

        $user->contacts()->create([
           'name' => 'Test Contact',
           'email' => 'testContact@example.com',
           'phone' => '+1234567890',
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->get(route('show-contact'));

        $response->assertStatus(200);

    }

    #[Test]
    public function test_que_usuario_sem_contatos_receba_mensagem_de_erro()
    {
        $user = User::query()->create([
            'name' => 'Test User',
            'email' => 'testUser@example.com',
            'password' => bcrypt('password12345678'),
            'is_active' => true,
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->get(route('show-contact'));

        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Você não possui contato.'
        ]);
    }

    #[Test]
    public function test_que_o_usuario_logado_possa_criar_contatos()
    {
        $user = User::query()->create([
            'name' => 'Test User',
            'email' => 'testUser@example.com',
            'password' => bcrypt('password12345678'),
            'is_active' => true,
        ]);

        $token = JWTAuth::fromUser($user);

        $contactData = [
            'name' => 'Test Contact',
            'email' => 'testContact@example.com',
            'phone' => '+1234567890',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson(route('create.conctact'), $contactData);

        $response->assertStatus(201);

        $response->assertJson(['message' => 'Contato criado com sucesso!']);

    }

    #[Test]
    public function test_que_o_usuario_receba_error_com_token_invalido()
    {

        $tokenInvalido = "1234567890";

        $response = $this->withHeader('Authorization', 'Bearer ' . $tokenInvalido)->getJson(route('show-contact'));

        $response->assertStatus(401);

    }

    #[Test]
    public function test_que_o_usuario_logado_edite_um_contato()
    {
        $user = User::factory()->create();

        $contact = $user->contacts()->create([
            'name' => 'Test Contact',
            'phone' => '+1234567890',
        ]);

        $token = JWTAuth::fromUser($user);

        $updatedData = [
            'name' => 'Test Contact',
            'phone' => '+12345678999',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson(route('updated.contact', $contact->id), $updatedData);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Contato atualizado com sucesso!']);
    }

    #[Test]
    public function test_que_o_id_do_usuario_nao_encontrado_para_edicao()
    {

        $user = User::factory()->create();

        $user->contacts()->create([
            'name' => 'Test Contact',
            'phone' => '+1234567890',
        ]);

        $token = JWTAuth::fromUser($user);

        $updatedData = [
            'name' => 'Test Contact',
            'phone' => '+12345678999',
        ];

        $idInvalido = "1234567890";

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson(route('updated.contact', $idInvalido), $updatedData);

        $response->assertStatus(404);

        $response->assertJson(['error' => 'Contato não encontrado.']);
    }

    #[Test]
    public function test_que_o_usuario_logado_exclua_um_contato()
    {
        $user = User::factory()->create();

        $contactData = [
            'name' => 'Test Contact',
            'phone' => '+1234567890',
        ];

        $contact = $user->contacts()->create($contactData);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson(route('delete.contact', $contact->id));

        $response->assertStatus(200);

    }
}
