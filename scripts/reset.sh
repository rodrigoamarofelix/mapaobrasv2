#!/bin/bash

# Script para resetar completamente o projeto
echo "🔄 Resetando Mapa Obras V2..."

# Parar containers
echo "🛑 Parando containers..."
docker compose down

# Remover volumes (CUIDADO: isso apagará todos os dados do banco)
echo "🗑️ Removendo volumes..."
docker compose down -v

# Remover imagens
echo "🧹 Removendo imagens..."
docker compose down --rmi all

# Limpar cache do Docker
echo "🧽 Limpando cache do Docker..."
docker system prune -f

echo "✅ Reset completo realizado!"
echo "💡 Execute ./scripts/start.sh para reiniciar o projeto"
