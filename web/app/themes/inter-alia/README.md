# Inter Alia - Tema WordPress

Tema WordPress customizado baseado em [Sage 11](https://roots.io/sage/) com suporte a ACF Blocks e carregamento condicional de assets.

## 🚀 Características

- ✅ **Sage 11** - Framework moderno para desenvolvimento WordPress
- ✅ **ACF Blocks** - Sistema de blocos customizados com ACF Composer
- ✅ **Carregamento Condicional** - CSS/JS carregados apenas quando necessário
- ✅ **Tailwind CSS** - Framework CSS utility-first
- ✅ **Laravel Blade** - Templates com sintaxe elegante
- ✅ **Bud.js** - Build tool moderna e performática
- ✅ **Performance Máxima** - Otimizado para carregar apenas o necessário

## 📋 Requisitos

- PHP >= 8.1
- Node.js >= 20.0.0
- Composer
- Yarn ou npm

## 🛠️ Instalação

### 1. Instalar dependências PHP

```bash
composer install
```

### 2. Instalar dependências Node

```bash
yarn install
# ou
npm install
```

### 3. Compilar assets

**Desenvolvimento:**
```bash
yarn dev
# ou
npm run dev
```

**Produção:**
```bash
yarn build
# ou
npm run build
```

## 📁 Estrutura do Projeto

```
inter-alia/
├── app/                    # Código PHP do tema
│   ├── Blocks/            # Blocos ACF customizados
│   ├── Providers/         # Service Providers
│   ├── View/              # View Composers
│   ├── filters.php        # Filtros WordPress
│   └── setup.php          # Configuração do tema
├── resources/             # Arquivos fonte
│   ├── scripts/           # JavaScript
│   │   ├── app.js         # JS principal
│   │   ├── editor.js      # JS do editor
│   │   └── blocks/        # JS específico por bloco
│   ├── styles/            # CSS
│   │   ├── app.css        # CSS principal
│   │   ├── editor.css     # CSS do editor
│   │   └── blocks/        # CSS específico por bloco
│   └── views/             # Templates Blade
│       ├── layouts/       # Layouts principais
│       ├── sections/      # Seções (header, footer)
│       └── blocks/        # Templates de blocos
├── public/                # Arquivos compilados (gerado)
├── bud.config.js          # Configuração do Bud.js
├── tailwind.config.js     # Configuração do Tailwind
└── composer.json          # Dependências PHP
```

## 🧩 Sistema de Blocos ACF

O tema possui um sistema avançado de blocos ACF com carregamento condicional de assets.

### Criar um Novo Bloco

1. **Criar a classe do bloco** em `app/Blocks/MeuBloco.php`:

```php
<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;

class MeuBloco extends Block
{
    public $name = 'meu-bloco';
    public $title = 'Meu Bloco';
    public $description = 'Descrição do bloco';
    public $category = 'common';
    public $icon = 'star-filled';

    public function with()
    {
        return [
            'titulo' => get_field('titulo'),
            'conteudo' => get_field('conteudo'),
        ];
    }
}
```

2. **Criar assets (opcional)**:
   - CSS: `resources/styles/blocks/meu-bloco.css`
   - JS: `resources/scripts/blocks/meu-bloco.js`

3. **Criar template (opcional)**: `resources/views/blocks/meu-bloco.blade.php`

Os assets serão automaticamente carregados apenas quando o bloco for usado na página.

📖 **Documentação completa:** Veja `app/Blocks/README.md`

## 🎨 Desenvolvimento

### Comandos Disponíveis

```bash
# Desenvolvimento com hot reload
yarn dev

# Build para produção
yarn build

# Limpar cache
lando cache-clear
# ou
lando cc
```

### Estrutura de Templates

O tema usa a hierarquia de templates do WordPress com Blade:

- `front-page.blade.php` - Página inicial
- `index.blade.php` - Template fallback
- `page.blade.php` - Páginas estáticas
- `404.blade.php` - Página de erro
- `search.blade.php` - Resultados de busca

## 🔧 Configuração

### Tailwind CSS

Configure cores, fontes e outros estilos em `tailwind.config.js`.

### Bud.js

Configure o build em `bud.config.js`. O sistema detecta automaticamente blocos e cria entrypoints.

## 📦 Dependências Principais

- **Sage 11** - Framework base
- **ACF Composer** - Integração ACF Blocks
- **Acorn** - Laravel para WordPress
- **Bud.js** - Build tool
- **Tailwind CSS** - Framework CSS

## 🚀 Performance

O tema é otimizado para performance máxima:

- ✅ Assets carregados condicionalmente por bloco
- ✅ Tailwind purged automaticamente
- ✅ Bundles separados por bloco
- ✅ Zero overhead para blocos não utilizados

## 📝 Boas Práticas

1. **Blocos ACF**: Use o sistema de blocos para todo conteúdo customizado
2. **Tailwind**: Prefira classes Tailwind sobre CSS customizado
3. **Assets**: Crie assets específicos apenas quando necessário
4. **Templates**: Use Blade para templates reutilizáveis

## 🤝 Contribuindo

Este é um projeto de estudo baseado em Sage 11. Sinta-se livre para usar como referência.

## 📄 Licença

MIT

## 🔗 Links Úteis

- [Sage Documentation](https://roots.io/sage/docs/)
- [ACF Composer](https://github.com/log1x/acf-composer)
- [Tailwind CSS](https://tailwindcss.com/)
- [Laravel Blade](https://laravel.com/docs/blade)

