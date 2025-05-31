<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Index extends Model
{
    protected $fillable = ['livro_id', 'indice_pai_id', 'titulo', 'pagina'];

    public function livro()
    {
        return $this->belongsTo(Book::class, 'livro_id');
    }

    public function pai()
    {
        return $this->belongsTo(Index::class, 'indice_pai_id');
    }

    public function filhos()
    {
        return $this->hasMany(Index::class, 'indice_pai_id');
    }
}
