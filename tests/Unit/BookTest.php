<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_logado_pode_cadastrar_livro()
    {
        
        $user = User::factory()->create();

      
        $payload = [
            'titulo' => 'Livro de Teste',
            'indices' => [
                [
                    'titulo' => 'Capítulo 1',
                    'pagina' => 1,
                    'indice_pai_id' => null,
                ],
                [
                    'titulo' => 'Seção 1.1',
                    'pagina' => 5,
                    'indice_pai_id' => 1, 
                ],
            ],
        ];

        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/livros', $payload);

  

       
        $response->assertStatus(201);

      
        $this->assertDatabaseHas('books', [
            'titulo' => 'Livro de Teste',
            'usuario_publicador_id' => $user->id,
        ]);

     
        $this->assertDatabaseHas('indices', [
            'titulo' => 'Capítulo 1',
            'pagina' => 1,
        ]);
    }

    public function test_usuario_nao_autenticado_nao_pode_cadastrar_livro()
    {
        $payload = ['titulo' => 'Livro sem auth'];

        $response = $this->postJson('/api/v1/livros', $payload);

        $response->assertStatus(401); 
    }

    public function test_usuario_logado_pode_listar_livros()
    {
      
        $user = User::factory()->create();
        $book = \App\Models\Book::factory()->create(['usuario_publicador_id' => $user->id]);

       
        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/livros');

      
        $response->assertStatus(200);

     
        $response->assertJsonFragment(['titulo' => $book->titulo]);
    }
}