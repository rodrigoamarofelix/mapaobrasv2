# Mapa Obras V2 - Laravel

Sistema de gestão e mapeamento de obras públicas migrado do CodeIgniter para Laravel.

## Funcionalidades

- **Mapa Interativo**: Visualização de obras em mapa com filtros avançados
- **Lista de Obras**: Lista detalhada com filtros personalizados
- **Detalhes da Obra**: Informações completas sobre cada projeto
- **Business Intelligence**: Dashboards e análises de dados
- **API REST**: Endpoints para integração com outros sistemas
- **Exportação de Dados**: Exportação em formato JSON

## Estrutura do Projeto

```
app/
├── Http/
│   └── Controllers/
│       ├── LandingController.php    # Controller do módulo Landing
│       ├── ObraController.php       # Controller do módulo Obras
│       └── ApiController.php        # Controller da API
config/
└── mapaobras.php                    # Configurações específicas do sistema
resources/
└── views/
    ├── layouts/
    │   └── app.blade.php            # Layout principal
    ├── landing/
    │   ├── obras_mapas.blade.php    # Página do mapa de obras
    │   ├── estados.blade.php        # Página "Saiba Mais"
    │   └── bi.blade.php             # Página de Business Intelligence
    └── obras/
        ├── index.blade.php          # Lista de obras
        └── show.blade.php           # Detalhes da obra
routes/
└── web.php                          # Rotas da aplicação
```

## Instalação

### Pré-requisitos

- PHP 8.2+
- Composer
- MySQL/MariaDB
- Node.js e NPM (para assets)

### Passos de Instalação

1. **Clone o repositório**
   ```bash
   git clone <repository-url>
   cd mapaobrasv2
   ```

2. **Instale as dependências**
   ```bash
   composer install
   npm install
   ```

3. **Configure o ambiente**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure o banco de dados**
   Edite o arquivo `.env` com as configurações do seu banco:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=mapaobras
   DB_USERNAME=seu_usuario
   DB_PASSWORD=sua_senha
   ```

5. **Execute as migrations**
   ```bash
   php artisan migrate
   ```

6. **Compile os assets**
   ```bash
   npm run build
   ```

7. **Inicie o servidor**
   ```bash
   php artisan serve
   ```

## Configuração do Banco de Dados

O sistema utiliza as seguintes tabelas principais:

- `mapaobras`: Dados principais das obras
- `projeto_obras`: Informações de localização das obras
- `municipios`: Dados dos municípios
- `markers`: Marcadores para o mapa
- `publicacao`: Publicações do diário oficial
- `edicao`: Edições do diário oficial
- `tipo`: Tipos de publicação

## Rotas Principais

### Landing
- `/` - Página inicial (mapa de obras)
- `/saiba-mais` - Página "Saiba Mais"
- `/bi` - Business Intelligence
- `/obras-maps` - Mapa de obras
- `/detalhe-mapas/{id}` - Detalhes da obra no mapa

### Obras
- `/obras` - Lista de obras
- `/obras/{id}` - Detalhes da obra
- `/obras/filter` - Filtrar obras
- `/obras/export/json` - Exportar dados

### API
- `/api/obras` - Lista todas as obras
- `/api/obras/{id}` - Obra específica
- `/api/filter` - Filtrar obras via API
- `/api/obras/statistics` - Estatísticas das obras

## Certificados SSL

O sistema suporta certificados SSL para desenvolvimento local. Os certificados devem ser colocados em:
- `storage/app/certificates/localhost.pem`
- `storage/app/certificates/localhost-key.pem`

Configure no `.env`:
```env
SSL_ENABLED=true
SSL_CERT_PATH=storage/app/certificates/localhost.pem
SSL_KEY_PATH=storage/app/certificates/localhost-key.pem
```

## Migração do CodeIgniter

Este projeto foi migrado do CodeIgniter mantendo a mesma funcionalidade:

### Controllers Migrados
- `Site.php` → `LandingController.php`
- `Api.php` → `ApiController.php`

### Funcionalidades Mantidas
- Consultas SQL originais preservadas
- Estrutura de dados mantida
- Filtros e parâmetros compatíveis
- Integração com banco de dados existente

## Desenvolvimento

### Estrutura de Controllers

**LandingController**: Gerencia as páginas principais do sistema
- `index()`: Página inicial (mapa de obras)
- `obrasMaps()`: Exibe o mapa com todas as obras
- `detalheMapas()`: Detalhes de uma obra específica
- Métodos auxiliares para consultas ao banco

**ObraController**: Gerencia a listagem e detalhes das obras
- `index()`: Lista todas as obras
- `show()`: Detalhes de uma obra
- `filter()`: Filtra obras por critérios
- `export()`: Exporta dados em JSON

**ApiController**: Fornece endpoints da API
- `index()`: Lista todas as obras
- `show()`: Obra específica
- `filter()`: Filtros via API
- `statistics()`: Estatísticas das obras

### Views

Todas as views utilizam Bootstrap 5 e componentes modernos:
- Layout responsivo
- Mapas interativos com Leaflet
- Gráficos com Chart.js
- Interface intuitiva

## Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request

## Licença

Este projeto está sob a licença MIT.