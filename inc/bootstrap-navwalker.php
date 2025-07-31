<?php
/**
 * Bootstrap 5 NavWalker for WordPress
 * 
 * This class extends the Walker_Nav_Menu class to output Bootstrap 5 compatible navigation menus.
 */

if (!defined('ABSPATH')) {
    exit;
}

class mytheme_bootstrap_navwalker extends Walker_Nav_Menu {

    /**
     * Start the list before the elements are added.
     */
    public function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
    }

    /**
     * End the list after the elements are added.
     */
    public function end_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    /**
     * Start the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'nav-item';

        // Check if the item has children
        $has_children = in_array('menu-item-has-children', $classes);

        if ($has_children && $depth === 0) {
            $classes[] = 'dropdown';
        }

        if ($depth > 0) {
            $classes = array_filter($classes, function($class) {
                return $class !== 'nav-item';
            });
        }

        // Preserve WordPress current menu item classes
        $current_classes = array('current-menu-item', 'current_page_item', 'current-page-ancestor', 'current-menu-ancestor');
        $is_current = !empty(array_intersect($classes, $current_classes));

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        // Link attributes
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        // Build link classes
        $link_classes = array('nav-link');
        
        if ($has_children && $depth === 0) {
            $link_classes[] = 'dropdown-toggle';
            $attributes .= ' data-bs-toggle="dropdown" aria-expanded="false"';
        }

        if ($depth > 0) {
            $link_classes = array('dropdown-item');
        }

        // Add current class to the link if this is the current item
        if ($is_current) {
            $link_classes[] = 'active';
        }

        $link_class = ' class="' . implode(' ', $link_classes) . '"';

        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . $link_class . '>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    /**
     * End the element output.
     */
    public function end_el(&$output, $item, $depth = 0, $args = array()) {
        $output .= "</li>\n";
    }
}

/**
 * Fallback function for when no menu is assigned
 */
function mytheme_bootstrap_navwalker_fallback($args) {
    if (!current_user_can('manage_options')) {
        return;
    }

    $fallback_output = '<ul class="navbar-nav ms-auto">';
    $fallback_output .= '<li class="nav-item">';
    $fallback_output .= '<a class="nav-link" href="' . esc_url(home_url('/')) . '">Home</a>';
    $fallback_output .= '</li>';
    
    if (current_user_can('manage_options')) {
        $fallback_output .= '<li class="nav-item">';
        $fallback_output .= '<a class="nav-link" href="' . esc_url(admin_url('nav-menus.php')) . '">Add Menu</a>';
        $fallback_output .= '</li>';
    }
    
    $fallback_output .= '</ul>';
    
    echo $fallback_output;
}
