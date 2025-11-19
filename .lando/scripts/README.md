# Scripts do Lando

Este diretório contém scripts customizados para comandos do Lando.

## Comandos Disponíveis

### `lando cache-clear` ou `lando cc`

Limpa todos os caches do projeto:

- ✅ Cache do WordPress (transients, object cache)
- ✅ Cache de plugins (W3 Total Cache, WP Super Cache, WP Rocket)
- ✅ Cache do tema (pasta `public/` do Bud.js)
- ✅ Cache do node_modules
- ✅ Cache do Composer
- ✅ Cache do PHP (OPcache)
- ✅ Cache de arquivos temporários

**Uso:**
```bash
lando cache-clear
# ou
lando cc
```

**Após limpar o cache, você pode precisar reconstruir os assets:**
```bash
cd web/app/themes/inter-alia
lando yarn build
```

