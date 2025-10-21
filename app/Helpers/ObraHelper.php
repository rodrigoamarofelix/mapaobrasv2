<?php

if (!function_exists('handler_obra')) {
    /**
     * Processa dados de obra da API do Governo de Goiás
     *
     * @param object $projeto Dados do projeto da API
     * @param bool $debug Se deve retornar dados de debug
     * @return array Dados processados para inserção no banco
     */
    function handler_obra($projeto, $debug = false)
    {
        try {
            // Processar dados básicos do projeto
            $dados = [
                'id_projeto' => $projeto->projetoId ?? null,
                'nome_projeto' => $projeto->nomeProjeto ?? null,
                'objeto' => $projeto->descricaoObjeto ?? null,
                'area_tematica' => $projeto->descricaoAreaTematica ?? null,
                'data_de_inicio_ou_previsao' => isset($projeto->dataInicio) ?
                    \Carbon\Carbon::createFromFormat('d/m/Y', $projeto->dataInicio)->format('Y-m-d') : null,
                'valor_total_do_projeto' => isset($projeto->valorTotalProjeto) ?
                    (float) $projeto->valorTotalProjeto : null,
                'envolve_parceria_captacao_de_recursos' => $projeto->possuiParceria ?? null,
                'tipo_de_instrumento' => $projeto->tipoInstrumento ?? null,
                'numero_do_instrumento' => $projeto->numeroInstrumento ?? null,
                'nome_do_parceiro_concedente' => $projeto->nomeParceiro ?? null,
                'início_vig_instrumento' => isset($projeto->dataInicioParceria) && $projeto->dataInicioParceria !== '-' ?
                    \Carbon\Carbon::createFromFormat('d/m/Y', $projeto->dataInicioParceria)->format('Y-m-d') : null,
                'final_vig_Instrumento' => isset($projeto->dataFimParceria) && $projeto->dataFimParceria !== '-' ?
                    \Carbon\Carbon::createFromFormat('d/m/Y', $projeto->dataFimParceria)->format('Y-m-d') : null,
                'repasse_financeiro' => $projeto->recurso ?? null,
                'valor_recurso_parceiro' => isset($projeto->valorRecursoParceiro) ?
                    (float) $projeto->valorRecursoParceiro : null,
                'contrapartida_pactuada' => isset($projeto->valorContrapartidaPactuada) ?
                    (float) $projeto->valorContrapartidaPactuada : null,
                'projeto_possui_emenda_parlamentar' => $projeto->possuiEmendaParlamentar ?? null,
                'numero_da_emenda' => $projeto->numeroEmenda ?? null,
                'situacao_obra' => $projeto->statusExecucao ?? null,
                'data_prevista_conclusao' => isset($projeto->dataPrevistaConclusao) && $projeto->dataPrevistaConclusao !== '-' ?
                    \Carbon\Carbon::createFromFormat('d/m/Y', $projeto->dataPrevistaConclusao)->format('Y-m-d') : null,
                'estagio_execucao_percentual' => isset($projeto->percentualExecucaoFeita) ?
                    (float) $projeto->percentualExecucaoFeita : null,
                'data_de_paralisacao' => isset($projeto->dataParalisacao) && $projeto->dataParalisacao !== '-' ?
                    \Carbon\Carbon::createFromFormat('d/m/Y', $projeto->dataParalisacao)->format('Y-m-d') : null,
                'data_previa_de_retomada' => isset($projeto->dataPreviaRetomada) && $projeto->dataPreviaRetomada !== '-' ?
                    \Carbon\Carbon::createFromFormat('d/m/Y', $projeto->dataPreviaRetomada)->format('Y-m-d') : null,
                'motivo_da_paralisacao' => $projeto->tipoMotivoParalizacao ?? null,
                'Tempo_de_paralisacao' => $projeto->qtdeDiasParalisados ?? null,
                'responsavel_pela_inexecucao' => $projeto->responsavelInexecucao ?? null,
                'valor_pago' => isset($projeto->valorDesembolso) ?
                    (float) $projeto->valorDesembolso : null,
                'saldo_a_pagar' => isset($projeto->valorSaldoExecutar) ?
                    (float) $projeto->valorSaldoExecutar : null,
                'situacao' => $projeto->statusExecucao ?? null,
                'nomeDocumentoEmpreita' => isset($projeto->contratoDtos[0]) ? $projeto->contratoDtos[0]->nomeDocumentoEmpreita : null,
                'linkDocumentoEmpreita' => isset($projeto->contratoDtos[0]->linksDtos[0]) ? $projeto->contratoDtos[0]->linksDtos[0]->descLinkDocumentoEmpreita : null,
                'valorEmpenhado' => isset($projeto->valorEmpenhado) ?
                    (float) $projeto->valorEmpenhado : null,
                'valorLiquidado' => isset($projeto->valorLiquidado) ?
                    (float) $projeto->valorLiquidado : null,
                'valorExecutado' => isset($projeto->valorExecutado) ?
                    (float) $projeto->valorExecutado : null,
                'numeroContratoEmpreita' => isset($projeto->contratoDtos[0]) ? $projeto->contratoDtos[0]->numrContratoEmpreita : null,
                'numeroProcessoSEI' => isset($projeto->projetoProcessoSeiDtos[0]) ? $projeto->projetoProcessoSeiDtos[0]->numeroProcessoSEI : null,
            ];

            // Se debug estiver ativo, adicionar informações extras
            if ($debug) {
                $dados['debug_info'] = [
                    'raw_data' => $projeto,
                    'processed_at' => now()->toDateTimeString(),
                    'api_version' => 'v1.0'
                ];
            }

            return $dados;

        } catch (\Exception $e) {
            \Log::error('Erro ao processar dados da obra: ' . $e->getMessage(), [
                'projeto_id' => $projeto->idProjeto ?? 'unknown',
                'exception' => $e
            ]);

            // Retornar dados básicos em caso de erro
            return [
                'id_projeto' => $projeto->idProjeto ?? null,
                'nome_projeto' => $projeto->nomeProjeto ?? 'Erro ao processar',
                'objeto' => 'Erro ao processar dados',
                'situacao_obra' => 'E', // Erro
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    }
}

if (!function_exists('handler_situation_work')) {
    /**
     * Converte código da situação da obra para nome legível
     *
     * @param string $situation Código da situação (A, C, I, P, D)
     * @return string Nome da situação ou 'Desconhecido' se inválido
     */
    function handler_situation_work($situation)
    {
        switch ($situation) {
            case 'A':
                return 'Andamento';
            case 'C':
                return 'Concluída';
            case 'I':
                return 'Inacabado';
            case 'P':
                return 'Paralisado';
            case 'D':
                return 'Desconhecido';
            default:
                return 'Desconhecido';
        }
    }
}
