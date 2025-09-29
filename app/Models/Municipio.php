<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Municipio extends Model
{
    protected $table = 'municipios';
    
    protected $fillable = [
        'nome',
        'uf'
    ];

    /**
     * Deleta todos os municípios
     */
    public static function deleteMunicipios()
    {
        return DB::table('municipios')->delete();
    }

    /**
     * Adiciona múltiplos municípios em lote
     */
    public static function addBatch($data)
    {
        return DB::table('municipios')->insert($data);
    }
}
