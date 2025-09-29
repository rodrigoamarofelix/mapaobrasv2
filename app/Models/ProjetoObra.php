<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProjetoObra extends Model
{
    protected $table = 'projeto_obras';

    protected $fillable = [
        'id_mapa_obra',
        'municipio',
        'latitude',
        'longitude'
    ];

    /**
     * Relacionamento com obra
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_mapa_obra');
    }

    /**
     * Adiciona múltiplas obras de projeto em lote
     */
    public static function addBatch($data)
    {
        return DB::table('projeto_obras')->insert($data);
    }
}
