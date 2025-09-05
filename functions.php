<?php
/**
 * Trinity functions and definitions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include Bootstrap NavWalker
require_once get_template_directory() . '/inc/bootstrap-navwalker.php';

// Include Simplified Theme Updater
require_once get_template_directory() . '/inc/theme-updater-simple.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function trinity_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title.
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support('post-thumbnails');

    // This theme uses wp_nav_menu() in multiple locations.
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'trinity'),
        'footer-menu' => esc_html__('Footer Menu', 'trinity'),
    ));

    // Switch default core markup for search form, comment form, and comments
    // to output valid HTML5.
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for core custom logo.
    add_theme_support('custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ));

    // Add support for wide and full alignment
    add_theme_support('align-wide');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'trinity_setup');

/**
 * Add custom image styles for block editor
 */
function trinity_add_image_styles() {
    // Add custom image styles to the block editor
    add_theme_support('editor-styles');
    
    // Register custom image styles for use in the block editor
    register_block_style('core/image', array(
        'name'  => 'rounded-0',
        'label' => __('Sharp Corners', 'trinity'),
    ));
    
    register_block_style('core/image', array(
        'name'  => 'rounded-1',
        'label' => __('Small Rounded', 'trinity'),
    ));
    
    register_block_style('core/image', array(
        'name'  => 'rounded-2',
        'label' => __('Medium Rounded', 'trinity'),
    ));
    
    register_block_style('core/image', array(
        'name'  => 'rounded-3',
        'label' => __('Large Rounded', 'trinity'),
    ));
    
    register_block_style('core/image', array(
        'name'  => 'rounded-4',
        'label' => __('Extra Large Rounded', 'trinity'),
    ));
    
    register_block_style('core/image', array(
        'name'  => 'rounded-5',
        'label' => __('Maximum Rounded', 'trinity'),
    ));
    
    register_block_style('core/image', array(
        'name'  => 'rounded-circle',
        'label' => __('Circle', 'trinity'),
    ));
    
    register_block_style('core/image', array(
        'name'  => 'rounded-pill',
        'label' => __('Pill Shape', 'trinity'),
    ));
}
add_action('init', 'trinity_add_image_styles');

/**
 * Add meta box for page title visibility
 */
function trinity_add_page_title_meta_box() {
    add_meta_box(
        'trinity_page_title_options',
        __('Page Title Options', 'trinity'),
        'trinity_page_title_meta_box_callback',
        array('page', 'post'),
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'trinity_add_page_title_meta_box');

/**
 * Meta box callback function
 */
function trinity_page_title_meta_box_callback($post) {
    // Add nonce field for security
    wp_nonce_field('trinity_page_title_nonce_action', 'trinity_page_title_nonce');
    
    // Get current value
    $hide_title = get_post_meta($post->ID, '_trinity_hide_page_title', true);
    
    // Output the field
    echo '<label for="trinity_hide_page_title">';
    echo '<input type="checkbox" id="trinity_hide_page_title" name="trinity_hide_page_title" value="1"' . checked($hide_title, 1, false) . ' />';
    echo ' ' . __('Hide page title', 'trinity');
    echo '</label>';
    echo '<p class="description">' . __('Check this box to hide the page title on this page.', 'trinity') . '</p>';
}

/**
 * Save meta box data
 */
function trinity_save_page_title_meta_box($post_id) {
    // Check if nonce is valid
    if (!isset($_POST['trinity_page_title_nonce']) || !wp_verify_nonce($_POST['trinity_page_title_nonce'], 'trinity_page_title_nonce_action')) {
        return;
    }
    
    // Check if user has permission to edit the post
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Don't save during autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Save the checkbox value
    if (isset($_POST['trinity_hide_page_title']) && $_POST['trinity_hide_page_title'] == '1') {
        update_post_meta($post_id, '_trinity_hide_page_title', 1);
    } else {
        delete_post_meta($post_id, '_trinity_hide_page_title');
    }
}
add_action('save_post', 'trinity_save_page_title_meta_box');

/**
 * Add body class when page title should be hidden
 */
function trinity_hide_title_body_class($classes) {
    if (is_singular()) {
        global $post;
        $hide_title = get_post_meta($post->ID, '_trinity_hide_page_title', true);
        if ($hide_title) {
            $classes[] = 'trinity-hide-page-title';
        }
    }
    return $classes;
}
add_filter('body_class', 'trinity_hide_title_body_class');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
function trinity_content_width() {
    $GLOBALS['content_width'] = apply_filters('trinity_content_width', 1200);
}
add_action('after_setup_theme', 'trinity_content_width', 0);

/**
 * Enqueue scripts and styles.
 */
function trinity_scripts() {
    // Enqueue main stylesheet (compiled from Sass)
    wp_enqueue_style('trinity-style', get_stylesheet_uri(), array(), '1.0.0');

    // Enqueue Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');

    // Enqueue Bootstrap JavaScript
    wp_enqueue_script(
        'bootstrap-js',
        get_template_directory_uri() . '/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
        array(),
        '5.3.3',
        true
    );

    // Enqueue theme navigation script
    wp_enqueue_script('trinity-navigation', get_template_directory_uri() . '/js/navigation.js', array('bootstrap-js'), '1.0.0', true);

    // Enqueue comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'trinity_scripts');

/**
 * Register widget area.
 */
function trinity_widgets_init() {
    // Main sidebar
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'trinity'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'trinity'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    // Top message bar
    register_sidebar(array(
        'name'          => esc_html__('Top Message Bar', 'trinity'),
        'id'            => 'top-message-bar',
        'description'   => esc_html__('Add widgets for announcements, promotions, or important notices at the top of the site.', 'trinity'),
        'before_widget' => '<div id="%1$s" class="message-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="message-title">',
        'after_title'   => '</span>',
    ));

    // Footer widget areas
    register_sidebar(array(
        'name'          => esc_html__('Footer Column 1', 'trinity'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets to the first footer column.', 'trinity'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="footer-title">',
        'after_title'   => '</h5>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Column 2', 'trinity'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets to the second footer column.', 'trinity'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="footer-title">',
        'after_title'   => '</h5>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Column 3', 'trinity'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets to the third footer column.', 'trinity'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="footer-title">',
        'after_title'   => '</h5>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Column 4', 'trinity'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Add widgets to the fourth footer column.', 'trinity'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="footer-title">',
        'after_title'   => '</h5>',
    ));
}
add_action('widgets_init', 'trinity_widgets_init');

/**
 * Custom template tags for this theme.
 */

/**
 * Prints HTML with meta information for the current post-date/time.
 */
function trinity_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if (get_the_time('U') !== get_the_modified_time('U')) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf($time_string,
        esc_attr(get_the_date(DATE_W3C)),
        esc_html(get_the_date()),
        esc_attr(get_the_modified_date(DATE_W3C)),
        esc_html(get_the_modified_date())
    );

    $posted_on = sprintf(
        esc_html_x('Posted on %s', 'post date', 'trinity'),
        '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span>';
}

/**
 * Prints HTML with meta information for the current author.
 */
function trinity_posted_by() {
    $byline = sprintf(
        esc_html_x('by %s', 'post author', 'trinity'),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
    );

    echo '<span class="byline"> ' . $byline . '</span>';
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function trinity_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'trinity_pingback_header');

/**
 * Customize the theme options.
 */
function trinity_customize_register($wp_customize) {
    // Add section for logo options
    $wp_customize->add_section('trinity_logo_section', array(
        'title'    => __('Logo Options', 'trinity'),
        'priority' => 30,
    ));

    // Light logo setting
    $wp_customize->add_setting('light_logo', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ));

    // Light logo control
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'light_logo', array(
        'label'    => __('Light Logo (for dark navbar)', 'trinity'),
        'section'  => 'trinity_logo_section',
        'settings' => 'light_logo',
        'description' => __('Upload a light version of your logo to be displayed when the navbar has a dark background on scroll.', 'trinity'),
    )));
}
add_action('customize_register', 'trinity_customize_register');

/**
 * Add Trinity-specific CSS for TMHero integration
 */
function trinity_tmhero_integration_css() {
    ?>
    <style type="text/css">
        /* Trinity theme TMHero integration */
        body.tmhero-hide-page-title .site-main,
        body.tmhero-hide-page-title #primary {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }
        
        /* Ensure hero blocks start immediately after fixed navbar */
        body.tmhero-hide-page-title .tmhero-block:first-child {
            margin-top: 0 !important;
        }
        
        /* Remove any spacing from Bootstrap/Trinity containers when hero is first element */
        body.tmhero-hide-page-title .container:first-child .tmhero-block:first-child,
        body.tmhero-hide-page-title .container-fluid:first-child .tmhero-block:first-child {
            margin-top: 0 !important;
        }
        
        /* Ensure full-width heroes extend edge to edge */
        body.tmhero-hide-page-title .tmhero-full-width {
            margin-left: calc(-50vw + 50%) !important;
            margin-right: calc(-50vw + 50%) !important;
            max-width: 100vw !important;
            width: 100vw !important;
        }
        
        /* Remove article padding when hero is present */
        body.tmhero-hide-page-title article .entry-header {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }
        
        /* Preserve navbar fixed behavior while removing content top spacing */
        .navbar-fixed-top,
        .fixed-top {
            position: fixed !important;
            top: 0 !important;
            z-index: 1030 !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'trinity_tmhero_integration_css');
