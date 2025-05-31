<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use App\Jobs\ImportIndicesFromXml;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['indices']);

        if ($request->filled('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }

        if ($request->filled('titulo_do_indice')) {
            $query->whereHas('indices', function ($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->titulo_do_indice . '%');
            });
        }

        return response()->json($query->get());
    }


    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string',
            'indices' => 'array',
            'indices.*.titulo' => 'required|string',
            'indices.*.pagina' => 'required|integer',
            'indices.*.indice_pai_id' => 'nullable|integer',
        ]);

        $livro = Book::create([
            'titulo' => $request->titulo,
            'usuario_publicador_id' => auth()->id(),
        ]);

        foreach ($request->indices as $indiceData) {
            $livro->indices()->create($indiceData);
        }

        return response()->json($livro->load('indices'), 201);
    }

    public function importIndices(Request $request, Book $livro)
    {
        $request->validate([
            'arquivo_xml' => 'required|file|mimes:xml'
        ]);

        $xmlContent = file_get_contents($request->file('arquivo_xml')->getRealPath());
        ImportIndicesFromXml::dispatch($livro, $xmlContent);

        return response()->json(['message' => 'Importação iniciada'], 202);
    }
}

// app/Http/Requests/StoreBookRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255'
        ];
    }
}
