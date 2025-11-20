<?php

namespace App\Walkers;

use Walker_Nav_Menu;

/**
 * Custom Walker para adicionar chevron aos itens de menu com submenu
 */
class MenuWalker extends Walker_Nav_Menu
{
    /**
     * Inicia o elemento do menu
     *
     * @param string $output
     * @param \WP_Post $item
     * @param int $depth
     * @param \stdClass $args
     * @param int $id
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        // Adiciona classes customizadas ao link
        $link_class = '';
        if (isset($args->link_class)) {
            $link_class = ' class="' . esc_attr($args->link_class) . '"';
        }

        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . $link_class . '>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        if (in_array('menu-item-has-children', $classes)) {
            $chevron_path = locate_template('resources/images/chevron.svg');
            if ($chevron_path && file_exists($chevron_path)) {
                $chevron_svg = file_get_contents($chevron_path);
                $chevron_svg = preg_replace('/class="[^"]*"/', 'class="chevron-icon" style="width: 1rem; height: 1rem; margin-left: 0.5rem; display: inline-block; vertical-align: middle;"', $chevron_svg);
                $item_output .= $chevron_svg;
            }
        }

        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

