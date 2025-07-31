<?php
/**
 * My Custom Theme functions and definitions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include Bootstrap NavWalker
require_once get_template_directory() . '/inc/bootstrap-navwalker.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function mytheme_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title.
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support('post-thumbnails');

    // This theme uses wp_nav_menu() in multiple locations.
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'mytheme'),
        'footer-menu' => esc_html__('Footer Menu', 'mytheme'),
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
add_action('after_setup_theme', 'mytheme_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
function mytheme_content_width() {
    $GLOBALS['content_width'] = apply_filters('mytheme_content_width', 1200);
}
add_action('after_setup_theme', 'mytheme_content_width', 0);

/**
 * Enqueue scripts and styles.
 */
function mytheme_scripts() {
    // Enqueue main stylesheet (compiled from Sass)
    wp_enqueue_style('mytheme-style', get_stylesheet_uri(), array(), '1.0.0');

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
    wp_enqueue_script('mytheme-navigation', get_template_directory_uri() . '/js/navigation.js', array('bootstrap-js'), '1.0.0', true);

    // Enqueue comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'mytheme_scripts');

/**
 * Register widget area.
 */
function mytheme_widgets_init() {
    // Main sidebar
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'mytheme'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'mytheme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    // Top message bar
    register_sidebar(array(
        'name'          => esc_html__('Top Message Bar', 'mytheme'),
        'id'            => 'top-message-bar',
        'description'   => esc_html__('Add widgets for announcements, promotions, or important notices at the top of the site.', 'mytheme'),
        'before_widget' => '<div id="%1$s" class="message-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="message-title">',
        'after_title'   => '</span>',
    ));

    // Footer widget areas
    register_sidebar(array(
        'name'          => esc_html__('Footer Column 1', 'mytheme'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets to the first footer column.', 'mytheme'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="footer-title">',
        'after_title'   => '</h5>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Column 2', 'mytheme'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets to the second footer column.', 'mytheme'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="footer-title">',
        'after_title'   => '</h5>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Column 3', 'mytheme'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets to the third footer column.', 'mytheme'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="footer-title">',
        'after_title'   => '</h5>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Column 4', 'mytheme'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Add widgets to the fourth footer column.', 'mytheme'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="footer-title">',
        'after_title'   => '</h5>',
    ));
}
add_action('widgets_init', 'mytheme_widgets_init');

/**
 * Custom template tags for this theme.
 */

/**
 * Prints HTML with meta information for the current post-date/time.
 */
function mytheme_posted_on() {
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
        esc_html_x('Posted on %s', 'post date', 'mytheme'),
        '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span>';
}

/**
 * Prints HTML with meta information for the current author.
 */
function mytheme_posted_by() {
    $byline = sprintf(
        esc_html_x('by %s', 'post author', 'mytheme'),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
    );

    echo '<span class="byline"> ' . $byline . '</span>';
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function mytheme_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'mytheme_pingback_header');

/**
 * Customize the theme options.
 */
function mytheme_customize_register($wp_customize) {
    // Add section for logo options
    $wp_customize->add_section('mytheme_logo_section', array(
        'title'    => __('Logo Options', 'mytheme'),
        'priority' => 30,
    ));

    // Light logo setting
    $wp_customize->add_setting('light_logo', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ));

    // Light logo control
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'light_logo', array(
        'label'    => __('Light Logo (for dark navbar)', 'mytheme'),
        'section'  => 'mytheme_logo_section',
        'settings' => 'light_logo',
        'description' => __('Upload a light version of your logo to be displayed when the navbar has a dark background on scroll.', 'mytheme'),
    )));
}
add_action('customize_register', 'mytheme_customize_register');
