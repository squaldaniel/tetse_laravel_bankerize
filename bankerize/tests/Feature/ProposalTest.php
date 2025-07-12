<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ProposalTest extends TestCase
{
    use RefreshDatabase;

    public function test_cadastra_proposta_com_dados_validos()
    {
        // Simula resposta bem-sucedida da API externa
        Http::fake([
            'https://util.devi.tools/api/v2/authorize' => Http::response(['status' => 'authorized'], 200),
            'https://util.devi.tools/api/v1/notify' => Http::response(['notified' => true], 200),
        ]);

        $payload = [
            "cpf" => "12345678900",
            "nome" => "José Silva",
            "data_nascimento" => "1990-01-01",
            "valor_emprestimo" => 1500.50,
            "chave_pix" => "jose@pix.com"
        ];

        $response = $this->postJson('/api/proposal', $payload);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'data' => [
                         'cpf',
                         'nome',
                         'valor_emprestimo',
                     ]
                 ]);
    }

    public function test_retorna_erro_quando_api_externa_falha()
    {
        // Simula falha da API de autorização
        Http::fake([
            'https://util.devi.tools/api/v2/authorize' => Http::response(null, 500),
            'https://util.devi.tools/api/v1/notify' => Http::response(null, 500),
        ]);

        $payload = [
            "cpf" => "12345678900",
            "nome" => "José Silva",
            "data_nascimento" => "1990-01-01",
            "valor_emprestimo" => 1500.50,
            "chave_pix" => "jose@pix.com"
        ];

        $response = $this->postJson('/api/proposal', $payload);

        $response->assertStatus(500)
                 ->assertJson([
                     'Message' => 'tente novamente em poucos instantes',
                 ]);
    }

    public function test_valida_campos_obrigatorios()
    {
        $response = $this->postJson('/api/proposal', []);

        $response->assertStatus(422) // erro de validação
                 ->assertJsonValidationErrors([
                     'cpf',
                     'nome',
                     'data_nascimento',
                     'valor_emprestimo',
                     'chave_pix',
                 ]);
    }
}
