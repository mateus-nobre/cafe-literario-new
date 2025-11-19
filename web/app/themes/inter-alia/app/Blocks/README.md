# Documentação Padrão - Criação de Blocos ACF

Esta documentação descreve o padrão para criação de novos blocos ACF no projeto. Todos os novos blocos devem seguir este padrão como ponto de partida.

## 📋 Índice

1. [Visão Geral](#visão-geral)
2. [Estrutura de Diretórios](#estrutura-de-diretórios)
3. [Criando um Novo Bloco](#criando-um-novo-bloco)
4. [Arquivos CSS e JS](#arquivos-css-e-js)
5. [Templates Blade](#templates-blade)
6. [Propriedades e Configurações](#propriedades-e-configurações)
7. [Sistema de Carregamento Condicional](#sistema-de-carregamento-condicional)
8. [Convenções de Nomenclatura](#convenções-de-nomenclatura)
9. [Exemplo Completo](#exemplo-completo)

---

## 🎯 Visão Geral

O sistema de blocos utiliza:
- **ACF Composer** para criação e gerenciamento de blocos ACF
- **BlockManager** para detecção automática e carregamento condicional de assets
- **Bud.js** para compilação automática de CSS e JS por bloco
- **Blade** para templates dos blocos

### Funcionalidades Principais

- ✅ **Registro Automático**: Blocos são descobertos automaticamente do diretório `app/Blocks`
- ✅ **Carregamento Condicional**: CSS e JS são carregados apenas quando o bloco é usado na página
- ✅ **Compilação Automática**: Bud.js compila automaticamente os assets de cada bloco
- ✅ **Performance Otimizada**: Apenas os assets necessários são carregados

---

## 📁 Estrutura de Diretórios

```
app/Blocks/
├── BlockManager.php          # Gerenciador de blocos
└── SeuNovoBloco.php          # Seus blocos personalizados

resources/
├── styles/
│   ├── blocks/               # CSS específico por bloco
│   │   └── seu-novo-bloco.css
│   ├── app.css               # Estilos globais
│   └── editor.css            # Estilos do editor
│
├── scripts/
│   ├── blocks/               # JS específico por bloco
│   │   └── seu-novo-bloco.js
│   ├── app.js                # Scripts globais
│   └── editor.js             # Scripts do editor
│
└── views/
    └── blocks/               # Templates Blade dos blocos
        └── seu-novo-bloco.blade.php
```

---

## 🚀 Criando um Novo Bloco

### Passo 1: Criar a Classe do Bloco

Crie um novo arquivo em `app/Blocks/SeuNovoBloco.php` seguindo o padrão estabelecido:

```php
<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class SeuNovoBloco extends Block
{
    /**
     * Nome do bloco (slug)
     * Deve ser em kebab-case e corresponder ao nome do arquivo convertido
     * Exemplo: SeuNovoBloco.php → seu-novo-bloco
     */
    public $name = 'seu-novo-bloco';

    /**
     * Título exibido no editor do WordPress
     */
    public $title = 'Seu Novo Bloco';

    /**
     * Descrição do bloco exibida no editor
     */
    public $description = 'Descrição do que o bloco faz.';

    /**
     * Categoria do bloco no editor
     * Opções: 'common', 'formatting', 'layout', 'widgets', 'embed', 'theme'
     */
    public $category = 'theme';

    /**
     * Ícone do bloco (Dashicons)
     * Ver ícones disponíveis: https://developer.wordpress.org/resource/dashicons/
     */
    public $icon = 'editor-ul';

    /**
     * Modo de visualização no editor
     * 'preview' = mostra preview do bloco
     * 'edit' = mostra apenas campos ACF
     */
    public $mode = 'preview';

    /**
     * Configuração de espaçamento (padding/margin)
     * null = desabilitado, true = habilitado
     */
    public $spacing = [
        'padding' => null,
        'margin' => null,
    ];

    /**
     * Recursos suportados pelo bloco
     * Define quais funcionalidades do editor estão disponíveis
     */
    public $supports = [
        'align' => true,              // Alinhamento do bloco (wide, full, etc)
        'align_text' => false,        // Alinhamento de texto
        'align_content' => false,     // Alinhamento de conteúdo
        'full_height' => false,       // Altura total
        'anchor' => false,            // ID de âncora
        'mode' => true,               // Modo preview/edit
        'multiple' => true,           // Permite múltiplas instâncias
        'jsx' => true,                // Suporte a JSX (InnerBlocks)
        'color' => [
            'background' => false,    // Cor de fundo
            'text' => false,          // Cor do texto
            'gradients' => false,     // Gradientes
        ],
        'spacing' => [
            'padding' => false,       // Padding
            'margin' => false,        // Margin
        ],
    ];

    /**
     * Dados passados para o template antes da renderização
     * 
     * @return array
     */
    public function with(): array
    {
        return [
            'items' => $this->items(),
            // Adicione outros dados aqui
        ];
    }

    /**
     * Configuração dos campos ACF do bloco
     * 
     * @return array
     */
    public function fields(): array
    {
        $fields = Builder::make('seu_novo_bloco');

        $fields
            ->addRepeater('items')
                ->addText('item')
            ->endRepeater();

        return $fields->build();
    }

    /**
     * Método auxiliar para recuperar os itens
     * 
     * @return array
     */
    public function items()
    {
        return get_field('items') ?: $this->example['items'] ?? [];
    }
}
```

### Passo 2: O Bloco é Registrado Automaticamente

O `ThemeServiceProvider` descobre automaticamente todos os arquivos `.php` no diretório `app/Blocks` e registra os blocos. **Não é necessário fazer nada além de criar o arquivo!**

---

## 🎨 Arquivos CSS e JS

### Localização dos Arquivos

Os arquivos CSS e JS devem ser criados nos seguintes diretórios:

- **CSS**: `resources/styles/blocks/{nome-do-bloco}.css`
- **JS**: `resources/scripts/blocks/{nome-do-bloco}.js`

**Importante**: O nome do arquivo deve corresponder ao `$name` do bloco (em kebab-case).

### Exemplo: CSS do Bloco

Crie `resources/styles/blocks/seu-novo-bloco.css`:

```css
/**
 * Estilos específicos do bloco Seu Novo Bloco
 * Estes estilos serão carregados apenas quando o bloco for usado na página
 */

@tailwind base;
@tailwind components;
@tailwind utilities;

/* Classes específicas do bloco */
.seu-novo-bloco {
  @apply container mx-auto px-4;
}

.seu-novo-bloco__item {
  @apply mb-4 p-4 bg-white rounded-lg shadow;
}

/* Ou estilos customizados */
.seu-novo-bloco__item:hover {
  transform: translateY(-2px);
  transition: transform 0.2s ease;
}
```

### Exemplo: JS do Bloco

Crie `resources/scripts/blocks/seu-novo-bloco.js`:

```javascript
/**
 * Scripts específicos do bloco Seu Novo Bloco
 * Estes scripts serão carregados apenas quando o bloco for usado na página
 */

import domReady from '@roots/sage/client/dom-ready';

domReady(() => {
  // Seleciona todos os blocos na página
  const blocos = document.querySelectorAll('.seu-novo-bloco');

  blocos.forEach(bloco => {
    // Inicialização do bloco
    console.log('Bloco inicializado:', bloco);

    // Exemplo: Adicionar event listeners
    const items = bloco.querySelectorAll('.seu-novo-bloco__item');
    
    items.forEach(item => {
      item.addEventListener('click', () => {
        console.log('Item clicado:', item);
      });
    });
  });
});
```

### Compilação Automática

O `bud.config.js` detecta automaticamente os arquivos CSS e JS nos diretórios `blocks/` e cria bundles separados para cada bloco:

- Bundle CSS: `block-{nome-do-bloco}.css`
- Bundle JS: `block-{nome-do-bloco}.js`

**Não é necessário configurar nada no `bud.config.js`!** A detecção é automática.

---

## 📄 Templates Blade

### Localização do Template

O template deve ser criado em:
`resources/views/blocks/{nome-do-bloco}.blade.php`

### Estrutura Padrão do Template

```blade
{{-- 
  Template do bloco Seu Novo Bloco
  Variáveis disponíveis: $block, $items (e outras definidas em with())
--}}

@unless ($block->preview)
  <div {{ $attributes }} class="seu-novo-bloco">
@endunless

@if ($items && count($items) > 0)
  <ul class="seu-novo-bloco__list">
    @foreach ($items as $item)
      <li class="seu-novo-bloco__item">
        {{ $item['item'] }}
      </li>
    @endforeach
  </ul>
@else
  <p class="seu-novo-bloco__empty">
    {{ $block->preview ? 'Adicione um item...' : 'Nenhum item encontrado!' }}
  </p>
@endif

{{-- InnerBlocks: permite adicionar blocos filhos (se jsx => true) --}}
@if ($block->template)
  <div class="seu-novo-bloco__inner">
    <InnerBlocks template="{{ $block->template }}" />
  </div>
@endif

@unless ($block->preview)
  </div>
@endunless
```

### Variáveis Disponíveis no Template

- `$block`: Objeto do bloco com informações como `preview`, `template`, etc.
- `$attributes`: Atributos HTML do bloco (classes, ID, etc.)
- Variáveis definidas no método `with()`: `$items`, etc.

---

## ⚙️ Propriedades e Configurações

### Propriedades Obrigatórias

| Propriedade | Tipo | Descrição | Exemplo |
|------------|------|-----------|---------|
| `$name` | string | Slug do bloco (kebab-case) | `'seu-novo-bloco'` |
| `$title` | string | Título exibido no editor | `'Seu Novo Bloco'` |
| `$description` | string | Descrição do bloco | `'Descrição do bloco'` |
| `$category` | string | Categoria no editor | `'theme'` |

### Propriedades Opcionais

| Propriedade | Tipo | Padrão | Descrição |
|------------|------|--------|-----------|
| `$icon` | string | `'block-default'` | Ícone Dashicons |
| `$mode` | string | `'preview'` | Modo de visualização |
| `$spacing` | array | `[]` | Configuração de espaçamento |
| `$supports` | array | `[]` | Recursos suportados |
| `$keywords` | array | `[]` | Palavras-chave para busca |
| `$example` | array | `[]` | Dados de exemplo para preview |

### Métodos Obrigatórios

#### `fields(): array`

Define os campos ACF do bloco usando o `Builder`:

```php
public function fields(): array
{
    $fields = Builder::make('seu_novo_bloco');

    $fields
        ->addText('titulo', [
            'label' => 'Título',
            'required' => true,
        ])
        ->addTextarea('conteudo', [
            'label' => 'Conteúdo',
        ])
        ->addRepeater('items')
            ->addText('item')
            ->addImage('imagem')
        ->endRepeater();

    return $fields->build();
}
```

#### `with(): array`

Define os dados passados para o template:

```php
public function with(): array
    {
        return [
            'titulo' => get_field('titulo'),
            'conteudo' => get_field('conteudo'),
        'items' => $this->items(),
    ];
}
```

### Métodos Opcionais

#### `assets(array $block): void`

Sobrescreva apenas se precisar de lógica customizada de carregamento de assets:

```php
public function assets(array $block): void
{
    // Por padrão, os assets são carregados automaticamente pelo BlockManager
    // Sobrescreva apenas se precisar de lógica específica
}
```

---

## 🔄 Sistema de Carregamento Condicional

### Como Funciona

1. **Detecção Automática**: O `BlockManager` detecta quais blocos estão sendo usados na página atual através dos hooks do WordPress:
   - `render_block`: Detecta blocos durante a renderização
   - `the_content`: Analisa o conteúdo antes da renderização
   - `checkAcfFields()`: Verifica campos ACF flexíveis

2. **Carregamento Condicional**: Apenas os bundles CSS/JS dos blocos detectados são enfileirados:
   ```php
   // BlockManager.php linha 204-210
   $bundleHandle = "block-{$blockSlug}";
   $bundle = bundle($bundleHandle);
   if ($bundle) {
       $bundle->enqueue();
   }
   ```

3. **Compilação Automática**: O `bud.config.js` compila automaticamente os assets:
   ```javascript
   // bud.config.js linha 69-89
   blockNames.forEach(blockName => {
     const cssPath = join(__dirname, `resources/styles/blocks/${blockName}.css`);
     const jsPath = join(__dirname, `resources/scripts/blocks/${blockName}.js`);
     // Cria entrypoint se arquivos existirem
   });
   ```

### Benefícios

- ✅ **Performance**: Apenas os assets necessários são carregados
- ✅ **Otimização**: Tailwind purga automaticamente classes não utilizadas
- ✅ **Modularidade**: Cada bloco tem seus próprios assets isolados
- ✅ **Manutenibilidade**: Fácil de adicionar/remover blocos sem afetar outros

---

## 📝 Convenções de Nomenclatura

### Arquivo PHP da Classe

- **Formato**: PascalCase
- **Sufixo**: Opcional (não é obrigatório usar "Block")
- **Exemplo**: `SliderHero.php`, `HeroSection.php`, `Gallery.php`, `ContentBlock.php`

### Nome do Bloco (`$name`)

- **Formato**: String com espaços (será convertido automaticamente para slug)
- **Conversão**: O ACF Composer converte automaticamente para kebab-case
  - `'Slider Hero'` → `slider-hero`
  - `'Hero Section'` → `hero-section`
  - `'Gallery'` → `gallery`

### Arquivos CSS e JS

- **Formato**: kebab-case (mesmo que `$name`)
- **Localização**:
  - CSS: `resources/styles/blocks/{nome-do-bloco}.css`
  - JS: `resources/scripts/blocks/{nome-do-bloco}.js`

### Template Blade

- **Formato**: kebab-case (mesmo que `$name`)
- **Localização**: `resources/views/blocks/{nome-do-bloco}.blade.php`

### Field Group ACF

- **Formato**: snake_case
- **Exemplo**: `seu_novo_bloco` (no `Builder::make()`)

### Classes CSS

- **Formato**: BEM (Block Element Modifier) recomendado
- **Prefixo**: Nome do bloco em kebab-case
- **Exemplo**:
  ```css
  .seu-novo-bloco { }           /* Block */
  .seu-novo-bloco__item { }     /* Element */
  .seu-novo-bloco--highlighted { } /* Modifier */
  ```

---

## 📚 Exemplo Completo

### 1. Classe do Bloco: `app/Blocks/HeroSection.php`

```php
<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class HeroSection extends Block
{
    public $name = 'Hero Section';
    public $title = 'Hero Section';
    public $description = 'Bloco hero para seções principais da página.';
    public $category = 'theme';
    public $icon = 'cover-image';
    public $mode = 'preview';

    public $spacing = [
        'padding' => null,
        'margin' => null,
    ];

    public $supports = [
        'align' => true,
        'align_text' => false,
        'align_content' => false,
        'full_height' => false,
        'anchor' => false,
        'mode' => true,
        'multiple' => true,
        'jsx' => false,
        'color' => [
            'background' => false,
            'text' => false,
            'gradients' => false,
        ],
        'spacing' => [
            'padding' => false,
            'margin' => false,
        ],
    ];

    public function with(): array
    {
        return [
            'titulo' => $this->titulo(),
            'subtitulo' => $this->subtitulo(),
            'imagem' => $this->imagem(),
            'botao' => $this->botao(),
        ];
    }

    public function fields(): array
    {
        $fields = Builder::make('hero_section');

        $fields
            ->addText('titulo', [
                'label' => 'Título',
                'required' => true,
            ])
            ->addTextarea('subtitulo', [
                'label' => 'Subtítulo',
            ])
            ->addImage('imagem', [
                'label' => 'Imagem de Fundo',
                'return_format' => 'array',
            ])
            ->addGroup('botao')
                ->addText('texto', [
                    'label' => 'Texto do Botão',
                ])
                ->addUrl('url', [
                    'label' => 'URL do Botão',
                ])
            ->endGroup();

        return $fields->build();
    }

    public function titulo()
    {
        return get_field('titulo') ?: $this->example['titulo'] ?? 'Título Padrão';
    }

    public function subtitulo()
    {
        return get_field('subtitulo') ?: '';
    }

    public function imagem()
    {
        return get_field('imagem');
    }

    public function botao()
    {
        return get_field('botao');
    }
}
```

### 2. CSS: `resources/styles/blocks/hero-section.css`

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

.hero-section {
  @apply relative min-h-screen flex items-center justify-center;
  background-size: cover;
  background-position: center;
}

.hero-section::before {
  @apply absolute inset-0 bg-black bg-opacity-50;
  content: '';
}

.hero-section__content {
  @apply relative z-10 text-center text-white px-4;
}

.hero-section__titulo {
  @apply text-4xl md:text-6xl font-bold mb-4;
}

.hero-section__subtitulo {
  @apply text-xl md:text-2xl mb-8;
}

.hero-section__botao {
  @apply inline-block px-8 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors;
}
```

### 3. JS: `resources/scripts/blocks/hero-section.js`

```javascript
import domReady from '@roots/sage/client/dom-ready';

domReady(() => {
  const heroSections = document.querySelectorAll('.hero-section');

  heroSections.forEach(block => {
    const imagem = block.dataset.imagem;
    
    if (imagem) {
      block.style.backgroundImage = `url(${imagem})`;
    }

    // Exemplo: Parallax effect
    window.addEventListener('scroll', () => {
      const scrolled = window.pageYOffset;
      block.style.transform = `translateY(${scrolled * 0.5}px)`;
    });
  });
});
```

### 4. Template: `resources/views/blocks/hero-section.blade.php`

```blade
@unless ($block->preview)
  <section {{ $attributes }} class="hero-section" data-imagem="{{ $imagem['url'] ?? '' }}">
@endunless

<div class="hero-section__content">
  @if ($titulo)
    <h1 class="hero-section__titulo">{{ $titulo }}</h1>
  @endif

  @if ($subtitulo)
    <p class="hero-section__subtitulo">{{ $subtitulo }}</p>
  @endif

  @if ($botao && $botao['texto'] && $botao['url'])
    <a href="{{ $botao['url'] }}" class="hero-section__botao">
      {{ $botao['texto'] }}
    </a>
  @endif
</div>

@unless ($block->preview)
  </section>
@endunless
```

---

## ✅ Checklist para Criar um Novo Bloco

- [ ] Criar arquivo PHP em `app/Blocks/{NomeDoBloco}.php`
- [ ] Definir `$name` em kebab-case
- [ ] Implementar método `fields()` com campos ACF
- [ ] Implementar método `with()` com dados para o template
- [ ] Criar template Blade em `resources/views/blocks/{nome-do-bloco}.blade.php` (se necessário)
- [ ] Criar CSS em `resources/styles/blocks/{nome-do-bloco}.css` (se necessário)
- [ ] Criar JS em `resources/scripts/blocks/{nome-do-bloco}.js` (se necessário)
- [ ] Testar o bloco no editor do WordPress
- [ ] Verificar se os assets são carregados corretamente no frontend

---

## 🔍 Troubleshooting

### O bloco não aparece no editor

- Verifique se o arquivo está em `app/Blocks/` com extensão `.php`
- Verifique se a classe estende `Block` corretamente
- Verifique se o namespace está correto: `namespace App\Blocks;`
- Limpe o cache do WordPress

### CSS/JS não estão sendo carregados

- Verifique se os arquivos estão nos diretórios corretos:
  - CSS: `resources/styles/blocks/{nome-do-bloco}.css`
  - JS: `resources/scripts/blocks/{nome-do-bloco}.js`
- Verifique se o nome do arquivo corresponde ao `$name` do bloco
- Execute `npm run build` ou `npm run dev` para compilar os assets
- Verifique o console do navegador para erros

### Template não está sendo renderizado

- Verifique se o template está em `resources/views/blocks/{nome-do-bloco}.blade.php`
- Verifique se o nome do template corresponde ao `$name` do bloco
- Verifique se as variáveis no template correspondem às definidas em `with()`

---

## 📖 Referências

- [ACF Composer Documentation](https://github.com/log1x/acf-composer)
- [WordPress Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [Sage Documentation](https://roots.io/sage/)
- [Bud.js Documentation](https://bud.js.org/)

---
