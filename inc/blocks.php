<?php
/**
 * Trinity Custom Theme Blocks (Hero and Modal only)
 * 
 * @package Trinity
 */

/**
 * Register custom theme blocks (non-Bootstrap components)
 */
function trinity_register_theme_blocks() {
    
    // Hero Block
    register_block_type('trinity/hero', [
        'render_callback' => 'trinity_render_hero_block',
        'attributes' => [
            'backgroundType' => [
                'type' => 'string',
                'default' => 'color'
            ],
            'backgroundImage' => [
                'type' => 'string',
                'default' => ''
            ],
            'backgroundVideo' => [
                'type' => 'string',
                'default' => ''
            ],
            'backgroundColor' => [
                'type' => 'string',
                'default' => '#007cba'
            ],
            'borderRadius' => [
                'type' => 'number',
                'default' => 0
            ],
            'fullWidth' => [
                'type' => 'boolean',
                'default' => false
            ],
            'textPosition' => [
                'type' => 'string',
                'default' => 'center'
            ],
            'textAlignment' => [
                'type' => 'string',
                'default' => 'center'
            ],
            'textColor' => [
                'type' => 'string',
                'default' => '#ffffff'
            ],
            'textBackgroundColor' => [
                'type' => 'string',
                'default' => ''
            ],
            'parallax' => [
                'type' => 'boolean',
                'default' => false
            ],
            'staticBackground' => [
                'type' => 'boolean',
                'default' => false
            ],
            'content' => [
                'type' => 'string',
                'default' => ''
            ],
            'buttons' => [
                'type' => 'array',
                'default' => []
            ]
        ]
    ]);
    
    // Modal Block
    register_block_type('trinity/modal', [
        'render_callback' => 'trinity_render_modal_block',
        'attributes' => [
            'modalId' => [
                'type' => 'string',
                'default' => ''
            ],
            'size' => [
                'type' => 'string',
                'default' => 'md'
            ],
            'title' => [
                'type' => 'string',
                'default' => ''
            ],
            'content' => [
                'type' => 'string',
                'default' => ''
            ],
            'triggerType' => [
                'type' => 'string',
                'default' => 'button'
            ],
            'triggerText' => [
                'type' => 'string',
                'default' => 'Open Modal'
            ],
            'triggerOnLoad' => [
                'type' => 'boolean',
                'default' => false
            ]
        ]
    ]);
}
add_action('init', 'trinity_register_theme_blocks');

/**
 * Render Hero Block
 */
function trinity_render_hero_block($attributes) {
    $background_type = $attributes['backgroundType'];
    $background_image = $attributes['backgroundImage'];
    $background_video = $attributes['backgroundVideo'];
    $background_color = $attributes['backgroundColor'];
    $border_radius = $attributes['borderRadius'];
    $full_width = $attributes['fullWidth'];
    $text_position = $attributes['textPosition'];
    $text_alignment = $attributes['textAlignment'];
    $text_color = $attributes['textColor'];
    $text_background_color = $attributes['textBackgroundColor'];
    $parallax = $attributes['parallax'];
    $static_background = $attributes['staticBackground'];
    $content = $attributes['content'];
    $buttons = $attributes['buttons'];
    
    $wrapper_classes = ['trinity-hero'];
    
    if ($full_width) {
        $wrapper_classes[] = 'trinity-hero-full-width';
    }
    
    if ($parallax && $background_type === 'image') {
        $wrapper_classes[] = 'trinity-hero-parallax';
    }
    
    $wrapper_classes[] = 'trinity-hero-text-' . $text_position;
    $wrapper_classes[] = 'text-' . $text_alignment;
    
    $wrapper_style = [];
    
    if ($background_type === 'color') {
        $wrapper_style[] = 'background-color: ' . esc_attr($background_color);
    } elseif ($background_type === 'image' && $background_image) {
        if ($static_background) {
            $wrapper_style[] = 'background-image: url(' . esc_url($background_image) . ')';
            $wrapper_style[] = 'background-attachment: fixed';
            $wrapper_style[] = 'background-size: cover';
            $wrapper_style[] = 'background-position: center';
        } else {
            $wrapper_style[] = 'background-image: url(' . esc_url($background_image) . ')';
            $wrapper_style[] = 'background-size: cover';
            $wrapper_style[] = 'background-position: center';
        }
    }
    
    if ($border_radius > 0) {
        $wrapper_style[] = 'border-radius: ' . intval($border_radius) . 'px';
    }
    
    $text_style = [];
    if ($text_color) {
        $text_style[] = 'color: ' . esc_attr($text_color);
    }
    if ($text_background_color) {
        $text_style[] = 'background-color: ' . esc_attr($text_background_color);
    }
    
    ob_start();
    ?>
    <div class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>" style="<?php echo esc_attr(implode('; ', $wrapper_style)); ?>">
        <?php if ($background_type === 'video' && $background_video): ?>
            <video class="trinity-hero-video" autoplay muted loop>
                <source src="<?php echo esc_url($background_video); ?>" type="video/mp4">
            </video>
        <?php endif; ?>
        
        <div class="trinity-hero-content" style="<?php echo esc_attr(implode('; ', $text_style)); ?>">
            <?php if ($content): ?>
                <div class="trinity-hero-text">
                    <?php echo wp_kses_post($content); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($buttons)): ?>
                <div class="trinity-hero-buttons">
                    <?php foreach ($buttons as $button): ?>
                        <a href="<?php echo esc_url($button['url']); ?>" 
                           class="btn btn-<?php echo esc_attr($button['style']); ?> <?php echo esc_attr($button['size']); ?>">
                            <?php echo esc_html($button['text']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render Modal Block
 */
function trinity_render_modal_block($attributes) {
    $modal_id = !empty($attributes['modalId']) ? $attributes['modalId'] : 'modal-' . wp_generate_uuid4();
    $size_class = 'modal-' . esc_attr($attributes['size']);
    $title = $attributes['title'];
    $content = $attributes['content'];
    $trigger_type = $attributes['triggerType'];
    $trigger_text = $attributes['triggerText'];
    $trigger_on_load = $attributes['triggerOnLoad'];
    
    ob_start();
    ?>
    <?php if ($trigger_type === 'button'): ?>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#<?php echo esc_attr($modal_id); ?>">
            <?php echo esc_html($trigger_text); ?>
        </button>
    <?php endif; ?>
    
    <div class="modal fade" id="<?php echo esc_attr($modal_id); ?>" tabindex="-1" aria-labelledby="<?php echo esc_attr($modal_id); ?>Label" aria-hidden="true">
        <div class="modal-dialog <?php echo esc_attr($size_class); ?>">
            <div class="modal-content">
                <?php if ($title): ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?php echo esc_attr($modal_id); ?>Label"><?php echo esc_html($title); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <div class="modal-body">
                    <?php echo wp_kses_post($content); ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($trigger_on_load): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('<?php echo esc_js($modal_id); ?>'));
                modal.show();
            });
        </script>
    <?php endif; ?>
    <?php
    return ob_get_clean();
}
