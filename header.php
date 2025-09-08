<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php if (get_post_meta(get_the_ID(), '_trinity_subtitle', true)) : ?>
        <meta name="description" content="<?php echo esc_attr(get_post_meta(get_the_ID(), '_trinity_subtitle', true)); ?>">
    <?php endif; ?>
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> data-theme="<?php echo esc_attr(get_theme_mod('trinity_default_mode', 'light')); ?>">
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to content', 'trinity'); ?></a>
    
    <!-- Loading Spinner -->
    <div id="trinity-loading" class="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden"><?php esc_html_e('Loading...', 'trinity'); ?></span>
        </div>
    </div>
    
    <?php if (get_theme_mod('trinity_announcement_bar_enabled', false)) : ?>
        <!-- Announcement Bar -->
        <div id="announcement-bar" class="announcement-bar" 
             style="background-color: <?php echo esc_attr(get_theme_mod('trinity_announcement_bg_color', '#007cba')); ?>; 
                    color: <?php echo esc_attr(get_theme_mod('trinity_announcement_text_color', '#ffffff')); ?>;">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center py-2">
                        <small><?php echo wp_kses_post(get_theme_mod('trinity_announcement_text', '')); ?></small>
                        <button type="button" class="btn-close btn-close-white ms-2" aria-label="<?php esc_attr_e('Close', 'trinity'); ?>" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Navigation -->
    <header id="masthead" class="site-header">
        <nav class="navbar navbar-expand-lg fixed-top trinity-navbar" id="trinity-navbar">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php
                    $light_logo = get_theme_mod('trinity_light_logo');
                    $dark_logo = get_theme_mod('trinity_dark_logo');
                    
                    if ($light_logo || $dark_logo) :
                        if ($light_logo) : ?>
                            <img src="<?php echo esc_url($light_logo); ?>" 
                                 alt="<?php bloginfo('name'); ?>" 
                                 class="logo logo-light" 
                                 height="40">
                        <?php endif;
                        
                        if ($dark_logo) : ?>
                            <img src="<?php echo esc_url($dark_logo); ?>" 
                                 alt="<?php bloginfo('name'); ?>" 
                                 class="logo logo-dark" 
                                 height="40">
                        <?php endif;
                    else : ?>
                        <span class="fw-bold"><?php bloginfo('name'); ?></span>
                    <?php endif; ?>
                </a>
                
                <!-- Mobile Toggle -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'trinity'); ?>">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Navigation Menu -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'menu_class' => 'navbar-nav ms-auto',
                        'container' => false,
                        'fallback_cb' => 'trinity_bootstrap_navwalker::fallback',
                        'walker' => new Trinity_Bootstrap_Navwalker(),
                    ]);
                    ?>
                </div>
            </div>
        </nav>
    </header>

    <div id="content" class="site-content">
        <?php
        // Page title and subtitle
        if (is_page() && !get_post_meta(get_the_ID(), '_trinity_hide_title', true)) :
            $subtitle = get_post_meta(get_the_ID(), '_trinity_subtitle', true);
        ?>
            <div class="page-header py-5 mt-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h1 class="page-title"><?php the_title(); ?></h1>
                            <?php if ($subtitle) : ?>
                                <p class="page-subtitle lead text-muted"><?php echo esc_html($subtitle); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif (!is_page()) : ?>
            <div class="spacer" style="height: 40px;"></div>
        <?php endif; ?>
