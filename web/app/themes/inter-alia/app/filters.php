<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Adiciona classe 'has-child' aos itens de menu que têm submenus
 */
\add_filter('nav_menu_css_class', function ($classes, $item) {
    if (in_array('menu-item-has-children', $classes)) {
        $classes[] = 'has-child';
    }
    return $classes;
}, 10, 2);
