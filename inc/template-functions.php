<?php
/**
 * Trinity Template Functions
 * 
 * @package Trinity
 */

/**
 * Get page subtitle
 */
function trinity_get_page_subtitle($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    return get_post_meta($post_id, '_trinity_subtitle', true);
}

/**
 * Check if page title should be hidden
 */
function trinity_is_page_title_hidden($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    return get_post_meta($post_id, '_trinity_hide_title', true) === '1';
}

/**
 * Get theme logo based on current mode
 */
function trinity_get_logo($mode = 'auto') {
    if ($mode === 'auto') {
        $mode = get_theme_mod('trinity_default_mode', 'light');
    }
    
    if ($mode === 'dark') {
        return get_theme_mod('trinity_dark_logo', '');
    } else {
        return get_theme_mod('trinity_light_logo', '');
    }
}

/**
 * Generate loading placeholder for images
 */
function trinity_image_placeholder($width = 400, $height = 250, $class = 'img-fluid rounded') {
    return sprintf(
        '<div class="placeholder-glow"><div class="placeholder %s" style="width: %dpx; height: %dpx;"></div></div>',
        esc_attr($class),
        intval($width),
        intval($height)
    );
}

/**
 * Generate button with Trinity styling
 */
function trinity_button($args = []) {
    $defaults = [
        'text' => 'Click Me',
        'url' => '#',
        'variant' => 'primary',
        'size' => '',
        'outline' => false,
        'stretched' => false,
        'border_radius' => '',
        'class' => '',
        'attributes' => []
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    $classes = ['btn'];
    
    if ($args['outline']) {
        $classes[] = 'btn-outline-' . esc_attr($args['variant']);
    } else {
        $classes[] = 'btn-' . esc_attr($args['variant']);
    }
    
    if (!empty($args['size'])) {
        $classes[] = 'btn-' . esc_attr($args['size']);
    }
    
    if ($args['stretched']) {
        $classes[] = 'stretched-link';
    }
    
    if (!empty($args['class'])) {
        $classes[] = $args['class'];
    }
    
    $style = '';
    if (!empty($args['border_radius'])) {
        $style = 'border-radius: ' . intval($args['border_radius']) . 'px;';
    }
    
    $attributes = '';
    if (!empty($args['attributes'])) {
        foreach ($args['attributes'] as $attr => $value) {
            $attributes .= ' ' . esc_attr($attr) . '="' . esc_attr($value) . '"';
        }
    }
    
    return sprintf(
        '<a href="%s" class="%s" style="%s"%s>%s</a>',
        esc_url($args['url']),
        esc_attr(implode(' ', $classes)),
        esc_attr($style),
        $attributes,
        esc_html($args['text'])
    );
}

/**
 * Generate responsive image with lazy loading
 */
function trinity_responsive_image($image_id, $size = 'large', $args = []) {
    $defaults = [
        'class' => 'img-fluid rounded',
        'lazy' => true,
        'placeholder' => true,
        'alt' => ''
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    if (!$image_id) {
        if ($args['placeholder']) {
            return trinity_image_placeholder();
        }
        return '';
    }
    
    $image = wp_get_attachment_image_src($image_id, $size);
    if (!$image) {
        if ($args['placeholder']) {
            return trinity_image_placeholder();
        }
        return '';
    }
    
    $alt = !empty($args['alt']) ? $args['alt'] : get_post_meta($image_id, '_wp_attachment_image_alt', true);
    
    $attributes = [
        'class' => $args['class'],
        'alt' => $alt
    ];
    
    if ($args['lazy']) {
        $attributes['loading'] = 'lazy';
        $attributes['data-src'] = $image[0];
        $attributes['src'] = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="' . $image[1] . '" height="' . $image[2] . '"><rect width="100%" height="100%" fill="#f8f9fa"/></svg>');
    } else {
        $attributes['src'] = $image[0];
    }
    
    $attr_string = '';
    foreach ($attributes as $attr => $value) {
        $attr_string .= ' ' . esc_attr($attr) . '="' . esc_attr($value) . '"';
    }
    
    return '<img' . $attr_string . '>';
}

/**
 * Check if announcement bar should be shown
 */
function trinity_show_announcement_bar() {
    if (!get_theme_mod('trinity_announcement_bar_enabled', false)) {
        return false;
    }
    
    $announcement_text = get_theme_mod('trinity_announcement_text', '');
    if (empty($announcement_text)) {
        return false;
    }
    
    // Check if user has dismissed the announcement (using localStorage on frontend)
    return true;
}

/**
 * Get navbar background image based on mode
 */
function trinity_get_navbar_bg($mode = 'light') {
    if ($mode === 'dark') {
        return get_theme_mod('trinity_navbar_dark_bg', '');
    } else {
        return get_theme_mod('trinity_navbar_light_bg', '');
    }
}

/**
 * Generate CSS variables for theme customization
 */
function trinity_css_variables() {
    $light_logo = get_theme_mod('trinity_light_logo', '');
    $dark_logo = get_theme_mod('trinity_dark_logo', '');
    $navbar_light_bg = get_theme_mod('trinity_navbar_light_bg', '');
    $navbar_dark_bg = get_theme_mod('trinity_navbar_dark_bg', '');
    
    $css = ':root {';
    
    if ($light_logo) {
        $css .= '--trinity-logo-light: url(' . esc_url($light_logo) . ');';
    }
    
    if ($dark_logo) {
        $css .= '--trinity-logo-dark: url(' . esc_url($dark_logo) . ');';
    }
    
    if ($navbar_light_bg) {
        $css .= '--trinity-navbar-bg-light: url(' . esc_url($navbar_light_bg) . ');';
    }
    
    if ($navbar_dark_bg) {
        $css .= '--trinity-navbar-bg-dark: url(' . esc_url($navbar_dark_bg) . ');';
    }
    
    $css .= '}';
    
    return $css;
}

/**
 * Add CSS variables to head
 */
function trinity_add_css_variables() {
    echo '<style type="text/css">' . trinity_css_variables() . '</style>';
}
add_action('wp_head', 'trinity_add_css_variables');

/**
 * Body classes for theme modes
 */
function trinity_body_classes($classes) {
    $default_mode = get_theme_mod('trinity_default_mode', 'light');
    $classes[] = 'theme-' . $default_mode;
    
    return $classes;
}
add_filter('body_class', 'trinity_body_classes');

/**
 * Custom comment callback
 */
function trinity_comment_callback($comment, $args, $depth) {
    $tag = ('div' === $args['style']) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent', $comment); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <div class="comment-meta d-flex align-items-center mb-3">
                <div class="comment-author vcard d-flex align-items-center">
                    <?php if (0 != $args['avatar_size']) echo get_avatar($comment, $args['avatar_size'], '', '', ['class' => 'rounded-circle me-3']); ?>
                    <div>
                        <?php printf('<b class="fn">%s</b>', get_comment_author_link($comment)); ?>
                        <div class="comment-metadata text-muted small">
                            <a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>">
                                <time datetime="<?php comment_time('c'); ?>">
                                    <?php printf(esc_html__('%1$s at %2$s', 'trinity'), get_comment_date('', $comment), get_comment_time()); ?>
                                </time>
                            </a>
                            <?php edit_comment_link(esc_html__('(Edit)', 'trinity'), ' <span class="edit-link">', '</span>'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ('0' == $comment->comment_approved) : ?>
                <div class="comment-awaiting-moderation alert alert-warning">
                    <?php esc_html_e('Your comment is awaiting moderation.', 'trinity'); ?>
                </div>
            <?php endif; ?>

            <div class="comment-content">
                <?php comment_text(); ?>
            </div>

            <?php
            comment_reply_link(array_merge($args, [
                'add_below' => 'div-comment',
                'depth'     => $depth,
                'max_depth' => $args['max_depth'],
                'before'    => '<div class="reply mt-3">',
                'after'     => '</div>',
                'reply_text' => '<i class="fas fa-reply me-1"></i>' . esc_html__('Reply', 'trinity'),
                'class'     => 'btn btn-outline-primary btn-sm'
            ]));
            ?>
        </article>
    <?php
}
?>
