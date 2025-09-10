<?php
/**
 * Trinity Theme Custom Blocks
 * 
 * @package Trinity
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom blocks
 */
function trinity_register_blocks() {
    // Register blocks
    trinity_register_alert_block();
    trinity_register_accordion_block();
    trinity_register_carousel_block();
    trinity_register_card_block();
    trinity_register_button_group_block();
    trinity_register_badge_block();
    trinity_register_progress_block();
    trinity_register_tabs_block();
    trinity_register_list_group_block();
    trinity_register_enhanced_modal_block();
    trinity_register_table_block();
}
add_action('init', 'trinity_register_blocks');

/**
 * Alert Block
 */
function trinity_register_alert_block() {
    register_block_type('trinity/alert', [
        'attributes' => [
            'content' => [
                'type' => 'string',
                'default' => 'This is an alert message.'
            ],
            'type' => [
                'type' => 'string',
                'default' => 'primary'
            ],
            'dismissible' => [
                'type' => 'boolean',
                'default' => false
            ],
            'heading' => [
                'type' => 'string',
                'default' => ''
            ]
        ],
        'render_callback' => 'trinity_render_alert_block'
    ]);
}

function trinity_render_alert_block($attributes) {
    $content = $attributes['content'] ?? 'This is an alert message.';
    $type = $attributes['type'] ?? 'primary';
    $dismissible = $attributes['dismissible'] ?? false;
    $heading = $attributes['heading'] ?? '';
    
    $classes = 'alert alert-' . esc_attr($type);
    if ($dismissible) {
        $classes .= ' alert-dismissible fade show';
    }
    
    $output = '<div class="' . $classes . '" role="alert">';
    
    if (!empty($heading)) {
        $output .= '<h4 class="alert-heading">' . esc_html($heading) . '</h4>';
    }
    
    $output .= wp_kses_post($content);
    
    if ($dismissible) {
        $output .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Accordion Block
 */
function trinity_register_accordion_block() {
    register_block_type('trinity/accordion', [
        'attributes' => [
            'items' => [
                'type' => 'array',
                'default' => [
                    [
                        'title' => 'Accordion Item #1',
                        'content' => 'This is the first item\'s accordion body.',
                        'open' => true
                    ],
                    [
                        'title' => 'Accordion Item #2',
                        'content' => 'This is the second item\'s accordion body.',
                        'open' => false
                    ]
                ]
            ],
            'flush' => [
                'type' => 'boolean',
                'default' => false
            ],
            'headerFontSize' => [
                'type' => 'string',
                'default' => '1.25rem'
            ],
            'headerFontWeight' => [
                'type' => 'string',
                'default' => '500'
            ],
            'headerFontFamily' => [
                'type' => 'string',
                'default' => ''
            ],
            'contentFontSize' => [
                'type' => 'string',
                'default' => '1rem'
            ],
            'contentFontWeight' => [
                'type' => 'string',
                'default' => '400'
            ],
            'contentFontFamily' => [
                'type' => 'string',
                'default' => ''
            ]
        ],
        'render_callback' => 'trinity_render_accordion_block'
    ]);
}

function trinity_render_accordion_block($attributes) {
    $items = $attributes['items'] ?? [];
    $flush = $attributes['flush'] ?? false;
    $header_font_size = $attributes['headerFontSize'] ?? '1.25rem';
    $header_font_weight = $attributes['headerFontWeight'] ?? '500';
    $header_font_family = $attributes['headerFontFamily'] ?? '';
    $content_font_size = $attributes['contentFontSize'] ?? '1rem';
    $content_font_weight = $attributes['contentFontWeight'] ?? '400';
    $content_font_family = $attributes['contentFontFamily'] ?? '';
    $accordion_id = 'accordion-' . uniqid();
    
    $classes = 'accordion';
    if ($flush) {
        $classes .= ' accordion-flush';
    }
    
    $header_styles = [];
    if ($header_font_size) $header_styles[] = 'font-size: ' . esc_attr($header_font_size);
    if ($header_font_weight) $header_styles[] = 'font-weight: ' . esc_attr($header_font_weight);
    if ($header_font_family) $header_styles[] = 'font-family: ' . esc_attr($header_font_family);
    $header_style_attr = !empty($header_styles) ? ' style="' . implode('; ', $header_styles) . '"' : '';
    
    $content_styles = [];
    if ($content_font_size) $content_styles[] = 'font-size: ' . esc_attr($content_font_size);
    if ($content_font_weight) $content_styles[] = 'font-weight: ' . esc_attr($content_font_weight);
    if ($content_font_family) $content_styles[] = 'font-family: ' . esc_attr($content_font_family);
    $content_style_attr = !empty($content_styles) ? ' style="' . implode('; ', $content_styles) . '"' : '';
    
    $output = '<div class="' . $classes . '" id="' . $accordion_id . '">';
    
    foreach ($items as $index => $item) {
        $item_id = $accordion_id . '-item-' . $index;
        $title = $item['title'] ?? 'Accordion Item';
        $content = $item['content'] ?? 'Accordion content';
        $open = $item['open'] ?? false;
        
        $output .= '<div class="accordion-item">';
        $output .= '<h2 class="accordion-header" id="heading-' . $item_id . '">';
        $output .= '<button class="accordion-button' . ($open ? '' : ' collapsed') . '" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-' . $item_id . '" aria-expanded="' . ($open ? 'true' : 'false') . '" aria-controls="collapse-' . $item_id . '"' . $header_style_attr . '>';
        $output .= esc_html($title);
        $output .= '</button>';
        $output .= '</h2>';
        $output .= '<div id="collapse-' . $item_id . '" class="accordion-collapse collapse' . ($open ? ' show' : '') . '" aria-labelledby="heading-' . $item_id . '" data-bs-parent="#' . $accordion_id . '">';
        $output .= '<div class="accordion-body"' . $content_style_attr . '>';
        $output .= wp_kses_post($content);
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Carousel Block
 */
function trinity_register_carousel_block() {
    register_block_type('trinity/carousel', [
        'attributes' => [
            'slides' => [
                'type' => 'array',
                'default' => [
                    [
                        'image' => '',
                        'imageId' => 0,
                        'title' => 'First slide',
                        'content' => 'Some representative placeholder content for the first slide.',
                        'link' => '',
                        'linkText' => ''
                    ]
                ]
            ],
            'showControls' => [
                'type' => 'boolean',
                'default' => true
            ],
            'showIndicators' => [
                'type' => 'boolean',
                'default' => true
            ],
            'autoSlide' => [
                'type' => 'boolean',
                'default' => false
            ],
            'interval' => [
                'type' => 'number',
                'default' => 5000
            ],
            'fullWidth' => [
                'type' => 'boolean',
                'default' => false
            ],
            'height' => [
                'type' => 'string',
                'default' => '400px'
            ],
            'fade' => [
                'type' => 'boolean',
                'default' => false
            ]
        ],
        'render_callback' => 'trinity_render_carousel_block'
    ]);
}

function trinity_render_carousel_block($attributes) {
    $slides = $attributes['slides'] ?? [];
    $show_controls = $attributes['showControls'] ?? true;
    $show_indicators = $attributes['showIndicators'] ?? true;
    $auto_slide = $attributes['autoSlide'] ?? false;
    $interval = $attributes['interval'] ?? 5000;
    $full_width = $attributes['fullWidth'] ?? false;
    $height = $attributes['height'] ?? '400px';
    $fade = $attributes['fade'] ?? false;
    $carousel_id = 'carousel-' . uniqid();
    
    if (empty($slides)) {
        return '<p>No slides added to carousel.</p>';
    }
    
    $carousel_classes = ['carousel', 'slide'];
    if ($fade) {
        $carousel_classes[] = 'carousel-fade';
    }
    
    $wrapper_classes = ['trinity-carousel'];
    if ($full_width) {
        $wrapper_classes[] = 'trinity-carousel-full-width';
        $wrapper_classes[] = 'full-width';
    }
    
    $wrapper_style = '';
    if (!$full_width) {
        $wrapper_style = 'style="height: ' . esc_attr($height) . ';"';
    }
    
    $data_attributes = [
        'data-bs-ride="' . ($auto_slide ? 'carousel' : 'false') . '"',
        'data-bs-interval="' . intval($interval) . '"'
    ];
    
    $output = '';
    
    if (!empty($wrapper_classes)) {
        $output .= '<div class="' . implode(' ', $wrapper_classes) . '">';
    }
    
    $output .= '<div id="' . $carousel_id . '" class="' . implode(' ', $carousel_classes) . '" ' . implode(' ', $data_attributes) . ' ' . $wrapper_style . '>';
    
    // Indicators
    if ($show_indicators && count($slides) > 1) {
        $output .= '<div class="carousel-indicators">';
        foreach ($slides as $index => $slide) {
            $active = $index === 0 ? ' class="active" aria-current="true"' : '';
            $output .= '<button type="button" data-bs-target="#' . $carousel_id . '" data-bs-slide-to="' . $index . '"' . $active . ' aria-label="Slide ' . ($index + 1) . '"></button>';
        }
        $output .= '</div>';
    }
    
    // Slides
    $output .= '<div class="carousel-inner">';
    foreach ($slides as $index => $slide) {
        $active = $index === 0 ? ' active' : '';
        $image = $slide['image'] ?? '';
        $image_id = $slide['imageId'] ?? 0;
        $title = $slide['title'] ?? '';
        $content = $slide['content'] ?? '';
        $link = $slide['link'] ?? '';
        $link_text = $slide['linkText'] ?? '';
        
        $output .= '<div class="carousel-item' . $active . '">';
        
        if (!empty($image)) {
            $img_classes = 'd-block w-100';
            if ($full_width) {
                $img_classes .= ' trinity-carousel-full-width-img';
            }
            
            // Use WordPress image handling if we have an image ID
            if ($image_id > 0) {
                $image_size = $full_width ? 'full' : 'large';
                $image_html = wp_get_attachment_image($image_id, $image_size, false, [
                    'class' => $img_classes,
                    'alt' => esc_attr($title)
                ]);
                $output .= $image_html;
            } else {
                $output .= '<img src="' . esc_url($image) . '" class="' . $img_classes . '" alt="' . esc_attr($title) . '">';
            }
        } else {
            $placeholder_height = $full_width ? '50vh' : $height;
            $output .= '<div class="carousel-placeholder bg-secondary d-flex align-items-center justify-content-center" style="height: ' . esc_attr($placeholder_height) . ';"><span class="text-white fs-4">Image ' . ($index + 1) . '</span></div>';
        }
        
        if (!empty($title) || !empty($content) || (!empty($link) && !empty($link_text))) {
            $output .= '<div class="carousel-caption d-none d-md-block">';
            if (!empty($title)) {
                $output .= '<h5>' . esc_html($title) . '</h5>';
            }
            if (!empty($content)) {
                $output .= '<p>' . esc_html($content) . '</p>';
            }
            if (!empty($link) && !empty($link_text)) {
                $output .= '<a href="' . esc_url($link) . '" class="btn btn-primary">' . esc_html($link_text) . '</a>';
            }
            $output .= '</div>';
        }
        
        $output .= '</div>';
    }
    $output .= '</div>';
    
    // Controls
    if ($show_controls && count($slides) > 1) {
        $output .= '<button class="carousel-control-prev" type="button" data-bs-target="#' . $carousel_id . '" data-bs-slide="prev">';
        $output .= '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
        $output .= '<span class="visually-hidden">Previous</span>';
        $output .= '</button>';
        $output .= '<button class="carousel-control-next" type="button" data-bs-target="#' . $carousel_id . '" data-bs-slide="next">';
        $output .= '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
        $output .= '<span class="visually-hidden">Next</span>';
        $output .= '</button>';
    }
    
    $output .= '</div>';
    
    if (!empty($wrapper_classes)) {
        $output .= '</div>';
    }
    
    return $output;
}

/**
 * Card Block
 */
function trinity_register_card_block() {
    register_block_type('trinity/card', [
        'attributes' => [
            'title' => [
                'type' => 'string',
                'default' => 'Card Title'
            ],
            'content' => [
                'type' => 'string',
                'default' => 'Some quick example text to build on the card title and make up the bulk of the card\'s content.'
            ],
            'image' => [
                'type' => 'string',
                'default' => ''
            ],
            'link' => [
                'type' => 'string',
                'default' => ''
            ],
            'linkText' => [
                'type' => 'string',
                'default' => 'Learn More'
            ],
            'cardStyle' => [
                'type' => 'string',
                'default' => 'default'
            ]
        ],
        'render_callback' => 'trinity_render_card_block'
    ]);
}

function trinity_render_card_block($attributes) {
    $title = $attributes['title'] ?? 'Card Title';
    $content = $attributes['content'] ?? '';
    $image = $attributes['image'] ?? '';
    $link = $attributes['link'] ?? '';
    $link_text = $attributes['linkText'] ?? 'Learn More';
    $card_style = $attributes['cardStyle'] ?? 'default';
    
    $classes = 'card';
    if ($card_style !== 'default') {
        $classes .= ' border-' . esc_attr($card_style);
    }
    
    $output = '<div class="' . $classes . '">';
    
    if (!empty($image)) {
        $output .= '<img src="' . esc_url($image) . '" class="card-img-top" alt="' . esc_attr($title) . '">';
    }
    
    $output .= '<div class="card-body">';
    $output .= '<h5 class="card-title">' . esc_html($title) . '</h5>';
    
    if (!empty($content)) {
        $output .= '<p class="card-text">' . wp_kses_post($content) . '</p>';
    }
    
    if (!empty($link)) {
        $output .= '<a href="' . esc_url($link) . '" class="btn btn-primary">' . esc_html($link_text) . '</a>';
    }
    
    $output .= '</div>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Button Group Block
 */
function trinity_register_button_group_block() {
    register_block_type('trinity/button-group', [
        'attributes' => [
            'buttons' => [
                'type' => 'array',
                'default' => [
                    [
                        'text' => 'Button 1',
                        'link' => '#',
                        'style' => 'primary'
                    ],
                    [
                        'text' => 'Button 2',
                        'link' => '#',
                        'style' => 'secondary'
                    ]
                ]
            ],
            'size' => [
                'type' => 'string',
                'default' => 'default'
            ],
            'vertical' => [
                'type' => 'boolean',
                'default' => false
            ]
        ],
        'render_callback' => 'trinity_render_button_group_block'
    ]);
}

function trinity_render_button_group_block($attributes) {
    $buttons = $attributes['buttons'] ?? [];
    $size = $attributes['size'] ?? 'default';
    $vertical = $attributes['vertical'] ?? false;
    
    $classes = 'btn-group';
    if ($vertical) {
        $classes = 'btn-group-vertical';
    }
    if ($size !== 'default') {
        $classes .= ' btn-group-' . esc_attr($size);
    }
    
    $output = '<div class="' . $classes . '" role="group">';
    
    foreach ($buttons as $button) {
        $text = $button['text'] ?? 'Button';
        $link = $button['link'] ?? '#';
        $style = $button['style'] ?? 'primary';
        
        $btn_classes = 'btn btn-' . esc_attr($style);
        if ($size !== 'default') {
            $btn_classes .= ' btn-' . esc_attr($size);
        }
        
        $output .= '<a href="' . esc_url($link) . '" class="' . $btn_classes . '">' . esc_html($text) . '</a>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Badge Block
 */
function trinity_register_badge_block() {
    register_block_type('trinity/badge', [
        'attributes' => [
            'text' => [
                'type' => 'string',
                'default' => 'Badge'
            ],
            'style' => [
                'type' => 'string',
                'default' => 'primary'
            ],
            'pill' => [
                'type' => 'boolean',
                'default' => false
            ]
        ],
        'render_callback' => 'trinity_render_badge_block'
    ]);
}

function trinity_render_badge_block($attributes) {
    $text = $attributes['text'] ?? 'Badge';
    $style = $attributes['style'] ?? 'primary';
    $pill = $attributes['pill'] ?? false;
    
    $classes = 'badge bg-' . esc_attr($style);
    if ($pill) {
        $classes .= ' rounded-pill';
    }
    
    return '<span class="' . $classes . '">' . esc_html($text) . '</span>';
}

/**
 * Progress Block
 */
function trinity_register_progress_block() {
    register_block_type('trinity/progress', [
        'attributes' => [
            'value' => [
                'type' => 'number',
                'default' => 50
            ],
            'label' => [
                'type' => 'string',
                'default' => ''
            ],
            'style' => [
                'type' => 'string',
                'default' => 'primary'
            ],
            'striped' => [
                'type' => 'boolean',
                'default' => false
            ],
            'animated' => [
                'type' => 'boolean',
                'default' => false
            ]
        ],
        'render_callback' => 'trinity_render_progress_block'
    ]);
}

function trinity_render_progress_block($attributes) {
    $value = max(0, min(100, $attributes['value'] ?? 50));
    $label = $attributes['label'] ?? '';
    $style = $attributes['style'] ?? 'primary';
    $striped = $attributes['striped'] ?? false;
    $animated = $attributes['animated'] ?? false;
    
    $bar_classes = 'progress-bar bg-' . esc_attr($style);
    if ($striped || $animated) {
        $bar_classes .= ' progress-bar-striped';
    }
    if ($animated) {
        $bar_classes .= ' progress-bar-animated';
    }
    
    $output = '<div class="progress">';
    $output .= '<div class="' . $bar_classes . '" role="progressbar" style="width: ' . $value . '%;" aria-valuenow="' . $value . '" aria-valuemin="0" aria-valuemax="100">';
    
    if (!empty($label)) {
        $output .= esc_html($label);
    } else {
        $output .= $value . '%';
    }
    
    $output .= '</div>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Tabs Block
 */
function trinity_register_tabs_block() {
    register_block_type('trinity/tabs', [
        'attributes' => [
            'tabs' => [
                'type' => 'array',
                'default' => [
                    [
                        'title' => 'Tab 1',
                        'content' => 'Content for tab 1',
                        'active' => true
                    ],
                    [
                        'title' => 'Tab 2',
                        'content' => 'Content for tab 2',
                        'active' => false
                    ]
                ]
            ],
            'style' => [
                'type' => 'string',
                'default' => 'tabs'
            ]
        ],
        'render_callback' => 'trinity_render_tabs_block'
    ]);
}

function trinity_render_tabs_block($attributes) {
    $tabs = $attributes['tabs'] ?? [];
    $style = $attributes['style'] ?? 'tabs';
    $tabs_id = 'tabs-' . uniqid();
    
    if (empty($tabs)) {
        return '<p>No tabs added.</p>';
    }
    
    // Tab navigation
    $nav_classes = 'nav nav-' . esc_attr($style);
    $output = '<ul class="' . $nav_classes . '" id="' . $tabs_id . '" role="tablist">';
    
    foreach ($tabs as $index => $tab) {
        $title = $tab['title'] ?? 'Tab ' . ($index + 1);
        $active = ($tab['active'] ?? false) || ($index === 0);
        $tab_id = $tabs_id . '-tab-' . $index;
        $pane_id = $tabs_id . '-pane-' . $index;
        
        $output .= '<li class="nav-item" role="presentation">';
        $output .= '<button class="nav-link' . ($active ? ' active' : '') . '" id="' . $tab_id . '" data-bs-toggle="tab" data-bs-target="#' . $pane_id . '" type="button" role="tab" aria-controls="' . $pane_id . '" aria-selected="' . ($active ? 'true' : 'false') . '">';
        $output .= esc_html($title);
        $output .= '</button>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    
    // Tab content
    $output .= '<div class="tab-content" id="' . $tabs_id . 'Content">';
    
    foreach ($tabs as $index => $tab) {
        $content = $tab['content'] ?? '';
        $active = ($tab['active'] ?? false) || ($index === 0);
        $tab_id = $tabs_id . '-tab-' . $index;
        $pane_id = $tabs_id . '-pane-' . $index;
        
        $output .= '<div class="tab-pane fade' . ($active ? ' show active' : '') . '" id="' . $pane_id . '" role="tabpanel" aria-labelledby="' . $tab_id . '">';
        $output .= '<div class="p-3">' . wp_kses_post($content) . '</div>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * List Group Block
 */
function trinity_register_list_group_block() {
    register_block_type('trinity/list-group', [
        'attributes' => [
            'items' => [
                'type' => 'array',
                'default' => [
                    ['text' => 'First item', 'active' => false, 'disabled' => false, 'variant' => '', 'link' => ''],
                    ['text' => 'Second item', 'active' => true, 'disabled' => false, 'variant' => '', 'link' => ''],
                    ['text' => 'Third item', 'active' => false, 'disabled' => false, 'variant' => '', 'link' => '']
                ]
            ],
            'flush' => [
                'type' => 'boolean',
                'default' => false
            ],
            'numbered' => [
                'type' => 'boolean',
                'default' => false
            ],
            'horizontal' => [
                'type' => 'boolean',
                'default' => false
            ]
        ],
        'render_callback' => 'trinity_render_list_group_block'
    ]);
}

function trinity_render_list_group_block($attributes) {
    $items = $attributes['items'] ?? [];
    $flush = $attributes['flush'] ?? false;
    $numbered = $attributes['numbered'] ?? false;
    $horizontal = $attributes['horizontal'] ?? false;
    
    if (empty($items)) {
        return '<p>No items added to list group.</p>';
    }
    
    $classes = ['list-group'];
    if ($flush) {
        $classes[] = 'list-group-flush';
    }
    if ($numbered) {
        $classes[] = 'list-group-numbered';
    }
    if ($horizontal) {
        $classes[] = 'list-group-horizontal';
    }
    
    $tag = $numbered ? 'ol' : 'ul';
    $output = '<' . $tag . ' class="' . implode(' ', $classes) . '">';
    
    foreach ($items as $item) {
        $text = $item['text'] ?? '';
        $active = $item['active'] ?? false;
        $disabled = $item['disabled'] ?? false;
        $variant = $item['variant'] ?? '';
        $link = $item['link'] ?? '';
        
        $item_classes = ['list-group-item'];
        if ($active) {
            $item_classes[] = 'active';
        }
        if ($disabled) {
            $item_classes[] = 'disabled';
        }
        if (!empty($variant)) {
            $item_classes[] = 'list-group-item-' . $variant;
        }
        
        if (!empty($link) && !$disabled) {
            $item_classes[] = 'list-group-item-action';
            $output .= '<li class="' . implode(' ', $item_classes) . '">';
            $output .= '<a href="' . esc_url($link) . '" class="text-decoration-none">' . esc_html($text) . '</a>';
            $output .= '</li>';
        } else {
            $output .= '<li class="' . implode(' ', $item_classes) . '">' . esc_html($text) . '</li>';
        }
    }
    
    $output .= '</' . $tag . '>';
    
    return $output;
}

/**
 * Enhanced Modal Block (separate from theme modal)
 */
function trinity_register_enhanced_modal_block() {
    register_block_type('trinity/enhanced-modal', [
        'attributes' => [
            'modalId' => [
                'type' => 'string',
                'default' => ''
            ],
            'title' => [
                'type' => 'string',
                'default' => 'Modal title'
            ],
            'content' => [
                'type' => 'string',
                'default' => 'Modal body text goes here.'
            ],
            'size' => [
                'type' => 'string',
                'default' => ''
            ],
            'centered' => [
                'type' => 'boolean',
                'default' => false
            ],
            'scrollable' => [
                'type' => 'boolean',
                'default' => false
            ],
            'fullscreen' => [
                'type' => 'string',
                'default' => ''
            ],
            'backdrop' => [
                'type' => 'string',
                'default' => 'true'
            ],
            'triggerText' => [
                'type' => 'string',
                'default' => 'Launch demo modal'
            ],
            'triggerVariant' => [
                'type' => 'string',
                'default' => 'primary'
            ],
            'showFooter' => [
                'type' => 'boolean',
                'default' => true
            ],
            'primaryButtonText' => [
                'type' => 'string',
                'default' => 'Save changes'
            ],
            'primaryButtonLink' => [
                'type' => 'string',
                'default' => ''
            ],
            'primaryButtonVariant' => [
                'type' => 'string',
                'default' => 'primary'
            ],
            'primaryButtonAction' => [
                'type' => 'string',
                'default' => ''
            ],
            'showPrimaryButton' => [
                'type' => 'boolean',
                'default' => true
            ],
            'secondaryButtonText' => [
                'type' => 'string',
                'default' => 'Close'
            ],
            'secondaryButtonLink' => [
                'type' => 'string',
                'default' => ''
            ],
            'secondaryButtonVariant' => [
                'type' => 'string',
                'default' => 'secondary'
            ],
            'secondaryButtonAction' => [
                'type' => 'string',
                'default' => 'close'
            ],
            'showSecondaryButton' => [
                'type' => 'boolean',
                'default' => true
            ]
        ],
        'render_callback' => 'trinity_render_enhanced_modal_block'
    ]);
}

function trinity_render_enhanced_modal_block($attributes) {
    $modal_id = !empty($attributes['modalId']) ? $attributes['modalId'] : 'modal-' . uniqid();
    $title = $attributes['title'] ?? 'Modal title';
    $content = $attributes['content'] ?? 'Modal body text goes here.';
    $size = $attributes['size'] ?? '';
    $centered = $attributes['centered'] ?? false;
    $scrollable = $attributes['scrollable'] ?? false;
    $fullscreen = $attributes['fullscreen'] ?? '';
    $backdrop = $attributes['backdrop'] ?? 'true';
    $trigger_text = $attributes['triggerText'] ?? 'Launch demo modal';
    $trigger_variant = $attributes['triggerVariant'] ?? 'primary';
    $show_footer = $attributes['showFooter'] ?? true;
    
    // Primary button attributes
    $primary_button_text = $attributes['primaryButtonText'] ?? 'Save changes';
    $primary_button_link = $attributes['primaryButtonLink'] ?? '';
    $primary_button_variant = $attributes['primaryButtonVariant'] ?? 'primary';
    $primary_button_action = $attributes['primaryButtonAction'] ?? '';
    $show_primary_button = $attributes['showPrimaryButton'] ?? true;
    
    // Secondary button attributes
    $secondary_button_text = $attributes['secondaryButtonText'] ?? 'Close';
    $secondary_button_link = $attributes['secondaryButtonLink'] ?? '';
    $secondary_button_variant = $attributes['secondaryButtonVariant'] ?? 'secondary';
    $secondary_button_action = $attributes['secondaryButtonAction'] ?? 'close';
    $show_secondary_button = $attributes['showSecondaryButton'] ?? true;
    
    $modal_classes = ['modal-dialog'];
    if (!empty($size)) {
        $modal_classes[] = 'modal-' . $size;
    }
    if ($centered) {
        $modal_classes[] = 'modal-dialog-centered';
    }
    if ($scrollable) {
        $modal_classes[] = 'modal-dialog-scrollable';
    }
    if (!empty($fullscreen)) {
        if ($fullscreen === 'true') {
            $modal_classes[] = 'modal-fullscreen';
        } else {
            $modal_classes[] = 'modal-fullscreen-' . $fullscreen . '-down';
        }
    }
    
    $modal_attributes = [
        'tabindex="-1"',
        'aria-labelledby="' . $modal_id . 'Label"',
        'aria-hidden="true"'
    ];
    
    if ($backdrop !== 'true') {
        $modal_attributes[] = 'data-bs-backdrop="' . esc_attr($backdrop) . '"';
    }
    
    $output = '';
    
    // Trigger button
    $output .= '<button type="button" class="btn btn-' . esc_attr($trigger_variant) . '" data-bs-toggle="modal" data-bs-target="#' . esc_attr($modal_id) . '">';
    $output .= esc_html($trigger_text);
    $output .= '</button>';
    
    // Modal
    $output .= '<div class="modal fade" id="' . esc_attr($modal_id) . '" ' . implode(' ', $modal_attributes) . '>';
    $output .= '<div class="' . implode(' ', $modal_classes) . '">';
    $output .= '<div class="modal-content">';
    
    // Header
    $output .= '<div class="modal-header">';
    $output .= '<h5 class="modal-title" id="' . esc_attr($modal_id) . 'Label">' . esc_html($title) . '</h5>';
    $output .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
    $output .= '</div>';
    
    // Body
    $output .= '<div class="modal-body">';
    $output .= wp_kses_post($content);
    $output .= '</div>';
    
    // Footer
    if ($show_footer && ($show_secondary_button || $show_primary_button)) {
        $output .= '<div class="modal-footer">';
        
        // Secondary button
        if ($show_secondary_button) {
            $button_attrs = 'class="btn btn-' . esc_attr($secondary_button_variant) . '"';
            
            if ($secondary_button_action === 'close') {
                $button_attrs .= ' data-bs-dismiss="modal"';
            }
            
            if (!empty($secondary_button_link)) {
                $output .= '<a href="' . esc_url($secondary_button_link) . '" ' . $button_attrs . '>' . esc_html($secondary_button_text) . '</a>';
            } else {
                $output .= '<button type="button" ' . $button_attrs . '>' . esc_html($secondary_button_text) . '</button>';
            }
        }
        
        // Primary button
        if ($show_primary_button) {
            $button_attrs = 'class="btn btn-' . esc_attr($primary_button_variant) . '"';
            
            if ($primary_button_action === 'close') {
                $button_attrs .= ' data-bs-dismiss="modal"';
            }
            
            if (!empty($primary_button_link)) {
                $output .= '<a href="' . esc_url($primary_button_link) . '" ' . $button_attrs . '>' . esc_html($primary_button_text) . '</a>';
            } else {
                $output .= '<button type="button" ' . $button_attrs . '>' . esc_html($primary_button_text) . '</button>';
            }
        }
        
        $output .= '</div>';
    }
    
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Bootstrap Table Block
 */
function trinity_register_table_block() {
    register_block_type('trinity/table', [
        'attributes' => [
            'headers' => [
                'type' => 'array',
                'default' => ['First', 'Last', 'Handle']
            ],
            'rows' => [
                'type' => 'array',
                'default' => [
                    ['Mark', 'Otto', '@mdo'],
                    ['Jacob', 'Thornton', '@fat'],
                    ['Larry', 'the Bird', '@twitter']
                ]
            ],
            'striped' => [
                'type' => 'boolean',
                'default' => false
            ],
            'hover' => [
                'type' => 'boolean',
                'default' => false
            ],
            'bordered' => [
                'type' => 'boolean',
                'default' => false
            ],
            'borderless' => [
                'type' => 'boolean',
                'default' => false
            ],
            'small' => [
                'type' => 'boolean',
                'default' => false
            ],
            'responsive' => [
                'type' => 'boolean',
                'default' => true
            ],
            'variant' => [
                'type' => 'string',
                'default' => ''
            ],
            'caption' => [
                'type' => 'string',
                'default' => ''
            ]
        ],
        'render_callback' => 'trinity_render_table_block'
    ]);
}

function trinity_render_table_block($attributes) {
    $headers = $attributes['headers'] ?? [];
    $rows = $attributes['rows'] ?? [];
    $striped = $attributes['striped'] ?? false;
    $hover = $attributes['hover'] ?? false;
    $bordered = $attributes['bordered'] ?? false;
    $borderless = $attributes['borderless'] ?? false;
    $small = $attributes['small'] ?? false;
    $responsive = $attributes['responsive'] ?? true;
    $variant = $attributes['variant'] ?? '';
    $caption = $attributes['caption'] ?? '';
    
    if (empty($headers) && empty($rows)) {
        return '<p>No table data provided.</p>';
    }
    
    $table_classes = ['table'];
    
    if ($striped) {
        $table_classes[] = 'table-striped';
    }
    if ($hover) {
        $table_classes[] = 'table-hover';
    }
    if ($bordered) {
        $table_classes[] = 'table-bordered';
    }
    if ($borderless) {
        $table_classes[] = 'table-borderless';
    }
    if ($small) {
        $table_classes[] = 'table-sm';
    }
    if (!empty($variant)) {
        $table_classes[] = 'table-' . $variant;
    }
    
    $output = '';
    
    if ($responsive) {
        $output .= '<div class="table-responsive">';
    }
    
    $output .= '<table class="' . implode(' ', $table_classes) . '">';
    
    // Caption
    if (!empty($caption)) {
        $output .= '<caption>' . esc_html($caption) . '</caption>';
    }
    
    // Headers
    if (!empty($headers)) {
        $output .= '<thead>';
        $output .= '<tr>';
        foreach ($headers as $header) {
            $output .= '<th scope="col">' . esc_html($header) . '</th>';
        }
        $output .= '</tr>';
        $output .= '</thead>';
    }
    
    // Body
    if (!empty($rows)) {
        $output .= '<tbody>';
        foreach ($rows as $row) {
            $output .= '<tr>';
            foreach ($row as $index => $cell) {
                if ($index === 0) {
                    $output .= '<th scope="row">' . esc_html($cell) . '</th>';
                } else {
                    $output .= '<td>' . esc_html($cell) . '</td>';
                }
            }
            $output .= '</tr>';
        }
        $output .= '</tbody>';
    }
    
    $output .= '</table>';
    
    if ($responsive) {
        $output .= '</div>';
    }
    
    return $output;
}
