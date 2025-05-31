<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
      use HasFactory;
    protected $fillable = ['titulo', 'usuario_publicador_id'];

    public function publicador()
    {
        return $this->belongsTo(User::class, 'usuario_publicador_id');
    }

    public function indices()
    {
        return $this->hasMany(Index::class, 'livro_id');
    }
}

