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
            ]
        ],
        'render_callback' => 'trinity_render_accordion_block'
    ]);
}

function trinity_render_accordion_block($attributes) {
    $items = $attributes['items'] ?? [];
    $flush = $attributes['flush'] ?? false;
    $accordion_id = 'accordion-' . uniqid();
    
    $classes = 'accordion';
    if ($flush) {
        $classes .= ' accordion-flush';
    }
    
    $output = '<div class="' . $classes . '" id="' . $accordion_id . '">';
    
    foreach ($items as $index => $item) {
        $item_id = $accordion_id . '-item-' . $index;
        $title = $item['title'] ?? 'Accordion Item';
        $content = $item['content'] ?? 'Accordion content';
        $open = $item['open'] ?? false;
        
        $output .= '<div class="accordion-item">';
        $output .= '<h2 class="accordion-header" id="heading-' . $item_id . '">';
        $output .= '<button class="accordion-button' . ($open ? '' : ' collapsed') . '" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-' . $item_id . '" aria-expanded="' . ($open ? 'true' : 'false') . '" aria-controls="collapse-' . $item_id . '">';
        $output .= esc_html($title);
        $output .= '</button>';
        $output .= '</h2>';
        $output .= '<div id="collapse-' . $item_id . '" class="accordion-collapse collapse' . ($open ? ' show' : '') . '" aria-labelledby="heading-' . $item_id . '" data-bs-parent="#' . $accordion_id . '">';
        $output .= '<div class="accordion-body">';
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
    $carousel_id = 'carousel-' . uniqid();
    
    if (empty($slides)) {
        return '<p>No slides added to carousel.</p>';
    }
    
    $output = '<div id="' . $carousel_id . '" class="carousel slide" data-bs-ride="' . ($auto_slide ? 'carousel' : 'false') . '">';
    
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
        $title = $slide['title'] ?? '';
        $content = $slide['content'] ?? '';
        $link = $slide['link'] ?? '';
        $link_text = $slide['linkText'] ?? '';
        
        $output .= '<div class="carousel-item' . $active . '">';
        
        if (!empty($image)) {
            $output .= '<img src="' . esc_url($image) . '" class="d-block w-100" alt="' . esc_attr($title) . '">';
        } else {
            $output .= '<div class="carousel-placeholder bg-secondary d-flex align-items-center justify-content-center" style="height: 400px;"><span class="text-white">Image ' . ($index + 1) . '</span></div>';
        }
        
        if (!empty($title) || !empty($content)) {
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
