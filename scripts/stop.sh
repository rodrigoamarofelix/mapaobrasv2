#!/bin/bash

# Script para parar o projeto Mapa Obras V2
echo "🛑 Parando Mapa Obras V2..."

# Parar containers
docker compose down

echo "✅ Projeto parado com sucesso!"
