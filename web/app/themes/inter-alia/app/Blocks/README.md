# Sistema de Blocos ACF com Carregamento Condicional

Este sistema permite criar blocos ACF com carregamento condicional de CSS e JS, garantindo performance máxima ao carregar apenas os assets dos blocos utilizados na página.

## Estrutura

```
app/Blocks/
├── BlockManager.php       # Gerenciador de blocos (detecção e enqueue automático)
├── ExampleBlock.php       # Exemplo de bloco
└── SeuBloco.php           # Seus blocos personalizados

resources/
├── styles/blocks/        # CSS específico por bloco
│   └── seu-bloco.css
├── scripts/blocks/       # JS específico por bloco
│   └── seu-bloco.js
└── views/blocks/         # Templates Blade dos blocos
    └── seu-bloco.blade.php
```

## Como Criar um Novo Bloco

### 1. Criar a Classe do Bloco

Crie um arquivo em `app/Blocks/SeuBloco.php`:

```php
<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;

class SeuBloco extends Block
{
    public $name = 'seu-bloco';
    public $title = 'Seu Bloco';
    public $description = 'Descrição do seu bloco';
    public $category = 'common';
    public $icon = 'star-filled';
    public $keywords = ['seu', 'bloco'];

    public function with()
    {
        return [
            'titulo' => get_field('titulo'),
            'conteudo' => get_field('conteudo'),
        ];
    }

    // Opcional: método assets() se precisar de lógica customizada
    public function assets(array $block): void
    {
        // Assets são carregados automaticamente pelo BlockManager
        // Sobrescreva apenas se precisar de lógica específica
    }
}
```

### 2. Criar Assets (Opcional)

Se o bloco precisar de CSS ou JS específico:

**CSS:** `resources/styles/blocks/seu-bloco.css`
```css
@tailwind base;
@tailwind components;
@tailwind utilities;

.seu-bloco {
  /* Estilos específicos */
}
```

**JS:** `resources/scripts/blocks/seu-bloco.js`
```javascript
import domReady from '@roots/sage/client/dom-ready';

domReady(() => {
  const blocos = document.querySelectorAll('.seu-bloco');
  // Inicialização do bloco
});
```

### 3. Criar Template (Opcional)

Crie `resources/views/blocks/seu-bloco.blade.php` se precisar de template customizado:

```blade
<div class="seu-bloco">
  <h2>{{ $titulo }}</h2>
  <div>{{ $conteudo }}</div>
</div>
```

## Como Funciona

1. **Detecção Automática**: O sistema detecta automaticamente quais blocos estão sendo usados na página atual
2. **Carregamento Condicional**: Apenas os CSS/JS dos blocos utilizados são carregados
3. **Compilação Automática**: O Bud.js compila automaticamente os assets de cada bloco
4. **Tailwind Otimizado**: O Tailwind escaneia os blocos e inclui apenas as classes utilizadas

## Nomenclatura

- **Nome do arquivo PHP**: `SeuBloco.php` (PascalCase)
- **Nome do bloco**: `seu-bloco` (kebab-case)
- **Assets CSS**: `resources/styles/blocks/seu-bloco.css`
- **Assets JS**: `resources/scripts/blocks/seu-bloco.js`
- **Template**: `resources/views/blocks/seu-bloco.blade.php`

## Performance

- ✅ CSS e JS carregados apenas quando necessário
- ✅ Tailwind purged automaticamente
- ✅ Bundles separados por bloco
- ✅ Sem impacto em blocos não utilizados

## Notas

- Blocos sem arquivos CSS/JS não geram bundles vazios
- O sistema funciona automaticamente após criar a classe do bloco
- Use Tailwind classes diretamente nos templates para melhor performance
- Assets globais continuam em `app.css` e `app.js`

