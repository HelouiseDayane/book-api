<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\Index;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;     
use Illuminate\Bus\Queueable;       
use Illuminate\Queue\SerializesModels;

class ImportIndicesFromXml implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Book $book,
        protected string $xmlContent
    ) {}

    public function handle()
    {
        $xml = simplexml_load_string($this->xmlContent);
        $this->parseAndSave($xml, null);
    }

    private function parseAndSave($xmlNode, $indicePaiId)
    {
        foreach ($xmlNode->indice as $node) {
            $index = Index::create([
                'livro_id' => $this->book->id,
                'indice_pai_id' => $indicePaiId,
                'titulo' => (string)$node['titulo'],   
                'pagina' => (int)$node['pagina'],     
            ]);

            if (isset($node->indice)) {
                $this->parseAndSave($node, $index->id);
            }
        }
    }

}
