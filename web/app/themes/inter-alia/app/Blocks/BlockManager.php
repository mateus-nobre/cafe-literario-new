<?php

/**
 * Block Manager
 *
 * Manages block registration and conditional asset loading
 */

namespace App\Blocks;

use function Roots\bundle;

class BlockManager
{
    /**
     * Registered blocks
     *
     * @var array
     */
    protected $blocks = [];

    /**
     * Blocks used on current page
     *
     * @var array
     */
    protected $usedBlocks = [];

    /**
     * Initialize block manager
     *
     * @return void
     */
    public function init()
    {
        // Hook into block rendering to detect used blocks
        add_filter('render_block', [$this, 'detectBlock'], 10, 2);

        // Enqueue assets for used blocks
        add_action('wp_enqueue_scripts', [$this, 'enqueueBlockAssets'], 20);

        // Also check in the_content for ACF blocks
        add_filter('the_content', [$this, 'detectBlocksInContent'], 5);
    }

    /**
     * Detect block usage during rendering
     *
     * @param string $blockContent
     * @param array $block
     * @return string
     */
    public function detectBlock($blockContent, $block)
    {
        if (!is_array($block)) {
            return $blockContent;
        }

        if (!empty($block['blockName']) && strpos($block['blockName'], 'acf/') === 0) {
            $this->usedBlocks[$block['blockName']] = true;
        }

        return $blockContent;
    }

    /**
     * Detect blocks in post content before rendering
     *
     * @param string $content
     * @return string
     */
    public function detectBlocksInContent($content)
    {
        global $post;

        if (!$post || empty($content) || !has_blocks($content)) {
            return $content;
        }

        try {
            $blocks = parse_blocks($content);
            if (is_array($blocks) && !empty($blocks)) {
                $this->extractBlockNames($blocks);
            }
        } catch (\Exception $e) {
            // Silently fail if block parsing fails
            return $content;
        }

        return $content;
    }

    /**
     * Recursively extract block names
     *
     * @param array $blocks
     * @return void
     */
    protected function extractBlockNames($blocks)
    {
        if (!is_array($blocks)) {
            return;
        }

        foreach ($blocks as $block) {
            if (!is_array($block)) {
                continue;
            }

            if (!empty($block['blockName']) && strpos($block['blockName'], 'acf/') === 0) {
                $this->usedBlocks[$block['blockName']] = true;
            }

            if (!empty($block['innerBlocks']) && is_array($block['innerBlocks'])) {
                $this->extractBlockNames($block['innerBlocks']);
            }
        }
    }

    /**
     * Check ACF flexible content fields
     *
     * @return void
     */
    protected function checkAcfFields()
    {
        global $post;

        if (!$post || !function_exists('get_field')) {
            return;
        }

        $fields = get_fields($post->ID);

        if (!$fields) {
            return;
        }

        $this->searchAcfFields($fields);
    }

    /**
     * Recursively search ACF fields for layouts
     *
     * @param array $fields
     * @return void
     */
    protected function searchAcfFields($fields)
    {
        if (!is_array($fields)) {
            return;
        }

        foreach ($fields as $key => $value) {
            if (is_array($value)) {
                // Check for flexible content layout
                if (isset($value['acf_fc_layout']) && is_string($value['acf_fc_layout'])) {
                    $layoutName = $value['acf_fc_layout'];
                    $blockName = 'acf/' . str_replace('_', '-', $layoutName);
                    $this->usedBlocks[$blockName] = true;
                }

                // Recursively search nested fields
                $this->searchAcfFields($value);
            }
        }
    }

    /**
     * Enqueue assets for blocks used on current page
     *
     * @return void
     */
    public function enqueueBlockAssets()
    {
        // Only run on frontend
        if (is_admin()) {
            return;
        }

        // Also check ACF fields
        try {
            $this->checkAcfFields();
        } catch (\Exception $e) {
            // Silently fail if ACF check fails
        }

        if (empty($this->usedBlocks)) {
            return;
        }

        foreach ($this->usedBlocks as $blockName => $used) {
            if (!$used || !is_string($blockName)) {
                continue;
            }

            // Get block slug (remove 'acf/' prefix)
            $blockSlug = str_replace('acf/', '', $blockName);

            if (empty($blockSlug)) {
                continue;
            }

            // Enqueue block-specific bundle (contains both CSS and JS if they exist)
            $bundleHandle = "block-{$blockSlug}";

            try {
                $bundle = bundle($bundleHandle);
                if ($bundle) {
                    $bundle->enqueue();
                }
            } catch (\Exception $e) {
                // Bundle doesn't exist, skip silently
                // This is normal for blocks without custom assets
            } catch (\Error $e) {
                // Also catch fatal errors
            } catch (\Throwable $e) {
                // Catch any other errors
            }
        }
    }

    /**
     * Register a block
     *
     * @param string $blockName
     * @param array $config
     * @return void
     */
    public function registerBlock($blockName, $config = [])
    {
        $this->blocks[$blockName] = $config;
    }

    /**
     * Get used blocks
     *
     * @return array
     */
    public function getUsedBlocks()
    {
        return array_keys($this->usedBlocks);
    }
}

