<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Obras - {{ $data_exportacao }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 10px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 10px;
        }

        .header h1 {
            color: #e74c3c;
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0;
            font-size: 12px;
        }

        .summary {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border-left: 4px solid #e74c3c;
        }

        .summary h3 {
            margin: 0 0 10px 0;
            color: #e74c3c;
            font-size: 14px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .summary-item {
            text-align: center;
            padding: 5px;
            background-color: white;
            border-radius: 3px;
        }

        .summary-item strong {
            display: block;
            color: #e74c3c;
            font-size: 12px;
        }

        .summary-item span {
            font-size: 10px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 8px;
        }

        th {
            background-color: #e74c3c;
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
        }

        td {
            padding: 4px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-align: center;
        }

        .status-C { background-color: #d4edda; color: #155724; }
        .status-A { background-color: #cce5ff; color: #004085; }
        .status-I { background-color: #fff3cd; color: #856404; }
        .status-P { background-color: #f8d7da; color: #721c24; }
        .status-D { background-color: #e2e3e5; color: #383d41; }

        .currency {
            text-align: right;
            font-family: monospace;
        }

        .percentage {
            text-align: center;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .page-break {
            page-break-before: always;
        }

        .text-truncate {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏗️ Relatório de Obras - Governo de Goiás</h1>
        <p>Data de Exportação: {{ $data_exportacao }}</p>
        <p>Sistema de Monitoramento de Obras Públicas</p>
    </div>

    <div class="summary">
        <h3>📊 Resumo Executivo</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <strong>{{ $total }}</strong>
                <span>Total de Obras</span>
            </div>
            <div class="summary-item">
                <strong>{{ collect($obras)->where('situacao_obra', 'C')->count() }}</strong>
                <span>Concluídas</span>
            </div>
            <div class="summary-item">
                <strong>{{ collect($obras)->where('situacao_obra', 'A')->count() }}</strong>
                <span>Em Andamento</span>
            </div>
            <div class="summary-item">
                <strong>R$ {{ number_format(collect($obras)->sum('valor_total_do_projeto'), 2, ',', '.') }}</strong>
                <span>Valor Total</span>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">ID</th>
                <th style="width: 25%;">Nome do Projeto</th>
                <th style="width: 20%;">Objeto</th>
                <th style="width: 12%;">Área Temática</th>
                <th style="width: 8%;">Situação</th>
                <th style="width: 12%;">Município</th>
                <th style="width: 10%;">Órgão</th>
                <th style="width: 8%;">Valor Total</th>
                <th style="width: 8%;">Execução</th>
                <th style="width: 8%;">Processo SEI</th>
            </tr>
        </thead>
        <tbody>
            @foreach($obras as $index => $obra)
                @if($index > 0 && $index % 25 == 0)
                    <tr><td colspan="10" class="page-break"></td></tr>
                @endif
                <tr>
                    <td>{{ $obra->id_projeto ?? $obra->id }}</td>
                    <td class="text-truncate" title="{{ $obra->nome_projeto ?? 'Não informado' }}">
                        {{ Str::limit($obra->nome_projeto ?? 'Não informado', 30) }}
                    </td>
                    <td class="text-truncate" title="{{ $obra->objeto ?? 'Não informado' }}">
                        {{ Str::limit($obra->objeto ?? 'Não informado', 25) }}
                    </td>
                    <td>{{ $obra->area_tematica ?? 'Não informado' }}</td>
                    <td>
                        <span class="status-badge status-{{ $obra->situacao_obra ?? 'D' }}">
                            @switch($obra->situacao_obra)
                                @case('C') Concluída @break
                                @case('A') Andamento @break
                                @case('I') Interrompida @break
                                @case('P') Paralisada @break
                                @default Não informado
                            @endswitch
                        </span>
                    </td>
                    <td>{{ $obra->municipio ?? 'Não informado' }}</td>
                    <td>{{ $obra->sigla ?? 'Não informado' }}</td>
                    <td class="currency">
                        @if($obra->valor_total_do_projeto)
                            R$ {{ number_format($obra->valor_total_do_projeto, 2, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="percentage">
                        @if($obra->estagio_execucao_percentual)
                            {{ $obra->estagio_execucao_percentual }}%
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $obra->numeroProcessoSEI ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Relatório gerado automaticamente pelo Sistema de Monitoramento de Obras Públicas</p>
        <p>Governo do Estado de Goiás - Secretaria de Estado da Infraestrutura</p>
        <p>Página {{ $index + 1 }} de {{ ceil(count($obras) / 25) }} | Total de registros: {{ $total }}</p>
    </div>
</body>
</html>





