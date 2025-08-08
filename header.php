<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    
    <!-- Top Message Bar -->
    <?php if (is_active_sidebar('top-message-bar')) : ?>
        <div class="top-message-bar">
            <div class="container-fluid">
                <div class="message-content">
                    <?php dynamic_sidebar('top-message-bar'); ?>
                    <button type="button" class="message-close" aria-label="Close message">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <nav class="navbar navbar-expand-lg navbar-dark" id="site-navigation">
        <div class="container">
            <!-- Brand/Logo -->
            <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                <?php if (has_custom_logo()) : ?>
                    <?php 
                    // Get the custom logo
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                    
                    // Check for light logo option
                    $light_logo_id = get_theme_mod('light_logo');
                    $light_logo = $light_logo_id ? wp_get_attachment_image_src($light_logo_id, 'full') : null;
                    
                    if ($light_logo) : ?>
                        <!-- Dual logo setup -->
                        <div class="logo-container">
                            <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="logo-normal">
                            <img src="<?php echo esc_url($light_logo[0]); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="logo-light">
                        </div>
                    <?php else : ?>
                        <!-- Single logo setup -->
                        <?php the_custom_logo(); ?>
                    <?php endif; ?>
                <?php else : ?>
                    <?php if (is_front_page() && is_home()) : ?>
                        <span class="h4 mb-0"><?php bloginfo('name'); ?></span>
                    <?php else : ?>
                        <span class="h4 mb-0"><?php bloginfo('name'); ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </a>

            <!-- Mobile toggle button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php
                wp_nav_menu(array(
                    'theme_location'  => 'primary',
                    'depth'           => 2,
                    'container'       => false,
                    'menu_class'      => 'navbar-nav ms-auto',
                    'menu_id'         => 'primary-menu',
                    'fallback_cb'     => 'trinity_bootstrap_navwalker_fallback',
                    'walker'          => new trinity_bootstrap_navwalker(),
                ));
                ?>
            </div>
        </div>
    </nav>

    <?php
    // Display site description below navbar if it exists
    $description = get_bloginfo('description', 'display');
    if ($description || is_customize_preview()) :
    ?>
        <div class="site-description-bar bg-light py-2">
            <div class="container">
                <p class="text-muted text-center mb-0 small"><?php echo $description; ?></p>
            </div>
        </div>
    <?php endif; ?>
