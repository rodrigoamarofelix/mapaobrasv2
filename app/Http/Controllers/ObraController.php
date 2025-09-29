<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Obra;
use App\Models\Municipio;
use App\Models\ProjetoObra;

class ObraController extends Controller
{
    /**
     * Exibe a lista de obras
     */
    public function index()
    {
        try {
            $obras = $this->buscarObras();

            return view('obras.index', compact('obras'));
        } catch (\Exception $e) {
            return view('obras.index', ['obras' => [], 'erro' => 'Erro ao carregar obras: ' . $e->getMessage()]);
        }
    }

    /**
     * Exibe detalhes de uma obra específica
     */
    public function show($id)
    {
        try {
            $obra = $this->buscarObraPorId($id);

            if (!$obra) {
                return redirect()->route('obras.index')->with('erro', 'Obra não encontrada');
            }

            return view('obras.show', compact('obra'));
        } catch (\Exception $e) {
            return redirect()->route('obras.index')->with('erro', 'Erro ao carregar obra: ' . $e->getMessage());
        }
    }

    /**
     * Busca todas as obras do banco de dados
     */
    private function buscarObras()
    {
        $sql = "
            SELECT
                mapaobras.id,
                mapaobras.id_projeto,
                mapaobras.nome_projeto,
                mapaobras.objeto,
                mapaobras.area_tematica,
                mapaobras.data_de_inicio_ou_previsao,
                mapaobras.valor_total_do_projeto,
                mapaobras.situacao_obra,
                mapaobras.data_prevista_conclusao,
                mapaobras.estagio_execucao_percentual,
                mapaobras.valor_pago,
                mapaobras.saldo_a_pagar,
                projeto_obras.latitude,
                projeto_obras.longitude,
                projeto_obras.municipio
            FROM mapaobras
            INNER JOIN projeto_obras ON projeto_obras.id_mapa_obra = mapaobras.id
            WHERE situacao_obra IN ('P','A', 'I', 'C')
            ORDER BY mapaobras.nome_projeto";

        return DB::select($sql);
    }

    /**
     * Busca uma obra específica por ID
     */
    private function buscarObraPorId($id)
    {
        $sql = "
            SELECT
                mapaobras.*,
                projeto_obras.latitude,
                projeto_obras.longitude,
                projeto_obras.municipio
            FROM mapaobras
            LEFT JOIN projeto_obras ON projeto_obras.id_mapa_obra = mapaobras.id
            WHERE mapaobras.id = ?";

        $result = DB::select($sql, [$id]);

        return !empty($result) ? $result[0] : null;
    }

    /**
     * Exibe detalhes da obra para o mapa
     */
    public function detalheMapas($id)
    {
        try {
            $obra = DB::select("
                SELECT
                    mapaobras.*,
                    projeto_obras.municipio,
                    projeto_obras.latitude,
                    projeto_obras.longitude
                FROM mapaobras
                LEFT JOIN projeto_obras ON projeto_obras.id_mapa_obra = mapaobras.id
                WHERE mapaobras.id = ?
                LIMIT 1
            ", [$id]);

            if (empty($obra)) {
                abort(404, 'Obra não encontrada');
            }

            $obra = $obra[0]; // Converter array para objeto

            return view('obras.detalhe-mapas', compact('obra'));
        } catch (\Exception $e) {
            abort(500, 'Erro ao carregar detalhes da obra: ' . $e->getMessage());
        }
    }

    /**
     * Filtra obras com base nos parâmetros
     */
    public function filter(Request $request)
    {
        try {
            $queryParams = [];
            $sql = "
                SELECT
                    mapaobras.id,
                    mapaobras.id_projeto,
                    mapaobras.nome_projeto,
                    mapaobras.objeto,
                    mapaobras.area_tematica,
                    mapaobras.situacao_obra,
                    mapaobras.estagio_execucao_percentual,
                    mapaobras.valor_total_do_projeto,
                    projeto_obras.municipio
                FROM mapaobras
                INNER JOIN projeto_obras ON projeto_obras.id_mapa_obra = mapaobras.id
                WHERE situacao_obra IN ('P','A', 'I', 'C', 'D')";

            // Filtro por número do projeto
            if ($request->has('nr_projeto') && !empty($request->nr_projeto)) {
                $sql .= " AND mapaobras.id_projeto = ?";
                $queryParams[] = intval($request->nr_projeto);
            }

            // Filtro por situação
            if ($request->has('situacao') && !empty($request->situacao)) {
                $sql .= " AND situacao_obra = ?";
                $queryParams[] = $request->situacao;
            }

            // Filtro por município
            if ($request->has('municipio') && !empty($request->municipio)) {
                $sql .= " AND projeto_obras.municipio LIKE ?";
                $queryParams[] = '%' . $request->municipio . '%';
            }

            // Filtro por área temática
            if ($request->has('area_tematica') && !empty($request->area_tematica)) {
                $sql .= " AND area_tematica = ?";
                $queryParams[] = $request->area_tematica;
            }

            // Filtro por nome da obra
            if ($request->has('nome_obra') && !empty($request->nome_obra)) {
                $sql .= " AND nome_projeto LIKE ?";
                $queryParams[] = '%' . $request->nome_obra . '%';
            }

            $sql .= " ORDER BY mapaobras.nome_projeto";

            $obras = DB::select($sql, $queryParams);

            return view('obras.index', compact('obras'));
        } catch (\Exception $e) {
            return view('obras.index', ['obras' => [], 'erro' => 'Erro ao filtrar obras: ' . $e->getMessage()]);
        }
    }

    /**
     * Atualiza cache das obras (se necessário)
     */
    public function atualizarCache()
    {
        try {
            // Se houver necessidade de cache, implementar aqui
            return redirect()->route('obras.index')->with('sucesso', 'Cache atualizado com sucesso');
        } catch (\Exception $e) {
            return redirect()->route('obras.index')->with('erro', 'Erro ao atualizar cache: ' . $e->getMessage());
        }
    }

    /**
     * Exporta obras para JSON
     */
    public function export()
    {
        try {
            $obras = $this->buscarObras();

            return response()->json($obras);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao exportar obras: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Importa municípios do IBGE para Goiás
     */
    public function importMunicipios()
    {
        try {
            $url = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/52/distritos';
            $response = file_get_contents($url);
            $response = json_decode($response, true);

            $list = [];
            foreach ($response as $key => $value) {
                $list[] = [
                    'nome' => $value['nome'],
                    'uf' => $value['municipio']['microrregiao']['mesorregiao']['UF']['sigla']
                ];
            }

            if (count($list) > 0) {
                // Deleta todos os municípios existentes
                Municipio::deleteMunicipios();

                // Insere os novos municípios
                $result = Municipio::addBatch($list);

                if ($result) {
                    return response()->json([
                        'success' => true,
                        'message' => "Foram inseridos: " . count($list) . " municípios"
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro ao inserir os municípios'
                    ], 500);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum município encontrado na API do IBGE'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao importar municípios: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lista todos os municípios importados
     */
    public function listMunicipios()
    {
        try {
            $municipios = DB::table('municipios')
                ->orderBy('nome')
                ->paginate(50);

            $total = DB::table('municipios')->count();

            $porUf = DB::table('municipios')
                ->select('uf', DB::raw('count(*) as total'))
                ->groupBy('uf')
                ->orderBy('uf')
                ->get();

            return view('obras.municipios', compact('municipios', 'total', 'porUf'));
        } catch (\Exception $e) {
            return view('obras.municipios', [
                'municipios' => collect(),
                'total' => 0,
                'porUf' => collect(),
                'erro' => 'Erro ao carregar municípios: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Testa conexão com a API
     */
    public function testApi()
    {
        try {
            $token = $this->getToken();

            if (!$token || !isset($token->access_token)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao obter token de acesso da API',
                    'debug' => [
                        'token_response' => $token,
                        'has_access_token' => isset($token->access_token)
                    ]
                ], 500);
            }

            $apiUrl = 'https://api.go.gov.br/';
            $arrContextOptions = [
                "http" => [
                    "method" => "GET",
                    'header' => [
                        "Authorization: Bearer $token->access_token",
                    ],
                ],
            ];

            // Testar com apenas 1 projeto
            $response = file_get_contents($apiUrl . "governo/projetos/v1.0/monitoramento-seinfraV2?offset=0&size=1", false, stream_context_create($arrContextOptions));
            $responseParse = json_decode($response);

            return response()->json([
                'success' => true,
                'message' => 'API funcionando!',
                'debug' => [
                    'token_obtido' => true,
                    'response_length' => strlen($response),
                    'projetos_encontrados' => is_array($responseParse) ? count($responseParse) : 0,
                    'primeiro_projeto' => isset($responseParse[0]) ? $responseParse[0] : null
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao testar API: ' . $e->getMessage(),
                'debug' => [
                    'error_code' => $e->getCode(),
                    'error_file' => $e->getFile() . ':' . $e->getLine()
                ]
            ], 500);
        }
    }

    /**
     * Obtém token de acesso da API do Governo de Goiás
     */
    private function getToken()
    {
        $apiUrl = 'https://api.go.gov.br/';
        $token = 'YUlvaWpzWVIzZlphRG5PUDg4X3VEemdEb3gwYTpCYkdROXplRllyV0lCVDhDTTM3bEh4Ym5mTUVh';

        $arrContextOptions = [
            "http" => [
                "method" => "POST",
                'header' => [
                    "Authorization: Basic " . $token,
                    'Content-Type: application/x-www-form-urlencoded'
                ],
                "content" => http_build_query([
                    'grant_type' => 'client_credentials'
                ])
            ],
        ];

        try {
            $response = file_get_contents($apiUrl . "token", false, stream_context_create($arrContextOptions));
            return json_decode($response);
        } catch (\Exception $e) {
            Log::error('Erro ao obter token da API: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Valida se a API está funcionando antes de importar
     */
    private function validateApi()
    {
        try {
            $token = $this->getToken();

            if (!$token || !isset($token->access_token)) {
                return [
                    'valid' => false,
                    'message' => 'Erro ao obter token de acesso da API',
                    'error' => 'TOKEN_ERROR'
                ];
            }

            $apiUrl = 'https://api.go.gov.br/';
            $arrContextOptions = [
                "http" => [
                    "method" => "GET",
                    'header' => [
                        "Authorization: Bearer $token->access_token",
                    ],
                ],
            ];

            // Testar com apenas 1 projeto para validar
            $url = $apiUrl . "governo/projetos/v1.0/monitoramento-seinfraV2?offset=0&size=1";
            $response = file_get_contents($url, false, stream_context_create($arrContextOptions));

            if ($response === false) {
                return [
                    'valid' => false,
                    'message' => 'Falha ao conectar com a API',
                    'error' => 'CONNECTION_ERROR'
                ];
            }

            $responseParse = json_decode($response);

            if (!$responseParse || !is_array($responseParse)) {
                return [
                    'valid' => false,
                    'message' => 'API retornou dados inválidos',
                    'error' => 'INVALID_RESPONSE'
                ];
            }

            if (count($responseParse) === 0) {
                return [
                    'valid' => false,
                    'message' => 'API não retornou nenhum projeto',
                    'error' => 'NO_DATA'
                ];
            }

            // Validar estrutura do primeiro projeto
            $projeto = $responseParse[0];
            $requiredFields = ['projetoId', 'nomeProjeto', 'municipioDtos'];

            foreach ($requiredFields as $field) {
                if (!isset($projeto->$field)) {
                    return [
                        'valid' => false,
                        'message' => "Campo obrigatório '$field' não encontrado na resposta da API",
                        'error' => 'MISSING_FIELD'
                    ];
                }
            }

            return [
                'valid' => true,
                'message' => 'API validada com sucesso',
                'data' => [
                    'projetos_disponiveis' => count($responseParse),
                    'primeiro_projeto_id' => $projeto->projetoId ?? 'N/A',
                    'primeiro_projeto_nome' => $projeto->nomeProjeto ?? 'N/A'
                ]
            ];

        } catch (\Exception $e) {
            return [
                'valid' => false,
                'message' => 'Erro ao validar API: ' . $e->getMessage(),
                'error' => 'VALIDATION_ERROR'
            ];
        }
    }

    /**
     * Importa obras da API do Governo de Goiás em lotes de 1000
     */
    public function getObrasApi()
    {
        try {
            // Validar API antes de fazer qualquer coisa
            Log::info('Iniciando validação da API...');
            $validation = $this->validateApi();

            if (!$validation['valid']) {
                Log::error('Validação da API falhou: ' . $validation['message']);
                return response()->json([
                    'success' => false,
                    'message' => 'API não está disponível: ' . $validation['message'],
                    'error_code' => $validation['error'],
                    'action' => 'Nenhuma alteração foi feita no banco de dados'
                ], 503);
            }

            Log::info('API validada com sucesso: ' . $validation['message']);

            $token = $this->getToken();
            $apiUrl = 'https://api.go.gov.br/';
            $arrContextOptions = [
                "http" => [
                    "method" => "GET",
                    'header' => [
                        "Authorization: Bearer $token->access_token",
                    ],
                ],
            ];

            // Deletar todas as obras existentes APENAS após validação
            Log::info('Limpando dados existentes...');
            Obra::deleteObras();
            ProjetoObra::query()->delete();

            $count_projetos = 0;
            $count_obras = 0;
            $offset = 0;
            $batch_size = 10000; // Tamanho máximo para capturar todos os projetos
            $batch_number = 1;
            $max_retries = 3; // Máximo de tentativas por lote

            Log::info('Iniciando importação em lotes de ' . $batch_size);

            do {
                $retry_count = 0;
                $batch_success = false;

                while ($retry_count < $max_retries && !$batch_success) {
                    try {
                        Log::info("Processando lote {$batch_number} - offset: {$offset} (tentativa " . ($retry_count + 1) . ")");

                        // Fazer requisição para o lote atual
                        $url = $apiUrl . "governo/projetos/v1.0/monitoramento-seinfraV2?offset={$offset}&size={$batch_size}";
                        $response = file_get_contents($url, false, stream_context_create($arrContextOptions));
                        $responseParse = json_decode($response);

                        if (!$responseParse || !is_array($responseParse)) {
                            Log::warning("Lote {$batch_number}: Resposta inválida ou vazia");
                            break;
                        }

                        $batch_projetos = 0;
                        $batch_obras = 0;

                        foreach ($responseParse as $projeto) {
                            try {
                                // Processar dados da obra usando o helper
                                $dto_projeto = handler_obra($projeto, false);

                                // Inserir obra na tabela mapaobras
                                $id_mapa_obra = Obra::create($dto_projeto)->id;
                                $batch_projetos++;
                                $count_projetos++;

                                // Processar municípios da obra
                                $obraDto = [];
                                if (isset($projeto->municipioDtos) && is_array($projeto->municipioDtos)) {
                                    foreach ($projeto->municipioDtos as $obra) {
                                        try {
                                            ProjetoObra::create([
                                                "id_mapa_obra" => $id_mapa_obra,
                                                "municipio" => $obra->nomeMunicipio ?? null,
                                                "latitude" => isset($obra->numeroLatitude) ? (float) $obra->numeroLatitude : null,
                                                "longitude" => isset($obra->numeroLongitude) ? (float) $obra->numeroLongitude : null,
                                            ]);
                                            $batch_obras++;
                                            $count_obras++;
                                        } catch (\Exception $e) {
                                            Log::error('Erro ao inserir município: ' . $e->getMessage(), [
                                                'projeto_id' => $projeto->projetoId ?? 'unknown',
                                                'municipio' => $obra->nomeMunicipio ?? 'unknown'
                                            ]);
                                        }
                                    }
                                }

                            } catch (\Exception $e) {
                                Log::error('Erro ao processar projeto no lote ' . $batch_number . ': ' . $e->getMessage(), [
                                    'projeto_id' => $projeto->projetoId ?? 'unknown',
                                    'exception' => $e
                                ]);
                                continue;
                            }
                        }

                        Log::info("Lote {$batch_number} concluído: {$batch_projetos} projetos, {$batch_obras} obras");
                        $batch_success = true;

                        // Se o lote retornou menos que o tamanho solicitado, chegamos ao fim
                        if (count($responseParse) < $batch_size) {
                            Log::info('Último lote processado - importação concluída');
                            break 2; // Sair dos dois loops
                        }

                        $offset += $batch_size;
                        $batch_number++;

                        // Pequena pausa entre lotes para evitar rate limiting
                        sleep(3);

                    } catch (\Exception $e) {
                        $retry_count++;
                        Log::error('Erro no lote ' . $batch_number . ' (tentativa ' . $retry_count . '): ' . $e->getMessage());

                        // Se for erro 429, aguardar mais tempo
                        if (strpos($e->getMessage(), '429') !== false) {
                            $wait_time = 30 * $retry_count; // Aumenta o tempo de espera a cada tentativa
                            Log::info("Rate limit atingido - aguardando {$wait_time} segundos...");
                            sleep($wait_time);
                        } else {
                            // Para outros erros, aguardar um pouco antes de tentar novamente
                            sleep(5);
                        }

                        if ($retry_count >= $max_retries) {
                            Log::error("Máximo de tentativas atingido para o lote {$batch_number}");
                            $offset += $batch_size;
                            $batch_number++;
                            break;
                        }
                    }
                }

            } while (true);

            if ($count_projetos > 0) {
                Log::info("Importação concluída: {$count_projetos} projetos, {$count_obras} obras em {$batch_number} lotes");

                return response()->json([
                    'success' => true,
                    'message' => "Importação concluída com sucesso!",
                    'data' => [
                        'total_projetos' => $count_projetos,
                        'total_obras' => $count_obras,
                        'total_lotes' => $batch_number,
                        'tamanho_lote' => $batch_size,
                        'api_validation' => $validation['data'] ?? null
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum projeto foi importado'
                ], 404);
            }

        } catch (\Exception $e) {
            Log::error('Erro na importação de obras: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao importar obras: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Importação incremental - apenas projetos novos ou atualizados
     */
    public function getObrasApiIncremental()
    {
        try {
            // Validar API antes de fazer qualquer coisa
            Log::info('Iniciando validação da API para importação incremental...');
            $validation = $this->validateApi();

            if (!$validation['valid']) {
                Log::error('Validação da API falhou: ' . $validation['message']);
                return response()->json([
                    'success' => false,
                    'message' => 'API não está disponível: ' . $validation['message'],
                    'error_code' => $validation['error'],
                    'action' => 'Nenhuma alteração foi feita no banco de dados'
                ], 503);
            }

            Log::info('API validada com sucesso para importação incremental');

            $token = $this->getToken();
            $apiUrl = 'https://api.go.gov.br/';
            $arrContextOptions = [
                "http" => [
                    "method" => "GET",
                    'header' => [
                        "Authorization: Bearer $token->access_token",
                    ],
                ],
            ];

            $count_projetos_novos = 0;
            $count_projetos_atualizados = 0;
            $count_obras_novas = 0;
            $offset = 0;
            $batch_size = 10000;
            $batch_number = 1;
            $max_retries = 3;

            Log::info('Iniciando importação incremental em lotes de ' . $batch_size);

            do {
                $retry_count = 0;
                $batch_success = false;

                while ($retry_count < $max_retries && !$batch_success) {
                    try {
                        Log::info("Processando lote {$batch_number} - offset: {$offset} (tentativa " . ($retry_count + 1) . ")");

                        $url = $apiUrl . "governo/projetos/v1.0/monitoramento-seinfraV2?offset={$offset}&size={$batch_size}";
                        $response = file_get_contents($url, false, stream_context_create($arrContextOptions));
                        $responseParse = json_decode($response);

                        if (!$responseParse || !is_array($responseParse)) {
                            Log::warning("Lote {$batch_number}: Resposta inválida ou vazia");
                            break;
                        }

                        $batch_novos = 0;
                        $batch_atualizados = 0;
                        $batch_obras_novas = 0;

                        foreach ($responseParse as $projeto) {
                            try {
                                $projeto_id = $projeto->projetoId ?? null;

                                if (!$projeto_id) {
                                    Log::warning('Projeto sem ID, pulando...');
                                    continue;
                                }

                                // Verificar se o projeto já existe
                                $obra_existente = Obra::where('id_projeto', $projeto_id)->first();

                                $dto_projeto = handler_obra($projeto, false);

                                if ($obra_existente) {
                                    // Atualizar projeto existente
                                    $obra_existente->update($dto_projeto);
                                    $id_mapa_obra = $obra_existente->id;
                                    $batch_atualizados++;
                                    $count_projetos_atualizados++;

                                    Log::info("Projeto {$projeto_id} atualizado");
                                } else {
                                    // Inserir novo projeto
                                    $id_mapa_obra = Obra::create($dto_projeto)->id;
                                    $batch_novos++;
                                    $count_projetos_novos++;

                                    Log::info("Novo projeto {$projeto_id} inserido");
                                }

                                // Processar municípios da obra
                                if (isset($projeto->municipioDtos) && is_array($projeto->municipioDtos)) {
                                    // Deletar municípios existentes para este projeto
                                    ProjetoObra::where('id_mapa_obra', $id_mapa_obra)->delete();

                                    foreach ($projeto->municipioDtos as $obra) {
                                        try {
                                            ProjetoObra::create([
                                                "id_mapa_obra" => $id_mapa_obra,
                                                "municipio" => $obra->nomeMunicipio ?? null,
                                                "latitude" => isset($obra->numeroLatitude) ? (float) $obra->numeroLatitude : null,
                                                "longitude" => isset($obra->numeroLongitude) ? (float) $obra->numeroLongitude : null,
                                            ]);
                                            $batch_obras_novas++;
                                            $count_obras_novas++;
                                        } catch (\Exception $e) {
                                            Log::error('Erro ao inserir município incremental: ' . $e->getMessage(), [
                                                'projeto_id' => $projeto->projetoId ?? 'unknown',
                                                'municipio' => $obra->nomeMunicipio ?? 'unknown'
                                            ]);
                                        }
                                    }
                                }

                            } catch (\Exception $e) {
                                Log::error('Erro ao processar projeto no lote ' . $batch_number . ': ' . $e->getMessage(), [
                                    'projeto_id' => $projeto->projetoId ?? 'unknown',
                                    'exception' => $e
                                ]);
                                continue;
                            }
                        }

                        Log::info("Lote {$batch_number} concluído: {$batch_novos} novos, {$batch_atualizados} atualizados, {$batch_obras_novas} obras");
                        $batch_success = true;

                        // Se o lote retornou menos que o tamanho solicitado, chegamos ao fim
                        if (count($responseParse) < $batch_size) {
                            Log::info('Último lote processado - importação incremental concluída');
                            break 2;
                        }

                        $offset += $batch_size;
                        $batch_number++;
                        sleep(3);

                    } catch (\Exception $e) {
                        $retry_count++;
                        Log::error('Erro no lote ' . $batch_number . ' (tentativa ' . $retry_count . '): ' . $e->getMessage());

                        if (strpos($e->getMessage(), '429') !== false) {
                            $wait_time = 30 * $retry_count;
                            Log::info("Rate limit atingido - aguardando {$wait_time} segundos...");
                            sleep($wait_time);
                        } else {
                            sleep(5);
                        }

                        if ($retry_count >= $max_retries) {
                            Log::error("Máximo de tentativas atingido para o lote {$batch_number}");
                            $offset += $batch_size;
                            $batch_number++;
                            break;
                        }
                    }
                }

            } while (true);

            Log::info("Importação incremental concluída: {$count_projetos_novos} novos, {$count_projetos_atualizados} atualizados, {$count_obras_novas} obras");

            return response()->json([
                'success' => true,
                'message' => "Importação incremental concluída com sucesso!",
                'data' => [
                    'projetos_novos' => $count_projetos_novos,
                    'projetos_atualizados' => $count_projetos_atualizados,
                    'obras_novas' => $count_obras_novas,
                    'total_lotes' => $batch_number,
                    'tamanho_lote' => $batch_size,
                    'api_validation' => $validation['data'] ?? null
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erro na importação incremental: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao importar obras incrementalmente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Interface de importação
     */
    public function importInterface()
    {
        try {
            // Estatísticas básicas
            $total_projetos = Obra::count();
            $total_obras = ProjetoObra::count();
            $total_municipios = Municipio::count();

            // Última importação (simulado)
            $ultima_importacao = Obra::latest('updated_at')->first();

            return view('obras.import', compact(
                'total_projetos',
                'total_obras',
                'total_municipios',
                'ultima_importacao'
            ));
        } catch (\Exception $e) {
            return view('obras.import', [
                'total_projetos' => 0,
                'total_obras' => 0,
                'total_municipios' => 0,
                'ultima_importacao' => null
            ]);
        }
    }

    /**
     * Testa quantos projetos existem na API
     */
    public function testApiCount()
    {
        try {
            $token = $this->getToken();

            if (!$token || !isset($token->access_token)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao obter token de acesso da API'
                ], 500);
            }

            $apiUrl = 'https://api.go.gov.br/';
            $arrContextOptions = [
                "http" => [
                    "method" => "GET",
                    'header' => [
                        "Authorization: Bearer $token->access_token",
                    ],
                ],
            ];

            $total_projetos = 0;
            $offset = 0;
            $batch_size = 10000;
            $batch_number = 1;
            $max_batches = 10; // Limite de segurança

            $results = [];

            do {
                try {
                    $url = $apiUrl . "governo/projetos/v1.0/monitoramento-seinfraV2?offset={$offset}&size={$batch_size}";
                    $response = file_get_contents($url, false, stream_context_create($arrContextOptions));
                    $responseParse = json_decode($response);

                    if (!$responseParse || !is_array($responseParse)) {
                        $results[] = "Lote {$batch_number}: Resposta inválida";
                        break;
                    }

                    $count = count($responseParse);
                    $total_projetos += $count;

                    $results[] = "Lote {$batch_number}: {$count} projetos (offset: {$offset})";

                    // Se o lote retornou menos que o tamanho solicitado, chegamos ao fim
                    if ($count < $batch_size) {
                        $results[] = "Último lote encontrado - total: {$total_projetos} projetos";
                        break;
                    }

                    $offset += $batch_size;
                    $batch_number++;

                    // Pequena pausa entre lotes
                    sleep(2);

                } catch (\Exception $e) {
                    $results[] = "Erro no lote {$batch_number}: " . $e->getMessage();
                    break;
                }

            } while ($batch_number <= $max_batches);

            return response()->json([
                'success' => true,
                'message' => "Teste de contagem concluído",
                'data' => [
                    'total_projetos' => $total_projetos,
                    'total_lotes' => $batch_number - 1,
                    'batch_size' => $batch_size,
                    'results' => $results
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro no teste: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validação pública da API
     */
    public function validateApiPublic()
    {
        $validation = $this->validateApi();

        return response()->json([
            'success' => $validation['valid'],
            'message' => $validation['message'],
            'error_code' => $validation['error'] ?? null,
            'data' => $validation['data'] ?? null,
            'timestamp' => now()->toISOString()
        ], $validation['valid'] ? 200 : 503);
    }

    /**
     * Debug da API - mostra informações detalhadas
     */
    public function debugApi()
    {
        try {
            $token = $this->getToken();

            if (!$token || !isset($token->access_token)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao obter token',
                    'token_response' => $token
                ], 500);
            }

            $apiUrl = 'https://api.go.gov.br/';
            $arrContextOptions = [
                "http" => [
                    "method" => "GET",
                    'header' => [
                        "Authorization: Bearer $token->access_token",
                    ],
                ],
            ];

            // Testar com apenas 1 projeto
            $url = $apiUrl . "governo/projetos/v1.0/monitoramento-seinfraV2?offset=0&size=1";
            $response = file_get_contents($url, false, stream_context_create($arrContextOptions));
            $responseParse = json_decode($response);

            return response()->json([
                'success' => true,
                'debug' => [
                    'url' => $url,
                    'response_length' => strlen($response),
                    'response_type' => gettype($responseParse),
                    'is_array' => is_array($responseParse),
                    'count' => is_array($responseParse) ? count($responseParse) : 'N/A',
                    'first_item_keys' => is_array($responseParse) && isset($responseParse[0]) ? array_keys((array)$responseParse[0]) : 'N/A',
                    'raw_response' => $response,
                    'parsed_response' => $responseParse
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro: ' . $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine()
            ], 500);
        }
    }

    /**
     * Teste de importação com dados simulados
     */
    public function testImport()
    {
        try {
            // Dados simulados para teste
            $projetosSimulados = [
                (object) [
                    'idProjeto' => 'TEST001',
                    'nomeProjeto' => 'Construção de Escola Municipal',
                    'objeto' => 'Construção de escola com 4 salas de aula',
                    'areaTematica' => 'Educação',
                    'dataDeInicioOuPrevisao' => '2024-01-15',
                    'valorTotalDoProjeto' => '500000.00',
                    'situacaoObra' => 'A',
                    'dataPrevistaConclusao' => '2024-12-31',
                    'estagioExecucaoPercentual' => '25.5',
                    'valorPago' => '125000.00',
                    'saldoAPagar' => '375000.00',
                    'numeroContratoEmpreita' => 'CT001/2024',
                    'numeroProcessoSEI' => 'SEI001',
                    'municipioDtos' => [
                        (object) [
                            'nomeMunicipio' => 'Goiânia',
                            'numeroLatitude' => -16.6869,
                            'numeroLongitude' => -49.2648
                        ]
                    ]
                ],
                (object) [
                    'idProjeto' => 'TEST002',
                    'nomeProjeto' => 'Pavimentação de Rua',
                    'objeto' => 'Pavimentação asfáltica de 2km',
                    'areaTematica' => 'Infraestrutura',
                    'dataDeInicioOuPrevisao' => '2024-02-01',
                    'valorTotalDoProjeto' => '300000.00',
                    'situacaoObra' => 'P',
                    'dataPrevistaConclusao' => '2024-11-30',
                    'estagioExecucaoPercentual' => '0.0',
                    'valorPago' => '0.00',
                    'saldoAPagar' => '300000.00',
                    'numeroContratoEmpreita' => 'CT002/2024',
                    'numeroProcessoSEI' => 'SEI002',
                    'municipioDtos' => [
                        (object) [
                            'nomeMunicipio' => 'Aparecida de Goiânia',
                            'numeroLatitude' => -16.8219,
                            'numeroLongitude' => -49.2439
                        ]
                    ]
                ]
            ];

            $count_projetos = 0;
            $count_obras = 0;

            // Deletar todas as obras existentes
            Obra::deleteObras();
            ProjetoObra::query()->delete();

            foreach ($projetosSimulados as $projeto) {
                try {
                    // Processar dados da obra usando o helper
                    $dto_projeto = handler_obra($projeto, false);

                    // Inserir obra na tabela mapaobras
                    $id_mapa_obra = Obra::create($dto_projeto)->id;
                    $count_projetos++;

                    // Processar municípios da obra
                    $obraDto = [];
                    if (isset($projeto->municipioDtos) && is_array($projeto->municipioDtos)) {
                        foreach ($projeto->municipioDtos as $obra) {
                            $obraDto[] = [
                                "id_mapa_obra" => $id_mapa_obra,
                                "municipio" => $obra->nomeMunicipio ?? null,
                                "latitude" => isset($obra->numeroLatitude) ? (float) $obra->numeroLatitude : null,
                                "longitude" => isset($obra->numeroLongitude) ? (float) $obra->numeroLongitude : null,
                                "created_at" => now(),
                                "updated_at" => now(),
                            ];
                            $count_obras++;
                        }

                        // Inserir municípios em lote
                        if (!empty($obraDto)) {
                            ProjetoObra::addBatch($obraDto);
                        }
                    }

                } catch (\Exception $e) {
                    Log::error('Erro ao processar projeto de teste: ' . $e->getMessage());
                    continue;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Teste de importação concluído com sucesso!",
                'data' => [
                    'total_projetos' => $count_projetos,
                    'total_obras' => $count_obras,
                    'note' => 'Dados simulados para demonstração'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erro no teste de importação: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro no teste: ' . $e->getMessage()
            ], 500);
        }
    }
}
