<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    private $baseSql;

    public function __construct()
    {
        $this->baseSql = "
            SELECT
                mapaobras.id,
                mapaobras.id_projeto,
                mapaobras.nome_projeto,
                mapaobras.objeto,
                mapaobras.area_tematica,
                mapaobras.data_de_inicio_ou_previsao,
                mapaobras.valor_total_do_projeto,
                mapaobras.envolve_parceria_captacao_de_recursos,
                mapaobras.tipo_de_instrumento,
                mapaobras.numero_do_instrumento,
                mapaobras.nome_do_parceiro_concedente,
                mapaobras.início_vig_instrumento,
                mapaobras.final_vig_Instrumento,
                mapaobras.repasse_financeiro,
                mapaobras.valor_recurso_parceiro,
                mapaobras.contrapartida_pactuada,
                mapaobras.projeto_possui_emenda_parlamentar,
                mapaobras.numero_da_emenda,
                mapaobras.situacao_obra,
                mapaobras.data_prevista_conclusao,
                mapaobras.estagio_execucao_percentual,
                mapaobras.data_de_paralisacao,
                mapaobras.data_previa_de_retomada,
                mapaobras.motivo_da_paralisacao,
                mapaobras.Tempo_de_paralisacao,
                mapaobras.responsavel_pela_inexecucao,
                mapaobras.valor_pago,
                mapaobras.saldo_a_pagar,
                mapaobras.situacao,
                mapaobras.nomeDocumentoEmpreita,
                mapaobras.linkDocumentoEmpreita,
                mapaobras.valorEmpenhado,
                mapaobras.valorLiquidado,
                mapaobras.valorExecutado,
                projeto_obras.latitude,
                projeto_obras.longitude,
                projeto_obras.municipio
            FROM mapaobras
            LEFT JOIN projeto_obras ON projeto_obras.id_mapa_obra = mapaobras.id
            WHERE situacao_obra IN ('P','A', 'I', 'C', 'D')";
    }

    /**
     * Filtra obras com base nos parâmetros fornecidos
     */
    public function filter(Request $request)
    {
        try {
            // Log simples para debug
            error_log('Filter method called with params: ' . json_encode($request->all()));
            \Log::info('Filter method called with params', ['params' => $request->all()]);

            // Debug específico para arrays
            foreach ($request->all() as $key => $value) {
                if (is_array($value)) {
                    \Log::info("Array parameter $key", ['value' => $value]);
                }
            }
            $queryParams = [];
            $sql = $this->baseSql;

            // Parâmetros simples
            $nr_projeto = $request->get('nr_projeto');
            if (!empty($nr_projeto)) {
                $nr_projeto = intval($nr_projeto);
                $sql .= " AND (mapaobras.id = ? OR mapaobras.id_projeto = ?)";
                $queryParams[] = $nr_projeto;
                $queryParams[] = $nr_projeto;
            }

            // Parâmetros múltiplos (arrays)
            $multiParams = [
                'situacao_obra' => 'situacao_obra',
                'tipos_do_projeto' => 'tipos_do_projeto',
                'orgao' => 'sigla',
                'area_tematica' => 'area_tematica',
                'nm_obra' => 'nome_projeto',
                'municipio' => 'projeto_obras.municipio'
            ];

            foreach ($multiParams as $getKey => $dbColumn) {
                $values = $request->get($getKey);
                if (!empty($values)) {
                    if (!is_array($values)) {
                        $values = [$values]; // transforma em array se for string
                    }

                    // Para municípios, converter acentos e usar ILIKE para busca case-insensitive
                    if ($getKey === 'municipio') {
                        \Log::info("Processando municípios", ['values' => $values]);
                        $sql .= " AND $dbColumn ILIKE ANY(ARRAY[";
                        $placeholders = [];
                        foreach ($values as $v) {
                            $placeholders[] = '?';
                            // Converter acentos para formato do banco
                            $normalized = strtoupper(str_replace(['â', 'ã', 'ç'], ['a', 'a', 'c'], $v));
                            \Log::info("Município normalizado", ['original' => $v, 'normalized' => $normalized]);
                            $queryParams[] = $normalized;
                        }
                        $sql .= implode(',', $placeholders) . "])";
                    } else {
                        \Log::info("Processando $getKey", ['values' => $values]);
                        $placeholders = implode(',', array_fill(0, count($values), '?'));
                        $sql .= " AND $dbColumn IN ($placeholders)";
                        foreach ($values as $v) {
                            $queryParams[] = $v;
                        }
                    }
                }
            }

            $consulta = DB::select($sql, $queryParams);

            // Debug temporário
            \Log::info('SQL', ['sql' => $sql]);
            \Log::info('Params', ['params' => $queryParams]);
            \Log::info('Result count', ['count' => count($consulta)]);

            return response()->json($consulta);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao filtrar obras: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtém todas as obras sem filtros
     */
    public function index()
    {
        try {
            $consulta = DB::select($this->baseSql);
            return response()->json($consulta);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao obter obras: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtém uma obra específica por ID
     */
    public function show($id)
    {
        try {
            $sql = $this->baseSql . " AND mapaobras.id = ?";
            $consulta = DB::select($sql, [$id]);

            if (empty($consulta)) {
                return response()->json([
                    'error' => 'Obra não encontrada'
                ], 404);
            }

            return response()->json($consulta[0]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao obter obra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtém obras por projeto
     */
    public function getByProject($id_projeto)
    {
        try {
            $sql = $this->baseSql . " AND mapaobras.id_projeto = ?";
            $consulta = DB::select($sql, [$id_projeto]);

            return response()->json($consulta);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao obter obras do projeto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtém estatísticas das obras
     */
    public function statistics()
    {
        try {
            $stats = [];

            // Total de obras
            $stats['total_obras'] = DB::select("SELECT COUNT(*) as total FROM mapaobras WHERE situacao_obra IN ('P','A', 'I', 'C')")[0]->total;

            // Valor total dos projetos
            $stats['valor_total'] = DB::select("SELECT SUM(valor_total_do_projeto) as total FROM mapaobras WHERE situacao_obra IN ('P','A', 'I', 'C')")[0]->total;

            // Média de execução
            $stats['media_execucao'] = DB::select("SELECT AVG(estagio_execucao_percentual) as media FROM mapaobras WHERE situacao_obra IN ('P','A', 'I', 'C')")[0]->media;

            // Obras por situação
            $stats['por_situacao'] = DB::select("SELECT situacao_obra, COUNT(*) as total FROM mapaobras WHERE situacao_obra IN ('P','A', 'I', 'C') GROUP BY situacao_obra");

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao obter estatísticas: ' . $e->getMessage()
            ], 500);
        }
    }
}

