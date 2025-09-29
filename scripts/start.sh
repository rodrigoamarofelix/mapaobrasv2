#!/bin/bash

# Script de inicialização do projeto Mapa Obras V2
echo "🚀 Iniciando Mapa Obras V2..."

# Verificar se o Docker está rodando
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker não está rodando. Por favor, inicie o Docker primeiro."
    exit 1
fi

# Parar containers existentes
echo "🛑 Parando containers existentes..."
docker compose down

# Construir e iniciar os containers
echo "🔨 Construindo e iniciando containers..."
docker compose up -d --build

# Aguardar os serviços ficarem prontos
echo "⏳ Aguardando serviços ficarem prontos..."
sleep 10

# Executar comandos do Laravel dentro do container
echo "📦 Instalando dependências do Composer..."
docker compose exec app composer install --no-interaction

echo "🔑 Gerando chave da aplicação..."
docker compose exec app php artisan key:generate

echo "🗄️ Executando migrações..."
docker compose exec app php artisan migrate --force

echo "📁 Configurando permissões..."
docker compose exec app mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
docker compose exec app chmod -R 755 storage bootstrap/cache

echo "✅ Projeto iniciado com sucesso!"
echo "🌐 Acesse: http://localhost:8080"
echo "📊 PostgreSQL: localhost:5432"
echo "🔴 Redis: localhost:6379"
