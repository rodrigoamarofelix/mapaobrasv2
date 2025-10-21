@extends('layouts.app')

@section('title', 'Detalhes do Projeto - Teste')
@section('description', 'Página de teste para detalhes do projeto')

@section('content')
<div class="container">
    <h1>DETALHES DO PROJETO - TESTE</h1>

    <div class="row">
        <div class="col-12">
            <h2>Estatísticas</h2>
            <p>Total de Projetos: {{ $estatisticas->total_projetos ?? 0 }}</p>
            <p>Valor Total: R${{ number_format(($estatisticas->valor_total ?? 0) / 1000000000, 2, ',', '.') }} Bi</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h2>Projetos</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Situação</th>
                        <th>ID Projeto</th>
                        <th>Órgão</th>
                        <th>Projeto</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projetos as $projeto)
                    <tr onclick="alert('Projeto: {{ $projeto->nome_projeto }}')">
                        <td>{{ $projeto->situacao_obra }}</td>
                        <td>{{ $projeto->id_projeto }}</td>
                        <td>{{ $projeto->orgao }}</td>
                        <td>{{ $projeto->nome_projeto }}</td>
                        <td>{{ $projeto->tipos_do_projeto }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">Nenhum projeto encontrado</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h2>Debug</h2>
            <p>Total de registros: {{ $debug['total_registros'] ?? 'N/A' }}</p>
            <p>Projetos retornados: {{ count($projetos) }}</p>
        </div>
    </div>
</div>
@endsection

