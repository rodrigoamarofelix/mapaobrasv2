<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Obra extends Model
{
    protected $table = 'mapaobras';
    
    protected $fillable = [
        'id_projeto',
        'nome_projeto',
        'objeto',
        'area_tematica',
        'data_de_inicio_ou_previsao',
        'valor_total_do_projeto',
        'envolve_parceria_captacao_de_recursos',
        'tipo_de_instrumento',
        'numero_do_instrumento',
        'nome_do_parceiro_concedente',
        'início_vig_instrumento',
        'final_vig_Instrumento',
        'repasse_financeiro',
        'valor_recurso_parceiro',
        'contrapartida_pactuada',
        'projeto_possui_emenda_parlamentar',
        'numero_da_emenda',
        'situacao_obra',
        'data_prevista_conclusao',
        'estagio_execucao_percentual',
        'data_de_paralisacao',
        'data_previa_de_retomada',
        'motivo_da_paralisacao',
        'Tempo_de_paralisacao',
        'responsavel_pela_inexecucao',
        'valor_pago',
        'saldo_a_pagar',
        'situacao',
        'nomeDocumentoEmpreita',
        'linkDocumentoEmpreita',
        'valorEmpenhado',
        'valorLiquidado',
        'valorExecutado',
        'numeroContratoEmpreita',
        'numeroProcessoSEI'
    ];

    /**
     * Relacionamento com projeto_obras
     */
    public function projetoObras()
    {
        return $this->hasMany(ProjetoObra::class, 'id_mapa_obra');
    }

    /**
     * Deleta todas as obras
     */
    public static function deleteObras()
    {
        return DB::table('mapaobras')->delete();
    }

    /**
     * Adiciona uma obra
     */
    public static function add($data)
    {
        return DB::table('mapaobras')->insertGetId($data);
    }

    /**
     * Adiciona múltiplas obras em lote
     */
    public static function addBatch($data, $table = 'mapaobras')
    {
        return DB::table($table)->insert($data);
    }
}
