<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
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
            INNER JOIN projeto_obras ON projeto_obras.id_mapa_obra = mapaobras.id
            WHERE situacao_obra IN ('P','A', 'I', 'C', 'D')";
    }

    /**
     * Exibe a página inicial do sistema (obrasMaps)
     */
    public function index()
    {
        return $this->obrasMaps();
    }

    /**
     * Exibe página de estados
     */
    public function saibaMais()
    {
        return view('landing.estados');
    }

    /**
     * Exibe página de BI
     */
    public function bi()
    {
        return view('landing.bi');
    }

    /**
     * Obtém obras em destaque por estado
     */
    public function getObrasDestaque()
    {
        $query = "SELECT count(id) as total_obras, sigla, SUM(valor_total_do_projeto) as valor_total, AVG(estagio_execucao_percentual) as media_execucao FROM mapaobras WHERE 1 = 1 GROUP BY sigla";

        try {
            $consulta = DB::select($query);
            return $consulta;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Obtém lista de estados/municípios
     */
    public function getEstados()
    {
        $query = 'SELECT nome, uf FROM municipios';

        try {
            $results = DB::select($query);
            return $results;
        } catch (\Exception $e) {
            return ['error' => 'Erro ao obter os municípios.'];
        }
    }

    /**
     * Exibe mapa de obras
     */
    public function obrasMaps()
    {
        try {
            $consulta = DB::select($this->baseSql);

            $data = [
                'locations' => $consulta,
                'situacoes' => $this->getSituacaoObrasMaps(),
                'tipos_de_projetos' => $this->getTiposDeProjetos(),
                'orgao' => $this->getOrgaosObras(),
                'areaTematica' => $this->getAreaTematica(),
                'municipios' => $this->getEstados(),
                'nome_obras' => $this->getNomeObras()
            ];

            return view('landing.obras_mapas', $data);
        } catch (\Exception $e) {
            return view('landing.obras_mapas', [
                'locations' => [],
                'situacoes' => [],
                'tipos_de_projetos' => [],
                'orgao' => [],
                'areaTematica' => [],
                'municipios' => [],
                'nome_obras' => [],
                'erro' => 'Erro ao carregar dados: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtém detalhes do diário oficial
     */
    private function getDiarioOficialDetails($processoSei)
    {
        $queryDiarioOficial = "
            SELECT edicao.data, edicao.numero, publicacao.pagina, publicacao.processo, publicacao.texto_publicacao, tipo.nome_tipo
            FROM publicacao
            INNER JOIN edicao ON edicao.id = publicacao.id_edicao
            INNER JOIN tipo ON tipo.id_tipo = publicacao.tipo
            WHERE publicacao.processo LIKE '%" . $processoSei . "'";

        try {
            return DB::select($queryDiarioOficial);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Exibe detalhes de uma obra específica
     */
    public function detalheMapas($id, $cj = null)
    {
        try {
            if ($cj === 'construindo_juntos') {
                $obra = DB::table('mapaobras')->where('id_projeto', $id)->first();
            } else {
                $obra = DB::table('mapaobras')->where('id', $id)->first();
            }

            if (!$obra) {
                abort(404);
            }

            $data = ['obra' => $obra];

            if (!empty($obra->processo_sei_da_contratacao)) {
                $data['diarioOficial'] = $this->getDiarioOficialDetails($obra->processo_sei_da_contratacao);
            }

            return view('landing.detalhe_mapas', $data);
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Exibe obras makers
     */
    public function obrasMakers()
    {
        try {
            $locations = DB::select('SELECT id, nome, lat, lng, imgmarcacao FROM markers');
            return view('landing.obras_makers', ['locations' => $locations]);
        } catch (\Exception $e) {
            return view('landing.obras_makers', ['locations' => [], 'erro' => 'Erro ao carregar dados']);
        }
    }

    /**
     * Exibe detalhes de makers
     */
    public function detalheMakers($id)
    {
        try {
            $obra = DB::table('markers')->where('id', $id)->first();

            if (!$obra) {
                abort(404);
            }

            return view('landing.detalhe_makers', ['obra' => $obra]);
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Obtém situações das obras para o mapa
     */
    public function getSituacaoObrasMaps()
    {
        try {
            $query = "SELECT DISTINCT situacao_obra FROM mapaobras WHERE situacao_obra IN ('P', 'A', 'C', 'I', 'D')";
            $consulta = DB::select($query);

            // Transformar os códigos em objetos com código e descrição
            $situacoes = [];
            foreach ($consulta as $item) {
                $situacoes[] = (object) [
                    'situacao_obra' => $item->situacao_obra,
                    'descricao' => handler_situation_work($item->situacao_obra)
                ];
            }

            return $situacoes;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Obtém tipos de projetos
     */
    public function getTiposDeProjetos()
    {
        try {
            $query = "SELECT DISTINCT tipos_do_projeto FROM mapaobras";
            $consulta = DB::select($query);
            return $consulta;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Obtém órgãos das obras
     */
    public function getOrgaosObras()
    {
        try {
            $query = "SELECT DISTINCT orgao, sigla FROM mapaobras";
            $consulta = DB::select($query);
            return $consulta;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Obtém nomes das obras
     */
    public function getNomeObras()
    {
        try {
            $query = "SELECT DISTINCT nome_projeto FROM mapaobras WHERE situacao_obra IN ('P', 'A', 'C')";
            $consulta = DB::select($query);
            return $consulta;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Obtém áreas temáticas
     */
    public function getAreaTematica()
    {
        try {
            $query = "SELECT DISTINCT area_tematica FROM mapaobras";
            $consulta = DB::select($query);
            return $consulta;
        } catch (\Exception $e) {
            return [];
        }
    }
}
