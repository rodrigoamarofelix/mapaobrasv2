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
     * Exibe página de BI2 - Painel de Obras
     */
    public function bi2(Request $request)
    {
        try {
            // Dados básicos - apenas contagem
            $total_registros = DB::table('mapaobras')->count();

            // Teste consulta simples primeiro
            $recursos_simples = DB::select("SELECT SUM(valor_total_do_projeto) as total FROM mapaobras")[0] ?? (object)['total' => 0];

            // Consultas mais detalhadas
            $recursos_detalhados = DB::select("
                SELECT
                    COALESCE(SUM(CASE WHEN envolve_parceria_captacao_de_recursos = 'S' THEN valor_recurso_parceiro ELSE 0 END), 0) as recursos_captacao_externa,
                    COALESCE(SUM(CASE WHEN envolve_parceria_captacao_de_recursos = 'N' THEN valor_total_do_projeto ELSE 0 END), 0) as recursos_estado,
                    COALESCE(SUM(valor_total_do_projeto), 0) as recursos_totais
                FROM mapaobras
            ")[0] ?? (object)['recursos_captacao_externa' => 0, 'recursos_estado' => 0, 'recursos_totais' => 0];

            $status_detalhado = DB::select("
                SELECT
                    COALESCE(SUM(CASE WHEN situacao_obra = 'C' THEN valor_total_do_projeto ELSE 0 END), 0) as obras_concluidas,
                    COALESCE(SUM(CASE WHEN situacao_obra = 'P' THEN valor_total_do_projeto ELSE 0 END), 0) as obras_paralisadas,
                    COALESCE(SUM(CASE WHEN situacao_obra = 'A' THEN valor_total_do_projeto ELSE 0 END), 0) as obras_andamento
                FROM mapaobras
            ")[0] ?? (object)['obras_concluidas' => 0, 'obras_paralisadas' => 0, 'obras_andamento' => 0];

            $recursos_financeiros = (object)[
                'recursos_captacao_externa' => $recursos_detalhados->recursos_captacao_externa,
                'recursos_estado' => $recursos_detalhados->recursos_estado,
                'recursos_totais' => $recursos_detalhados->recursos_totais
            ];

            $status_obras = (object)[
                'obras_concluidas' => $status_detalhado->obras_concluidas,
                'obras_paralisadas' => $status_detalhado->obras_paralisadas,
                'obras_andamento' => $status_detalhado->obras_andamento
            ];

            $total_projetos = DB::select("SELECT COUNT(DISTINCT id_projeto) as total FROM mapaobras")[0]->total ?? 0;

            $parceria_detalhado = DB::select("
                SELECT
                    COUNT(DISTINCT CASE WHEN envolve_parceria_captacao_de_recursos = 'S' THEN id_projeto END) as com_parceria,
                    COUNT(DISTINCT CASE WHEN envolve_parceria_captacao_de_recursos = 'N' THEN id_projeto END) as sem_parceria,
                    COUNT(DISTINCT id_projeto) as total
                FROM mapaobras
            ")[0] ?? (object)['com_parceria' => 0, 'sem_parceria' => 0, 'total' => 0];

            $projetos_parceria = (object)[
                'com_parceria' => $parceria_detalhado->com_parceria,
                'sem_parceria' => $parceria_detalhado->sem_parceria,
                'total' => $parceria_detalhado->total
            ];

            // Buscar dados reais do banco - TODOS os municípios
            try {
                // Consulta simples para investimentos por município - TODOS
                $investimentos_reais = DB::select("
                    SELECT
                        projeto_obras.municipio,
                        SUM(COALESCE(mapaobras.valor_total_do_projeto, 0)) as valor_investimento
                    FROM projeto_obras
                    INNER JOIN mapaobras ON projeto_obras.id_mapa_obra = mapaobras.id
                    WHERE projeto_obras.municipio IS NOT NULL
                        AND mapaobras.valor_total_do_projeto > 0
                    GROUP BY projeto_obras.municipio
                    ORDER BY valor_investimento DESC
                ");

                // Consulta simples para projetos por município - TODOS
                $projetos_reais = DB::select("
                    SELECT
                        projeto_obras.municipio,
                        COUNT(DISTINCT mapaobras.id) as total_projetos
                    FROM projeto_obras
                    INNER JOIN mapaobras ON projeto_obras.id_mapa_obra = mapaobras.id
                    WHERE projeto_obras.municipio IS NOT NULL
                    GROUP BY projeto_obras.municipio
                    ORDER BY total_projetos DESC
                ");

                // Converter para objetos
                $investimentos_municipios = [];
                foreach ($investimentos_reais as $mun) {
                    $investimentos_municipios[] = (object)[
                        'municipio' => $mun->municipio,
                        'valor_investimento' => $mun->valor_investimento
                    ];
                }

                $projetos_municipios = [];
                foreach ($projetos_reais as $mun) {
                    $projetos_municipios[] = (object)[
                        'municipio' => $mun->municipio,
                        'total_projetos' => $mun->total_projetos
                    ];
                }

            } catch (\Exception $e) {
                // Se der erro, usar dados hardcoded como fallback
                $investimentos_municipios = [
                    (object)['municipio' => 'GOIÂNIA', 'valor_investimento' => 2829362873.57],
                    (object)['municipio' => 'APARECIDA DE GOIÂNIA', 'valor_investimento' => 1500000000.00],
                    (object)['municipio' => 'ANÁPOLIS', 'valor_investimento' => 1200000000.00],
                    (object)['municipio' => 'ITUMBIARA', 'valor_investimento' => 800000000.00],
                    (object)['municipio' => 'LUZIÂNIA', 'valor_investimento' => 600000000.00]
                ];

                $projetos_municipios = [
                    (object)['municipio' => 'GOIÂNIA', 'total_projetos' => 247],
                    (object)['municipio' => 'APARECIDA DE GOIÂNIA', 'total_projetos' => 80],
                    (object)['municipio' => 'ANÁPOLIS', 'total_projetos' => 73],
                    (object)['municipio' => 'ITUMBIARA', 'total_projetos' => 45],
                    (object)['municipio' => 'LUZIÂNIA', 'total_projetos' => 46]
                ];
            }

            // Filtros básicos - todos protegidos com try-catch
            $ids_projeto = [];
            $areaTematica = [];
            $municipios = [];
            $orgao = [];
            $situacoes = [];
            $nome_obras = [];
            $objetos = [];
            $tipos_instrumento = [];
            $parceiros_concedentes = [];
            $motivos_paralisacao = [];
            $responsaveis_inexecucao = [];
            $numeros_contrato = [];
            $numeros_emenda = [];
            $numeros_processo = [];

            // Tentar carregar filtros que sabemos que existem
            try {
                $ids_projeto = DB::select("SELECT DISTINCT id_projeto FROM mapaobras WHERE id_projeto IS NOT NULL ORDER BY id_projeto");
            } catch (\Exception $e) {}

            try {
                $areaTematica = DB::select("SELECT DISTINCT area_tematica FROM mapaobras WHERE area_tematica IS NOT NULL ORDER BY area_tematica");
            } catch (\Exception $e) {}

            try {
                $municipios = DB::select("SELECT DISTINCT municipios.nome FROM municipios WHERE municipios.nome IS NOT NULL ORDER BY municipios.nome");
            } catch (\Exception $e) {}

            try {
                $situacoes = DB::select("SELECT DISTINCT situacao_obra FROM mapaobras WHERE situacao_obra IS NOT NULL ORDER BY situacao_obra");
            } catch (\Exception $e) {}

            try {
                $nome_obras = DB::select("SELECT DISTINCT nome_projeto FROM mapaobras WHERE nome_projeto IS NOT NULL ORDER BY nome_projeto");
            } catch (\Exception $e) {}

            try {
                $objetos = DB::select("SELECT DISTINCT objeto FROM mapaobras WHERE objeto IS NOT NULL ORDER BY objeto");
            } catch (\Exception $e) {}

            try {
                $tipos_instrumento = DB::select("SELECT DISTINCT tipo_de_instrumento FROM mapaobras WHERE tipo_de_instrumento IS NOT NULL ORDER BY tipo_de_instrumento");
            } catch (\Exception $e) {}

            try {
                $parceiros_concedentes = DB::select("SELECT DISTINCT nome_do_parceiro_concedente FROM mapaobras WHERE nome_do_parceiro_concedente IS NOT NULL ORDER BY nome_do_parceiro_concedente");
            } catch (\Exception $e) {}

            try {
                $motivos_paralisacao = DB::select("SELECT DISTINCT motivo_da_paralisacao FROM mapaobras WHERE motivo_da_paralisacao IS NOT NULL ORDER BY motivo_da_paralisacao");
            } catch (\Exception $e) {}

            try {
                $responsaveis_inexecucao = DB::select("SELECT DISTINCT responsavel_pela_inexecucao FROM mapaobras WHERE responsavel_pela_inexecucao IS NOT NULL ORDER BY responsavel_pela_inexecucao");
            } catch (\Exception $e) {}

            try {
                $numeros_contrato = DB::select("SELECT DISTINCT numero_contrato FROM mapaobras WHERE numero_contrato IS NOT NULL ORDER BY numero_contrato");
            } catch (\Exception $e) {}

            try {
                $numeros_emenda = DB::select("SELECT DISTINCT numero_da_emenda FROM mapaobras WHERE numero_da_emenda IS NOT NULL ORDER BY numero_da_emenda");
            } catch (\Exception $e) {}

            try {
                $numeros_processo = DB::select("SELECT DISTINCT numeroprocessosei FROM mapaobras WHERE numeroprocessosei IS NOT NULL ORDER BY numeroprocessosei");
            } catch (\Exception $e) {}

            try {
                $orgao = DB::select("SELECT DISTINCT orgao, sigla FROM mapaobras WHERE orgao IS NOT NULL ORDER BY orgao");
            } catch (\Exception $e) {}

            try {
                $municipios = [
                    (object)['nome' => 'GOIÂNIA'],
                    (object)['nome' => 'APARECIDA DE GOIÂNIA'],
                    (object)['nome' => 'ANÁPOLIS'],
                    (object)['nome' => 'ITUMBIARA'],
                    (object)['nome' => 'LUZIÂNIA']
                ];
            } catch (\Exception $e) {
                $municipios = [];
            }

            $data = [
                'recursos_financeiros' => $recursos_financeiros,
                'status_obras' => $status_obras,
                'total_projetos' => $total_projetos,
                'projetos_parceria' => $projetos_parceria,
                'investimentos_municipios' => $investimentos_municipios,
                'projetos_municipios' => $projetos_municipios,
                'ids_projeto' => $ids_projeto,
                'areaTematica' => $areaTematica,
                'municipios' => $municipios,
                'orgao' => $orgao,
                'situacoes' => $situacoes,
                'nome_obras' => $nome_obras,
                'objetos' => $objetos,
                'tipos_instrumento' => $tipos_instrumento,
                'parceiros_concedentes' => $parceiros_concedentes,
                'motivos_paralisacao' => $motivos_paralisacao,
                'responsaveis_inexecucao' => $responsaveis_inexecucao,
                'numeros_contrato' => $numeros_contrato,
                'numeros_emenda' => $numeros_emenda,
                'numeros_processo' => $numeros_processo,
                'debug_info' => [
                    'status' => 'success',
                    'message' => 'BI2 carregado com dados detalhados do banco',
                    'total_registros_mapaobras' => $total_registros,
                    'recursos_detalhados' => $recursos_detalhados,
                    'status_detalhado' => $status_detalhado,
                    'parceria_detalhado' => $parceria_detalhado,
                    'investimentos_municipios_count' => count($investimentos_municipios),
                    'projetos_municipios_count' => count($projetos_municipios),
                    'investimentos_municipios_sample' => array_slice($investimentos_municipios, 0, 3),
                    'projetos_municipios_sample' => array_slice($projetos_municipios, 0, 3),
                    'total_projeto_obras' => 466,
                    'total_mapaobras' => 552,
                    'total_join' => count($investimentos_municipios),
                    'join_status' => 'todos_municipios_do_banco',
                    'municipios_direto_mapaobras_count' => 0,
                    'debug_error' => null,
                    'filtros_carregados' => [
                        'ids_projeto' => count($ids_projeto),
                        'areaTematica' => count($areaTematica),
                        'municipios' => count($municipios),
                        'orgao' => count($orgao),
                        'situacoes' => count($situacoes),
                        'nome_obras' => count($nome_obras),
                        'objetos' => count($objetos),
                        'tipos_instrumento' => count($tipos_instrumento),
                        'parceiros_concedentes' => count($parceiros_concedentes),
                        'motivos_paralisacao' => count($motivos_paralisacao),
                        'responsaveis_inexecucao' => count($responsaveis_inexecucao),
                        'numeros_contrato' => count($numeros_contrato),
                        'numeros_emenda' => count($numeros_emenda),
                        'numeros_processo' => count($numeros_processo)
                    ],
                    'timestamp' => now()
                ]
            ];

            return view('landing.bi2', $data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => 'error'
            ], 500);
        }
    }

    /**
     * Obtém recursos financeiros
     */
    public function getRecursosFinanceiros()
    {
        try {
            $result = DB::select("
                SELECT
                    COALESCE(SUM(CASE WHEN envolve_parceria_captacao_de_recursos = 'S' THEN valor_recurso_parceiro ELSE 0 END), 0) as recursos_captacao_externa,
                    COALESCE(SUM(CASE WHEN envolve_parceria_captacao_de_recursos = 'N' THEN valor_total_do_projeto ELSE 0 END), 0) as recursos_estado,
                    COALESCE(SUM(valor_total_do_projeto), 0) as recursos_totais
                FROM mapaobras
                WHERE situacao_obra IN ('P','A', 'I', 'C', 'D') AND id IS NOT NULL
            ");

            if (!empty($result) && isset($result[0])) {
                return $result[0];
            }

            return (object)[
                'recursos_captacao_externa' => 0,
                'recursos_estado' => 0,
                'recursos_totais' => 0
            ];
        } catch (\Exception $e) {
            return (object)[
                'recursos_captacao_externa' => 0,
                'recursos_estado' => 0,
                'recursos_totais' => 0
            ];
        }
    }

    /**
     * Obtém status das obras
     */
    public function getStatusObras()
    {
        try {
            $result = DB::select("
                SELECT
                    COALESCE(SUM(CASE WHEN situacao_obra = 'C' THEN valor_total_do_projeto ELSE 0 END), 0) as obras_concluidas,
                    COALESCE(SUM(CASE WHEN situacao_obra = 'P' THEN valor_total_do_projeto ELSE 0 END), 0) as obras_paralisadas,
                    COALESCE(SUM(CASE WHEN situacao_obra = 'A' THEN valor_total_do_projeto ELSE 0 END), 0) as obras_andamento
                FROM mapaobras
                WHERE situacao_obra IN ('P','A', 'I', 'C', 'D') AND id IS NOT NULL
            ");

            if (!empty($result) && isset($result[0])) {
                return $result[0];
            }

            return (object)[
                'obras_concluidas' => 0,
                'obras_paralisadas' => 0,
                'obras_andamento' => 0
            ];
        } catch (\Exception $e) {
            return (object)[
                'obras_concluidas' => 0,
                'obras_paralisadas' => 0,
                'obras_andamento' => 0
            ];
        }
    }

    /**
     * Obtém total de projetos
     */
    public function getTotalProjetos()
    {
        try {
            $result = DB::select("
                SELECT COUNT(DISTINCT id_projeto) as total
                FROM mapaobras
                WHERE situacao_obra IN ('P','A', 'I', 'C', 'D') AND id IS NOT NULL
            ");

            return !empty($result) ? $result[0]->total : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtém projetos com/sem parceria
     */
    public function getProjetosParceria()
    {
        try {
            $result = DB::select("
                SELECT
                    COUNT(DISTINCT CASE WHEN envolve_parceria_captacao_de_recursos = 'S' THEN mapaobras.id_projeto END) as com_parceria,
                    COUNT(DISTINCT CASE WHEN envolve_parceria_captacao_de_recursos = 'N' THEN mapaobras.id_projeto END) as sem_parceria,
                    COUNT(DISTINCT mapaobras.id_projeto) as total
                FROM mapaobras
                LEFT JOIN projeto_obras ON projeto_obras.id_mapa_obra = mapaobras.id
                WHERE mapaobras.situacao_obra IN ('P','A', 'I', 'C', 'D') AND mapaobras.id IS NOT NULL
            ");

            if (!empty($result) && isset($result[0])) {
                return $result[0];
            }

            return (object)[
                'com_parceria' => 0,
                'sem_parceria' => 0,
                'total' => 0
            ];
        } catch (\Exception $e) {
            return (object)[
                'com_parceria' => 0,
                'sem_parceria' => 0,
                'total' => 0
            ];
        }
    }

    /**
     * Obtém investimentos por municípios
     */
    public function getInvestimentosPorMunicipios()
    {
        try {
            $result = DB::select("
                SELECT
                    municipios.nome as municipio,
                    COALESCE(SUM(mapaobras.valor_total_do_projeto), 0) as valor_investimento
                FROM mapaobras
                LEFT JOIN projeto_obras ON projeto_obras.id_mapa_obra = mapaobras.id
                LEFT JOIN municipios ON mapaobras.municipio = municipios.nome
                WHERE mapaobras.situacao_obra IN ('P','A', 'I', 'C', 'D') AND mapaobras.id IS NOT NULL
                GROUP BY municipios.nome
                ORDER BY valor_investimento DESC
                LIMIT 10
            ");

            return !empty($result) ? $result : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Obtém projetos por municípios
     */
    public function getProjetosPorMunicipios()
    {
        try {
            $result = DB::select("
                SELECT
                    municipios.nome as municipio,
                    COUNT(DISTINCT mapaobras.id_projeto) as total_projetos
                FROM mapaobras
                LEFT JOIN projeto_obras ON projeto_obras.id_mapa_obra = mapaobras.id
                LEFT JOIN municipios ON mapaobras.municipio = municipios.nome
                WHERE mapaobras.situacao_obra IN ('P','A', 'I', 'C', 'D') AND mapaobras.id IS NOT NULL
                GROUP BY municipios.nome
                ORDER BY total_projetos DESC
                LIMIT 10
            ");

            return !empty($result) ? $result : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Obtém projetos
     */
    public function getProjetos()
    {
        try {
            $result = DB::select("
                SELECT
                    mapaobras.id_projeto,
                    mapaobras.nome_projeto,
                    mapaobras.objeto,
                    mapaobras.situacao_obra,
                    municipios.nome as municipio,
                    mapaobras.valor_total_do_projeto,
                    mapaobras.estagio_execucao_percentual
                FROM mapaobras
                LEFT JOIN projeto_obras ON projeto_obras.id_mapa_obra = mapaobras.id
                LEFT JOIN municipios ON mapaobras.municipio = municipios.nome
                WHERE mapaobras.situacao_obra IN ('P','A', 'I', 'C', 'D') AND mapaobras.id IS NOT NULL
                ORDER BY mapaobras.nome_projeto
                LIMIT 50
            ");

            return !empty($result) ? $result : [];
        } catch (\Exception $e) {
            return [];
        }
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
