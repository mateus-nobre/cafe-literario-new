<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class SliderHero extends Block
{
    public $name = 'Slider Hero';
    public $description = 'Bloco de slider para o topo do website com destaques da página.';
    public $category = 'theme';
    public $icon = 'editor-ul';
    public $mode = 'preview';
    public $spacing = [
        'padding' => null,
        'margin' => null,
    ];

    /**
     * The supported block features.
     *
     * @var array
     */
    public $supports = [
        'align' => true,
        'align_text' => false,
        'align_content' => false,
        'full_height' => false,
        'anchor' => false,
        'mode' => true,
        'multiple' => true,
        'jsx' => true,
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


    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        return [
            'items' => $this->items(),
        ];
    }

    /**
     * The block field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('slider_hero');

        $fields
            ->addRepeater('items')
                ->addPostObject('post', [
                    'label' => 'Post',
                    'instructions' => 'Selecione um post para exibir no slider',
                    'required' => true,
                    'return_format' => 'object',
                    'multiple' => false,
                ])
            ->endRepeater();

        return $fields->build();
    }

    /**
     * Retrieve the items with post data.
     *
     * @return array
     */
    public function items()
    {
        $items = get_field('items') ?: [];

        if (empty($items)) {
            return $this->example['items'] ?? [];
        }

        // Process each item to get full post data
        $processed_items = [];
        foreach ($items as $item) {
            if (empty($item['post']) || !is_object($item['post'])) {
                continue;
            }

            $post = $item['post'];

            $processed_items[] = [
                'post' => $post,
                'id' => $post->ID,
                'title' => get_the_title($post->ID),
                'excerpt' => get_the_excerpt($post->ID),
                'content' => get_the_content(null, false, $post->ID),
                'url' => get_permalink($post->ID),
                'date' => get_the_date('', $post->ID),
                'featured_image' => get_the_post_thumbnail_url($post->ID, 'full'),
            ];
        }

        return $processed_items;
    }
}

