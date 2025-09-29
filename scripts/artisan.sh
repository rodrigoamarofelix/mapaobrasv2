#!/bin/bash

# Script para executar comandos Artisan dentro do container
if [ $# -eq 0 ]; then
    echo "❌ Uso: ./scripts/artisan.sh <comando>"
    echo "📝 Exemplo: ./scripts/artisan.sh migrate"
    echo "📝 Exemplo: ./scripts/artisan.sh make:controller UserController"
    exit 1
fi

echo "🔧 Executando: php artisan $*"
docker compose exec app php artisan "$@"
