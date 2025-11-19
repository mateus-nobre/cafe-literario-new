# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

## [Unreleased]

### Adicionado
- Sistema de blocos ACF com carregamento condicional de assets
- BlockManager para detecção automática de blocos utilizados
- Estrutura de diretórios para blocos (CSS, JS, templates)
- Configuração automática do Bud.js para compilar assets por bloco
- Comando `lando cache-clear` para limpeza de caches
- Templates básicos (front-page, index, page, 404, search)
- Documentação completa do sistema de blocos
- Configuração Git (.gitignore, .gitattributes, .editorconfig)
- README.md com documentação do tema

### Modificado
- Limpeza de arquivos padrão desnecessários do Sage
- Simplificação de templates e componentes
- Otimização do Tailwind para escanear blocos
- Proteções de segurança no BlockManager

### Removido
- Templates padrão de blog/post não utilizados
- Componentes e partials padrão
- Scripts/filters de exemplo
- View Composers padrão não utilizados
- Documentação padrão do Sage

## [1.0.0] - 2025-11-19

### Adicionado
- Estrutura inicial do tema baseado em Sage 11
- Integração com ACF Composer
- Sistema de carregamento condicional de assets
- Configuração de desenvolvimento com Lando

