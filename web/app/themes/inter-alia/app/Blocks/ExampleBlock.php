<?php

/**
 * Example Block
 *
 * This is an example of how to create a new ACF block.
 * Copy this file and modify it for your own blocks.
 */

namespace App\Blocks;

use Log1x\AcfComposer\Block;

class ExampleBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'example-block';

    /**
     * The block title.
     *
     * @var string
     */
    public $title = 'Example Block';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'An example ACF block.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'common';

    /**
     * The block icon.
     *
     * @var string|array
     */
    public $icon = 'star-filled';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['example', 'block'];

    /**
     * The block post type allow list.
     *
     * @var array
     */
    public $post_types = [];

    /**
     * The parent block type allow list.
     *
     * @var array
     */
    public $parent = [];

    /**
     * The default block mode.
     *
     * @var string
     */
    public $mode = 'preview';

    /**
     * The default block alignment.
     *
     * @var string
     */
    public $align = '';

    /**
     * The default block text alignment.
     *
     * @var string
     */
    public $align_text = '';

    /**
     * The default block content alignment.
     *
     * @var string
     */
    public $align_content = '';

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
    ];

    /**
     * The block styles.
     *
     * @var array
     */
    public $styles = [];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'title' => get_field('title'),
            'content' => get_field('content'),
        ];
    }

    /**
     * Assets to be enqueued when rendering the block.
     *
     * @param array $block
     * @return void
     */
    public function assets(array $block): void
    {
        // Assets are automatically loaded by BlockManager
        // based on block usage detection.
        // This method can be overridden if custom enqueue logic is needed.
    }
}

