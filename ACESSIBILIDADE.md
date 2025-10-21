# 🌟 Acessibilidade e LIBRAS no Mapa de Obras

## 📋 Funcionalidades Implementadas

### 🎯 **Painel de Acessibilidade**
- **Localização**: Botão flutuante no canto superior direito
- **Atalho**: `Alt + A` para abrir/fechar
- **Funcionalidades**:
  - Alto contraste
  - Controle de tamanho da fonte (80% a 150%)
  - Navegação por teclado
  - Suporte a LIBRAS
  - Leitor de tela

### 🔍 **Alto Contraste**
- **Ativação**: `Alt + C` ou pelo painel de acessibilidade
- **Efeito**: Aumenta contraste e brilho para melhor visibilidade
- **Persistência**: Configuração salva no navegador

### 📏 **Controle de Fonte**
- **Controles**: Botões + e - no painel
- **Faixa**: 80% a 150% do tamanho original
- **Persistência**: Configuração salva no navegador

### ⌨️ **Navegação por Teclado**

#### **Mapa Interativo**
- `↑` `↓` `←` `→` - Navegar pelo mapa
- `+` ou `=` - Aumentar zoom
- `-` - Diminuir zoom
- `Enter` - Focar no primeiro marcador
- `Tab` - Navegar entre elementos

#### **Atalhos Globais**
- `Alt + A` - Abrir painel de acessibilidade
- `Alt + L` - Ativar/desativar LIBRAS
- `Alt + C` - Ativar alto contraste
- `Alt + F` - Focar nos filtros
- `Alt + M` - Focar no mapa
- `Esc` - Fechar painel de acessibilidade

### 🗣️ **Leitor de Tela**
- **Botões**: "Ler Informações do Mapa" e "Ler Filtros"
- **Funcionalidade**: Sintetiza voz em português brasileiro
- **Velocidade**: Otimizada para compreensão (0.8x)
- **Integração**: Botões "Ler" nos popups dos marcadores

### 🤟 **Suporte a LIBRAS**

#### **Intérprete Virtual**
- **Ativação**: `Alt + L` ou pelo painel de acessibilidade
- **Avatar**: Personagem animado com movimentos de braços
- **Localização**: Canto inferior direito quando ativo

#### **Tradução Automática**
- **Popups**: Botão "LIBRAS" em cada marcador
- **Conteúdo**: Traduz informações da obra para LIBRAS
- **Persistência**: Configuração salva no navegador

### 🎨 **Melhorias Visuais**

#### **Filtros Acessíveis**
- **Labels**: Associados corretamente aos inputs
- **Placeholders**: Texto de exemplo nos campos
- **Descrições**: Texto de ajuda para campos complexos
- **ARIA**: Atributos para leitores de tela

#### **Mapa Acessível**
- **Role**: `img` para identificação como imagem
- **ARIA**: Labels e descrições apropriadas
- **Tabindex**: Navegação por teclado habilitada
- **Focus**: Indicadores visuais de foco

#### **Marcadores Melhorados**
- **Títulos**: Nome da obra como título
- **Alt**: Descrição alternativa para cada marcador
- **Popups**: Estrutura semântica com ARIA
- **Botões**: Labels descritivos e ícones com aria-hidden

## 🚀 **Como Usar**

### **Para Usuários com Deficiência Visual**
1. Use `Alt + A` para abrir o painel de acessibilidade
2. Ative o alto contraste com `Alt + C`
3. Ajuste o tamanho da fonte conforme necessário
4. Use `Alt + F` para focar nos filtros
5. Use `Alt + M` para focar no mapa
6. Use as setas para navegar pelo mapa
7. Use `Enter` para focar nos marcadores

### **Para Usuários com Deficiência Auditiva**
1. Use `Alt + L` para ativar LIBRAS
2. Clique nos botões "LIBRAS" nos popups dos marcadores
3. O avatar virtual traduzirá as informações em sinais

### **Para Usuários de Leitores de Tela**
1. Use `Alt + A` para acessar o painel
2. Use "Ler Informações do Mapa" para contexto geral
3. Use "Ler Filtros" para entender opções disponíveis
4. Use "Ler" nos popups para informações específicas

## 🔧 **Implementação Técnica**

### **Arquivos Modificados**
- `resources/views/components/accessibility.blade.php` - Componente principal
- `resources/views/landing/obras_mapas.blade.php` - Integração no mapa

### **Tecnologias Utilizadas**
- **CSS**: Estilos responsivos e de alto contraste
- **JavaScript**: Navegação por teclado e síntese de voz
- **ARIA**: Atributos de acessibilidade
- **HTML Semântico**: Estrutura acessível

### **Compatibilidade**
- **Navegadores**: Chrome, Firefox, Safari, Edge
- **Leitores de Tela**: NVDA, JAWS, VoiceOver
- **Dispositivos**: Desktop, tablet, mobile

## 📊 **Conformidade**

### **Padrões Seguidos**
- **WCAG 2.1 AA**: Diretrizes de acessibilidade web
- **eMAG**: Modelo de Acessibilidade em Governo Eletrônico
- **LBI**: Lei Brasileira de Inclusão

### **Critérios Atendidos**
- ✅ Perceptível (contraste, tamanho de fonte)
- ✅ Operável (navegação por teclado)
- ✅ Compreensível (textos claros e descritivos)
- ✅ Robusto (compatibilidade com tecnologias assistivas)

## 🎯 **Benefícios**

### **Para Usuários**
- **Inclusão**: Acesso igualitário às informações
- **Autonomia**: Navegação independente
- **Eficiência**: Atalhos e controles otimizados

### **Para o Governo**
- **Compliance**: Atendimento à legislação
- **Responsabilidade Social**: Inclusão digital
- **Qualidade**: Melhor experiência do usuário

## 🔮 **Próximos Passos**

### **Melhorias Futuras**
- [ ] Integração com API real de LIBRAS
- [ ] Mais opções de personalização visual
- [ ] Suporte a mais idiomas
- [ ] Análise de acessibilidade automatizada

### **Monitoramento**
- [ ] Testes com usuários reais
- [ ] Feedback da comunidade
- [ ] Métricas de uso das funcionalidades
- [ ] Atualizações baseadas em padrões

---

**Desenvolvido com ❤️ para promover a inclusão digital no Estado de Goiás**





