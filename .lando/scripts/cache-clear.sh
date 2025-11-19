#!/bin/bash

# Script para limpar todos os caches do projeto
# Uso: lando cache-clear ou lando cc

# Não parar em erros - alguns comandos podem falhar normalmente
set +e

echo "🧹 Limpando caches do projeto..."

# Cores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# 1. Limpar cache do WordPress (transients)
echo -e "${BLUE}📦 Limpando cache do WordPress (transients)...${NC}"
wp cache flush --allow-root 2>/dev/null || echo "  ⚠️  WP-CLI cache flush não disponível"
wp transient delete --all --allow-root 2>/dev/null || echo "  ⚠️  Erro ao limpar transients"

# 2. Limpar cache de plugins comuns
echo -e "${BLUE}🔌 Limpando cache de plugins...${NC}"
wp cache flush --allow-root 2>/dev/null || true

# Limpar cache do W3 Total Cache (se instalado)
if wp plugin is-active w3-total-cache --allow-root 2>/dev/null; then
    wp w3-total-cache flush all --allow-root 2>/dev/null || true
    echo "  ✅ W3 Total Cache limpo"
fi

# Limpar cache do WP Super Cache (se instalado)
if wp plugin is-active wp-super-cache --allow-root 2>/dev/null; then
    wp cache flush --allow-root 2>/dev/null || true
    echo "  ✅ WP Super Cache limpo"
fi

# Limpar cache do WP Rocket (se instalado)
if wp plugin is-active wp-rocket --allow-root 2>/dev/null; then
    wp rocket clean --allow-root 2>/dev/null || true
    echo "  ✅ WP Rocket limpo"
fi

# 3. Limpar cache do tema (build do Bud.js)
echo -e "${BLUE}🎨 Limpando cache do tema (Bud.js build)...${NC}"
THEME_PATH="/app/web/app/themes/inter-alia"
if [ -d "$THEME_PATH/public" ]; then
    rm -rf "$THEME_PATH/public"/*
    echo "  ✅ Pasta public/ do tema limpa"
else
    echo "  ℹ️  Pasta public/ não encontrada"
fi

# Limpar node_modules/.cache se existir
if [ -d "$THEME_PATH/node_modules/.cache" ]; then
    rm -rf "$THEME_PATH/node_modules/.cache"
    echo "  ✅ Cache do node_modules limpo"
fi

# 4. Limpar cache do Composer
echo -e "${BLUE}📚 Limpando cache do Composer...${NC}"
composer clear-cache 2>/dev/null || echo "  ⚠️  Erro ao limpar cache do Composer"
echo "  ✅ Cache do Composer limpo"

# 5. Limpar cache do npm/yarn (no serviço node)
echo -e "${BLUE}📦 Limpando cache do npm/yarn...${NC}"
echo "  ℹ️  Execute 'lando yarn cache clean' ou 'lando npm cache clean --force' se necessário"

# 6. Limpar cache do PHP (opcache)
echo -e "${BLUE}🐘 Limpando cache do PHP (opcache)...${NC}"
if command -v php &> /dev/null; then
    # Tentar limpar opcache via PHP
    php -r "if (function_exists('opcache_reset')) { opcache_reset(); echo 'OPcache resetado\n'; } else { echo 'OPcache não disponível\n'; }" 2>/dev/null || echo "  ⚠️  Não foi possível limpar OPcache"
else
    echo "  ℹ️  PHP não encontrado no PATH"
fi

# 7. Limpar cache de uploads/temp se necessário
echo -e "${BLUE}📁 Verificando cache de arquivos...${NC}"
if [ -d "/app/web/app/cache" ]; then
    find /app/web/app/cache -type f -delete 2>/dev/null || true
    echo "  ✅ Cache de arquivos limpo"
fi

# 8. Limpar cache do Bedrock (se aplicável)
echo -e "${BLUE}🪨 Limpando cache do Bedrock...${NC}"
if [ -d "/app/vendor" ]; then
    # Composer já foi limpo acima
    echo "  ✅ Cache do Bedrock verificado"
fi

echo ""
echo -e "${GREEN}✨ Limpeza de cache concluída!${NC}"
echo ""
echo "💡 Dicas:"
echo "  - Para reconstruir os assets do tema: cd web/app/themes/inter-alia && lando yarn build"
echo "  - Para limpar apenas cache do WordPress: lando wp cache flush"
echo "  - Para limpar apenas cache do Composer: lando composer clear-cache"

