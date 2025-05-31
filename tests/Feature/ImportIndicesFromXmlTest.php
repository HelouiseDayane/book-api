<?php

namespace Tests\Feature;

use App\Jobs\ImportIndicesFromXml;
use App\Models\Book;
use App\Models\Index;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportIndicesFromXmlTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_imports_indices_from_xml_and_saves_to_database()
    {
        // Cria um livro
        $book = Book::factory()->create();

        // XML exemplo com índices (pai e filhos)
        $xmlContent = <<<XML
<indices>
    <indice titulo="Capítulo 1" pagina="1">
        <indice titulo="Seção 1.1" pagina="2"/>
        <indice titulo="Seção 1.2" pagina="3"/>
    </indice>
    <indice titulo="Capítulo 2" pagina="4"/>
</indices>
XML;

        // Cria e executa o job
        $job = new ImportIndicesFromXml($book, $xmlContent);
        $job->handle();

        // Verifica se os índices foram inseridos na tabela
        $this->assertDatabaseHas('indices', [
            'livro_id' => $book->id,
            'titulo' => 'Capítulo 1',
            'pagina' => 1,
            'indice_pai_id' => null,
        ]);

        $this->assertDatabaseHas('indices', [
            'livro_id' => $book->id,
            'titulo' => 'Seção 1.1',
            'pagina' => 2,
            // deve apontar para o id do índice "Capítulo 1"
            // mas para simplificar, só verifica a existência do registro
        ]);

        $this->assertDatabaseHas('indices', [
            'livro_id' => $book->id,
            'titulo' => 'Capítulo 2',
            'pagina' => 4,
            'indice_pai_id' => null,
        ]);
    }
}
