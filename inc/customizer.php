<?php
/**
 * Trinity Theme Customizer
 * 
 * @package Trinity
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 */
function trinity_customize_register($wp_customize) {
    
    // Add theme mode section
    $wp_customize->add_section('trinity_theme_mode', [
        'title' => esc_html__('Theme Mode', 'trinity'),
        'priority' => 30,
    ]);
    
    $wp_customize->add_setting('trinity_default_mode', [
        'default' => 'light',
        'sanitize_callback' => 'trinity_sanitize_select',
    ]);
    
    $wp_customize->add_control('trinity_default_mode', [
        'label' => esc_html__('Default Theme Mode', 'trinity'),
        'section' => 'trinity_theme_mode',
        'type' => 'select',
        'choices' => [
            'light' => esc_html__('Light Mode', 'trinity'),
            'dark' => esc_html__('Dark Mode', 'trinity'),
        ],
    ]);
    
    // Logo settings
    $wp_customize->add_section('trinity_logos', [
        'title' => esc_html__('Theme Logos', 'trinity'),
        'priority' => 40,
    ]);
    
    $wp_customize->add_setting('trinity_light_logo', [
        'sanitize_callback' => 'esc_url_raw',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'trinity_light_logo', [
        'label' => esc_html__('Light Mode Logo', 'trinity'),
        'section' => 'trinity_logos',
        'settings' => 'trinity_light_logo',
    ]));
    
    $wp_customize->add_setting('trinity_dark_logo', [
        'sanitize_callback' => 'esc_url_raw',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'trinity_dark_logo', [
        'label' => esc_html__('Dark Mode Logo', 'trinity'),
        'section' => 'trinity_logos',
        'settings' => 'trinity_dark_logo',
    ]));
    
    // Navbar background images
    $wp_customize->add_section('trinity_navbar_bg', [
        'title' => esc_html__('Navbar Backgrounds', 'trinity'),
        'priority' => 50,
    ]);
    
    $wp_customize->add_setting('trinity_navbar_light_bg', [
        'sanitize_callback' => 'esc_url_raw',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'trinity_navbar_light_bg', [
        'label' => esc_html__('Light Mode Navbar Background Pattern', 'trinity'),
        'section' => 'trinity_navbar_bg',
        'settings' => 'trinity_navbar_light_bg',
    ]));
    
    $wp_customize->add_setting('trinity_navbar_dark_bg', [
        'sanitize_callback' => 'esc_url_raw',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'trinity_navbar_dark_bg', [
        'label' => esc_html__('Dark Mode Navbar Background Pattern', 'trinity'),
        'section' => 'trinity_navbar_bg',
        'settings' => 'trinity_navbar_dark_bg',
    ]));
    
    // Announcement bar
    $wp_customize->add_section('trinity_announcement', [
        'title' => esc_html__('Announcement Bar', 'trinity'),
        'priority' => 60,
    ]);
    
    $wp_customize->add_setting('trinity_announcement_bar_enabled', [
        'default' => false,
        'sanitize_callback' => 'trinity_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('trinity_announcement_bar_enabled', [
        'label' => esc_html__('Enable Announcement Bar', 'trinity'),
        'section' => 'trinity_announcement',
        'type' => 'checkbox',
    ]);
    
    $wp_customize->add_setting('trinity_announcement_text', [
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    
    $wp_customize->add_control('trinity_announcement_text', [
        'label' => esc_html__('Announcement Text', 'trinity'),
        'section' => 'trinity_announcement',
        'type' => 'textarea',
    ]);
    
    $wp_customize->add_setting('trinity_announcement_bg_color', [
        'default' => '#007cba',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'trinity_announcement_bg_color', [
        'label' => esc_html__('Background Color', 'trinity'),
        'section' => 'trinity_announcement',
        'settings' => 'trinity_announcement_bg_color',
    ]));
    
    $wp_customize->add_setting('trinity_announcement_text_color', [
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'trinity_announcement_text_color', [
        'label' => esc_html__('Text Color', 'trinity'),
        'section' => 'trinity_announcement',
        'settings' => 'trinity_announcement_text_color',
    ]));
    
    // GitHub updates
    $wp_customize->add_section('trinity_updates', [
        'title' => esc_html__('Theme Updates', 'trinity'),
        'priority' => 70,
    ]);
    
    $wp_customize->add_setting('trinity_github_repo', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    
    $wp_customize->add_control('trinity_github_repo', [
        'label' => esc_html__('GitHub Repository URL', 'trinity'),
        'section' => 'trinity_updates',
        'type' => 'url',
        'description' => esc_html__('Enter the full GitHub repository URL for theme updates.', 'trinity'),
    ]);
}
add_action('customize_register', 'trinity_customize_register');

/**
 * Sanitize select fields
 */
function trinity_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize checkbox fields
 */
function trinity_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Bind JS handlers to instantly live-preview changes.
 */
function trinity_customize_preview_js() {
    wp_enqueue_script('trinity-customizer', TRINITY_URI . '/assets/js/customizer.js', ['customize-preview'], TRINITY_VERSION, true);
}
add_action('customize_preview_init', 'trinity_customize_preview_js');
?>
