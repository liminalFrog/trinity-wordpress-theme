<?php
/**
 * Trinity Theme Functions
 * 
 * @package Trinity
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme constants
define('TRINITY_VERSION', '1.0.0');
define('TRINITY_URI', get_template_directory_uri());
define('TRINITY_DIR', get_template_directory());

// Include custom blocks
require_once TRINITY_DIR . '/blocks/blocks.php';

/**
 * Add custom block category
 */
function trinity_block_categories($categories, $post) {
    return array_merge(
        $categories,
        [
            [
                'slug' => 'trinity-blocks',
                'title' => esc_html__('Trinity Blocks', 'trinity'),
                'icon' => 'layout',
            ],
        ]
    );
}
add_filter('block_categories_all', 'trinity_block_categories', 10, 2);

/**
 * Enqueue block editor scripts
 */
function trinity_enqueue_block_editor_assets() {
    wp_enqueue_script(
        'trinity-blocks',
        TRINITY_URI . '/assets/dist/blocks.min.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components'],
        TRINITY_VERSION . '-' . time(), // Force cache refresh
        true
    );
    
    wp_enqueue_style(
        'trinity-blocks-editor',
        TRINITY_URI . '/assets/css/blocks-editor.css',
        array(),
        TRINITY_VERSION
    );
}
add_action('enqueue_block_editor_assets', 'trinity_enqueue_block_editor_assets');

/**
 * Theme setup
 */
function trinity_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('custom-background');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ]);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    
    // Register navigation menus
    register_nav_menus([
        'primary' => esc_html__('Primary Menu', 'trinity'),
        'footer' => esc_html__('Footer Menu', 'trinity'),
    ]);
    
    // Add image sizes
    add_image_size('trinity-hero', 1920, 1080, true);
    add_image_size('trinity-card', 400, 250, true);
    
    // Load text domain
    load_theme_textdomain('trinity', TRINITY_DIR . '/languages');
}
add_action('after_setup_theme', 'trinity_setup');

/**
 * Enqueue scripts and styles
 */
function trinity_scripts() {
    // Bootstrap CSS
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', [], '5.3.0');
    
    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', [], '6.4.0');
    
    // Theme styles
    wp_enqueue_style('trinity-style', get_stylesheet_uri(), ['bootstrap', 'font-awesome'], TRINITY_VERSION);
    
    // Bootstrap JS
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', [], '5.3.0', true);
    
    // Theme scripts
    wp_enqueue_script('trinity-script', TRINITY_URI . '/assets/js/theme.js', ['jquery', 'bootstrap'], TRINITY_VERSION, true);
    
    // Localize script
    wp_localize_script('trinity-script', 'trinity_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('trinity_nonce'),
        'theme_mode' => get_theme_mod('trinity_default_mode', 'light')
    ]);
    
    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'trinity_scripts');

/**
 * Register widget areas
 */
function trinity_widgets_init() {
    register_sidebar([
        'name' => esc_html__('Sidebar', 'trinity'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'trinity'),
        'before_widget' => '<section id="%1$s" class="widget %2$s mb-4">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title h5">',
        'after_title' => '</h2>',
    ]);
    
    // Footer widgets
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar([
            'name' => sprintf(esc_html__('Footer Widget %d', 'trinity'), $i),
            'id' => 'footer-' . $i,
            'description' => sprintf(esc_html__('Footer widget area %d', 'trinity'), $i),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-3">',
            'after_widget' => '</div>',
            'before_title' => '<h5 class="widget-title">',
            'after_title' => '</h5>',
        ]);
    }
}
add_action('widgets_init', 'trinity_widgets_init');

/**
 * Custom pagination
 */
function trinity_pagination() {
    global $wp_query;
    
    if ($wp_query->max_num_pages <= 1) {
        return;
    }
    
    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
    $max = intval($wp_query->max_num_pages);
    
    echo '<nav aria-label="Page navigation" class="mt-5">';
    echo '<ul class="pagination justify-content-center">';
    
    // Previous page
    if ($paged > 1) {
        echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($paged - 1) . '">' . esc_html__('Previous', 'trinity') . '</a></li>';
    }
    
    // Page numbers
    for ($i = 1; $i <= $max; $i++) {
        if ($i == $paged) {
            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($i) . '">' . $i . '</a></li>';
        }
    }
    
    // Next page
    if ($paged < $max) {
        echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($paged + 1) . '">' . esc_html__('Next', 'trinity') . '</a></li>';
    }
    
    echo '</ul>';
    echo '</nav>';
}

/**
 * Add custom meta boxes for pages
 */
function trinity_add_meta_boxes() {
    add_meta_box(
        'trinity_page_options',
        esc_html__('Trinity Page Options', 'trinity'),
        'trinity_page_options_callback',
        'page',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'trinity_add_meta_boxes');

/**
 * Page options meta box callback
 */
function trinity_page_options_callback($post) {
    wp_nonce_field('trinity_save_page_options', 'trinity_page_options_nonce');
    
    $hide_title = get_post_meta($post->ID, '_trinity_hide_title', true);
    $subtitle = get_post_meta($post->ID, '_trinity_subtitle', true);
    
    echo '<p>';
    echo '<label for="trinity_hide_title">';
    echo '<input type="checkbox" id="trinity_hide_title" name="trinity_hide_title" value="1" ' . checked($hide_title, '1', false) . '>';
    echo ' ' . esc_html__('Hide page title', 'trinity');
    echo '</label>';
    echo '</p>';
    
    echo '<p>';
    echo '<label for="trinity_subtitle">' . esc_html__('Subtitle:', 'trinity') . '</label><br>';
    echo '<input type="text" id="trinity_subtitle" name="trinity_subtitle" value="' . esc_attr($subtitle) . '" class="widefat">';
    echo '</p>';
}

/**
 * Save page options
 */
function trinity_save_page_options($post_id) {
    if (!isset($_POST['trinity_page_options_nonce']) || !wp_verify_nonce($_POST['trinity_page_options_nonce'], 'trinity_save_page_options')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_page', $post_id)) {
        return;
    }
    
    // Save hide title option
    if (isset($_POST['trinity_hide_title'])) {
        update_post_meta($post_id, '_trinity_hide_title', '1');
    } else {
        delete_post_meta($post_id, '_trinity_hide_title');
    }
    
    // Save subtitle
    if (isset($_POST['trinity_subtitle'])) {
        update_post_meta($post_id, '_trinity_subtitle', sanitize_text_field($_POST['trinity_subtitle']));
    }
}
add_action('save_post', 'trinity_save_page_options');

/**
 * Include required files
 */
require_once TRINITY_DIR . '/inc/nav-walker.php';
require_once TRINITY_DIR . '/inc/customizer.php';
require_once TRINITY_DIR . '/inc/blocks.php';
require_once TRINITY_DIR . '/inc/theme-updates.php';
require_once TRINITY_DIR . '/inc/template-functions.php';
?>
