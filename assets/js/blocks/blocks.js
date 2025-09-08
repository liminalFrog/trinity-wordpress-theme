/**
 * Trinity Bootstrap Blocks
 */

const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, TextControl, SelectControl, ToggleControl, RangeControl, Button } = wp.components;
const { useState } = wp.element;

// Alert Block
registerBlockType('trinity/alert', {
    title: 'Bootstrap Alert',
    icon: 'warning',
    category: 'trinity-blocks',
    attributes: {
        content: {
            type: 'string',
            default: 'This is an alert message.'
        },
        type: {
            type: 'string',
            default: 'primary'
        },
        dismissible: {
            type: 'boolean',
            default: false
        },
        heading: {
            type: 'string',
            default: ''
        }
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { content, type, dismissible, heading } = attributes;

        return [
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Alert Settings' },
                    wp.element.createElement(SelectControl, {
                        label: 'Alert Type',
                        value: type,
                        options: [
                            { label: 'Primary', value: 'primary' },
                            { label: 'Secondary', value: 'secondary' },
                            { label: 'Success', value: 'success' },
                            { label: 'Danger', value: 'danger' },
                            { label: 'Warning', value: 'warning' },
                            { label: 'Info', value: 'info' }
                        ],
                        onChange: (value) => setAttributes({ type: value })
                    }),
                    wp.element.createElement(TextControl, {
                        label: 'Heading (optional)',
                        value: heading,
                        onChange: (value) => setAttributes({ heading: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Dismissible',
                        checked: dismissible,
                        onChange: (value) => setAttributes({ dismissible: value })
                    })
                )
            ),
            wp.element.createElement('div', { className: 'trinity-alert-preview' },
                wp.element.createElement('div', {
                    className: `alert alert-${type}${dismissible ? ' alert-dismissible' : ''}`,
                    style: { marginBottom: 0 }
                },
                    heading && wp.element.createElement('h4', { className: 'alert-heading' }, heading),
                    wp.element.createElement(wp.blockEditor.RichText, {
                        tagName: 'div',
                        value: content,
                        onChange: (value) => setAttributes({ content: value }),
                        placeholder: 'Enter alert message...'
                    }),
                    dismissible && wp.element.createElement('button', {
                        type: 'button',
                        className: 'btn-close',
                        'aria-label': 'Close'
                    })
                )
            )
        ];
    },
    save: function() {
        return null; // Server-side rendering
    }
});

// Accordion Block
registerBlockType('trinity/accordion', {
    title: 'Bootstrap Accordion',
    icon: 'list-view',
    category: 'trinity-blocks',
    attributes: {
        items: {
            type: 'array',
            default: [
                {
                    title: 'Accordion Item #1',
                    content: 'This is the first item\'s accordion body.',
                    open: true
                },
                {
                    title: 'Accordion Item #2',
                    content: 'This is the second item\'s accordion body.',
                    open: false
                }
            ]
        },
        flush: {
            type: 'boolean',
            default: false
        }
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { items, flush } = attributes;

        const addItem = () => {
            const newItems = [...items, {
                title: `Accordion Item #${items.length + 1}`,
                content: 'New accordion content.',
                open: false
            }];
            setAttributes({ items: newItems });
        };

        const removeItem = (index) => {
            const newItems = items.filter((_, i) => i !== index);
            setAttributes({ items: newItems });
        };

        const updateItem = (index, field, value) => {
            const newItems = [...items];
            newItems[index][field] = value;
            setAttributes({ items: newItems });
        };

        return [
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Accordion Settings' },
                    wp.element.createElement(ToggleControl, {
                        label: 'Flush Design',
                        checked: flush,
                        onChange: (value) => setAttributes({ flush: value })
                    }),
                    wp.element.createElement(Button, {
                        isPrimary: true,
                        onClick: addItem
                    }, 'Add Item')
                )
            ),
            wp.element.createElement('div', { className: 'trinity-accordion-preview' },
                wp.element.createElement('div', { className: `accordion${flush ? ' accordion-flush' : ''}` },
                    items.map((item, index) =>
                        wp.element.createElement('div', { key: index, className: 'accordion-item' },
                            wp.element.createElement('h2', { className: 'accordion-header' },
                                wp.element.createElement(wp.blockEditor.RichText, {
                                    tagName: 'button',
                                    className: `accordion-button${item.open ? '' : ' collapsed'}`,
                                    value: item.title,
                                    onChange: (value) => updateItem(index, 'title', value),
                                    placeholder: 'Accordion title...'
                                }),
                                wp.element.createElement(Button, {
                                    isDestructive: true,
                                    isSmall: true,
                                    onClick: () => removeItem(index),
                                    style: { marginLeft: '10px' }
                                }, 'Remove')
                            ),
                            wp.element.createElement('div', {
                                className: `accordion-collapse collapse${item.open ? ' show' : ''}`
                            },
                                wp.element.createElement('div', { className: 'accordion-body' },
                                    wp.element.createElement(wp.blockEditor.RichText, {
                                        tagName: 'div',
                                        value: item.content,
                                        onChange: (value) => updateItem(index, 'content', value),
                                        placeholder: 'Accordion content...'
                                    })
                                )
                            )
                        )
                    )
                )
            )
        ];
    },
    save: function() {
        return null; // Server-side rendering
    }
});

// Card Block
registerBlockType('trinity/card', {
    title: 'Bootstrap Card',
    icon: 'id-alt',
    category: 'trinity-blocks',
    attributes: {
        title: {
            type: 'string',
            default: 'Card Title'
        },
        content: {
            type: 'string',
            default: 'Some quick example text to build on the card title and make up the bulk of the card\'s content.'
        },
        image: {
            type: 'string',
            default: ''
        },
        link: {
            type: 'string',
            default: ''
        },
        linkText: {
            type: 'string',
            default: 'Learn More'
        },
        cardStyle: {
            type: 'string',
            default: 'default'
        }
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { title, content, image, link, linkText, cardStyle } = attributes;

        return [
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Card Settings' },
                    wp.element.createElement(wp.blockEditor.MediaUpload, {
                        onSelect: (media) => setAttributes({ image: media.url }),
                        allowedTypes: ['image'],
                        value: image,
                        render: ({ open }) => (
                            wp.element.createElement(Button, {
                                onClick: open,
                                isPrimary: !image,
                                isSecondary: !!image
                            }, image ? 'Change Image' : 'Select Image')
                        )
                    }),
                    wp.element.createElement(TextControl, {
                        label: 'Link URL',
                        value: link,
                        onChange: (value) => setAttributes({ link: value })
                    }),
                    wp.element.createElement(TextControl, {
                        label: 'Link Text',
                        value: linkText,
                        onChange: (value) => setAttributes({ linkText: value })
                    }),
                    wp.element.createElement(SelectControl, {
                        label: 'Card Style',
                        value: cardStyle,
                        options: [
                            { label: 'Default', value: 'default' },
                            { label: 'Primary', value: 'primary' },
                            { label: 'Secondary', value: 'secondary' },
                            { label: 'Success', value: 'success' },
                            { label: 'Danger', value: 'danger' },
                            { label: 'Warning', value: 'warning' },
                            { label: 'Info', value: 'info' }
                        ],
                        onChange: (value) => setAttributes({ cardStyle: value })
                    })
                )
            ),
            wp.element.createElement('div', { className: 'trinity-card-preview' },
                wp.element.createElement('div', {
                    className: `card${cardStyle !== 'default' ? ` border-${cardStyle}` : ''}`,
                    style: { maxWidth: '300px' }
                },
                    image && wp.element.createElement('img', {
                        src: image,
                        className: 'card-img-top',
                        alt: title
                    }),
                    wp.element.createElement('div', { className: 'card-body' },
                        wp.element.createElement(wp.blockEditor.RichText, {
                            tagName: 'h5',
                            className: 'card-title',
                            value: title,
                            onChange: (value) => setAttributes({ title: value }),
                            placeholder: 'Card title...'
                        }),
                        wp.element.createElement(wp.blockEditor.RichText, {
                            tagName: 'p',
                            className: 'card-text',
                            value: content,
                            onChange: (value) => setAttributes({ content: value }),
                            placeholder: 'Card content...'
                        }),
                        link && wp.element.createElement('a', {
                            href: link,
                            className: 'btn btn-primary'
                        }, linkText || 'Learn More')
                    )
                )
            )
        ];
    },
    save: function() {
        return null; // Server-side rendering
    }
});

// Progress Block
registerBlockType('trinity/progress', {
    title: 'Bootstrap Progress Bar',
    icon: 'chart-bar',
    category: 'trinity-blocks',
    attributes: {
        value: {
            type: 'number',
            default: 50
        },
        label: {
            type: 'string',
            default: ''
        },
        style: {
            type: 'string',
            default: 'primary'
        },
        striped: {
            type: 'boolean',
            default: false
        },
        animated: {
            type: 'boolean',
            default: false
        }
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { value, label, style, striped, animated } = attributes;

        return [
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Progress Settings' },
                    wp.element.createElement(RangeControl, {
                        label: 'Progress Value (%)',
                        value: value,
                        onChange: (value) => setAttributes({ value: value }),
                        min: 0,
                        max: 100
                    }),
                    wp.element.createElement(TextControl, {
                        label: 'Custom Label (optional)',
                        value: label,
                        onChange: (value) => setAttributes({ label: value })
                    }),
                    wp.element.createElement(SelectControl, {
                        label: 'Progress Style',
                        value: style,
                        options: [
                            { label: 'Primary', value: 'primary' },
                            { label: 'Secondary', value: 'secondary' },
                            { label: 'Success', value: 'success' },
                            { label: 'Danger', value: 'danger' },
                            { label: 'Warning', value: 'warning' },
                            { label: 'Info', value: 'info' }
                        ],
                        onChange: (value) => setAttributes({ style: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Striped',
                        checked: striped,
                        onChange: (value) => setAttributes({ striped: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Animated',
                        checked: animated,
                        onChange: (value) => setAttributes({ animated: value })
                    })
                )
            ),
            wp.element.createElement('div', { className: 'trinity-progress-preview' },
                wp.element.createElement('div', { className: 'progress' },
                    wp.element.createElement('div', {
                        className: `progress-bar bg-${style}${striped || animated ? ' progress-bar-striped' : ''}${animated ? ' progress-bar-animated' : ''}`,
                        style: { width: `${value}%` }
                    }, label || `${value}%`)
                )
            )
        ];
    },
    save: function() {
        return null; // Server-side rendering
    }
});

// Badge Block
registerBlockType('trinity/badge', {
    title: 'Bootstrap Badge',
    icon: 'tag',
    category: 'trinity-blocks',
    attributes: {
        text: {
            type: 'string',
            default: 'Badge'
        },
        style: {
            type: 'string',
            default: 'primary'
        },
        pill: {
            type: 'boolean',
            default: false
        }
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { text, style, pill } = attributes;

        return [
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Badge Settings' },
                    wp.element.createElement(SelectControl, {
                        label: 'Badge Style',
                        value: style,
                        options: [
                            { label: 'Primary', value: 'primary' },
                            { label: 'Secondary', value: 'secondary' },
                            { label: 'Success', value: 'success' },
                            { label: 'Danger', value: 'danger' },
                            { label: 'Warning', value: 'warning' },
                            { label: 'Info', value: 'info' },
                            { label: 'Light', value: 'light' },
                            { label: 'Dark', value: 'dark' }
                        ],
                        onChange: (value) => setAttributes({ style: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Pill Style',
                        checked: pill,
                        onChange: (value) => setAttributes({ pill: value })
                    })
                )
            ),
            wp.element.createElement('div', { className: 'trinity-badge-preview' },
                wp.element.createElement(wp.blockEditor.RichText, {
                    tagName: 'span',
                    className: `badge bg-${style}${pill ? ' rounded-pill' : ''}`,
                    value: text,
                    onChange: (value) => setAttributes({ text: value }),
                    placeholder: 'Badge text...'
                })
            )
        ];
    },
    save: function() {
        return null; // Server-side rendering
    }
});

// Carousel Block
wp.blocks.registerBlockType('trinity/carousel', {
    title: 'Bootstrap Carousel',
    icon: 'images-alt2',
    category: 'trinity-blocks',
    attributes: {
        images: {
            type: 'array',
            default: []
        },
        showControls: {
            type: 'boolean',
            default: true
        },
        showIndicators: {
            type: 'boolean',
            default: true
        },
        autoplay: {
            type: 'boolean',
            default: true
        },
        imageId: {
            type: 'number',
            default: 0
        },
        fullWidth: {
            type: 'boolean',
            default: false
        },
        height: {
            type: 'string',
            default: '400px'
        },
        fade: {
            type: 'boolean',
            default: false
        },
        interval: {
            type: 'number',
            default: 5000
        }
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { images, showControls, showIndicators, autoplay, imageId, fullWidth, height, fade, interval } = attributes;

        return [
            wp.element.createElement(wp.blockEditor.InspectorControls, null,
                wp.element.createElement(wp.components.PanelBody, { title: 'Carousel Settings' },
                    wp.element.createElement(wp.components.ToggleControl, {
                        label: 'Show Controls',
                        checked: showControls,
                        onChange: (value) => setAttributes({ showControls: value })
                    }),
                    wp.element.createElement(wp.components.ToggleControl, {
                        label: 'Show Indicators',
                        checked: showIndicators,
                        onChange: (value) => setAttributes({ showIndicators: value })
                    }),
                    wp.element.createElement(wp.components.ToggleControl, {
                        label: 'Autoplay',
                        checked: autoplay,
                        onChange: (value) => setAttributes({ autoplay: value })
                    }),
                    wp.element.createElement(wp.components.ToggleControl, {
                        label: 'Full Width',
                        checked: fullWidth,
                        onChange: (value) => setAttributes({ fullWidth: value })
                    }),
                    wp.element.createElement(wp.components.ToggleControl, {
                        label: 'Fade Transition',
                        checked: fade,
                        onChange: (value) => setAttributes({ fade: value })
                    }),
                    wp.element.createElement(wp.components.TextControl, {
                        label: 'Height',
                        value: height,
                        onChange: (value) => setAttributes({ height: value })
                    }),
                    wp.element.createElement(wp.components.RangeControl, {
                        label: 'Interval (ms)',
                        value: interval,
                        min: 1000,
                        max: 10000,
                        step: 500,
                        onChange: (value) => setAttributes({ interval: value })
                    }),
                    wp.element.createElement(wp.blockEditor.MediaUpload, {
                        onSelect: (media) => setAttributes({ imageId: media.id }),
                        allowedTypes: ['image'],
                        value: imageId,
                        render: ({ open }) => (
                            wp.element.createElement(wp.components.Button, {
                                onClick: open,
                                isPrimary: true
                            }, imageId ? 'Change Image' : 'Select Image')
                        )
                    })
                )
            ),
            wp.element.createElement('div', { className: 'trinity-block-placeholder' },
                wp.element.createElement('div', { className: 'bootstrap-icon' }, '🎠'),
                wp.element.createElement('h3', null, 'Bootstrap Carousel'),
                wp.element.createElement('p', null, `Controls: ${showControls ? 'Yes' : 'No'}, Indicators: ${showIndicators ? 'Yes' : 'No'}, Autoplay: ${autoplay ? 'Yes' : 'No'}`),
                wp.element.createElement('p', null, `Full Width: ${fullWidth ? 'Yes' : 'No'}, Fade: ${fade ? 'Yes' : 'No'}, Height: ${height}`)
            )
        ];
    },
    save: function() {
        return null; // Server-side rendering
    }
});

// Button Group Block
wp.blocks.registerBlockType('trinity/button-group', {
    title: 'Bootstrap Button Group',
    icon: 'editor-ul',
    category: 'trinity-blocks',
    attributes: {
        buttons: {
            type: 'array',
            default: [
                { text: 'Button 1', style: 'primary' },
                { text: 'Button 2', style: 'secondary' },
                { text: 'Button 3', style: 'success' }
            ]
        },
        size: {
            type: 'string',
            default: ''
        },
        vertical: {
            type: 'boolean',
            default: false
        }
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { buttons, size, vertical } = attributes;

        return [
            wp.element.createElement(wp.blockEditor.InspectorControls, null,
                wp.element.createElement(wp.components.PanelBody, { title: 'Button Group Settings' },
                    wp.element.createElement(wp.components.SelectControl, {
                        label: 'Size',
                        value: size,
                        options: [
                            { label: 'Default', value: '' },
                            { label: 'Large', value: 'btn-group-lg' },
                            { label: 'Small', value: 'btn-group-sm' }
                        ],
                        onChange: (value) => setAttributes({ size: value })
                    }),
                    wp.element.createElement(wp.components.ToggleControl, {
                        label: 'Vertical Layout',
                        checked: vertical,
                        onChange: (value) => setAttributes({ vertical: value })
                    })
                )
            ),
            wp.element.createElement('div', { className: 'trinity-block-placeholder' },
                wp.element.createElement('div', { className: 'bootstrap-icon' }, '🔘'),
                wp.element.createElement('h3', null, 'Bootstrap Button Group'),
                wp.element.createElement('p', null, `${buttons.length} buttons, ${vertical ? 'Vertical' : 'Horizontal'}${size ? ', ' + size : ''}`)
            )
        ];
    },
    save: function() {
        return null; // Server-side rendering
    }
});

// Tabs Block
wp.blocks.registerBlockType('trinity/tabs', {
    title: 'Bootstrap Tabs',
    icon: 'index-card',
    category: 'trinity-blocks',
    attributes: {
        tabs: {
            type: 'array',
            default: [
                { title: 'Tab 1', content: 'Content for tab 1' },
                { title: 'Tab 2', content: 'Content for tab 2' },
                { title: 'Tab 3', content: 'Content for tab 3' }
            ]
        },
        style: {
            type: 'string',
            default: 'tabs'
        }
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { tabs, style } = attributes;

        return [
            wp.element.createElement(wp.blockEditor.InspectorControls, null,
                wp.element.createElement(wp.components.PanelBody, { title: 'Tabs Settings' },
                    wp.element.createElement(wp.components.SelectControl, {
                        label: 'Style',
                        value: style,
                        options: [
                            { label: 'Tabs', value: 'tabs' },
                            { label: 'Pills', value: 'pills' }
                        ],
                        onChange: (value) => setAttributes({ style: value })
                    })
                )
            ),
            wp.element.createElement('div', { className: 'trinity-block-placeholder' },
                wp.element.createElement('div', { className: 'bootstrap-icon' }, '📑'),
                wp.element.createElement('h3', null, 'Bootstrap Tabs'),
                wp.element.createElement('p', null, `${tabs.length} tabs (${style} style)`)
            )
        ];
    },
    save: function() {
        return null; // Server-side rendering
    }
});

// Scrollspy Block
registerBlockType('trinity/scrollspy', {
    title: 'Bootstrap Scrollspy',
    icon: 'list-view',
    category: 'trinity-blocks',
    attributes: {
        targetId: {
            type: 'string',
            default: 'scrollspy-content'
        },
        navItems: {
            type: 'array',
            default: [
                { label: 'Item 1', target: 'item-1' },
                { label: 'Item 2', target: 'item-2' },
                { label: 'Item 3', target: 'item-3' }
            ]
        },
        offset: {
            type: 'number',
            default: 0
        },
        smooth: {
            type: 'boolean',
            default: true
        },
        navStyle: {
            type: 'string',
            default: 'pills'
        }
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { targetId, navItems, offset, smooth, navStyle } = attributes;

        return [
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Scrollspy Settings' },
                    wp.element.createElement(TextControl, {
                        label: 'Target ID',
                        value: targetId,
                        onChange: (value) => setAttributes({ targetId: value })
                    }),
                    wp.element.createElement(SelectControl, {
                        label: 'Navigation Style',
                        value: navStyle,
                        options: [
                            { label: 'Pills', value: 'pills' },
                            { label: 'Tabs', value: 'tabs' }
                        ],
                        onChange: (value) => setAttributes({ navStyle: value })
                    }),
                    wp.element.createElement(RangeControl, {
                        label: 'Offset',
                        value: offset,
                        min: 0,
                        max: 100,
                        onChange: (value) => setAttributes({ offset: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Smooth Scrolling',
                        checked: smooth,
                        onChange: (value) => setAttributes({ smooth: value })
                    })
                )
            ),
            wp.element.createElement('div', { className: 'trinity-block-placeholder' },
                wp.element.createElement('div', { className: 'bootstrap-icon' }, '📍'),
                wp.element.createElement('h3', null, 'Bootstrap Scrollspy'),
                wp.element.createElement('p', null, `${navItems.length} navigation items (${navStyle} style)`)
            )
        ];
    },
    save: function() {
        return null; // Server-side rendering
    }
});

// List Group Block
registerBlockType('trinity/list-group', {
    title: 'Bootstrap List Group',
    icon: 'editor-ul',
    category: 'trinity-blocks',
    attributes: {
        items: {
            type: 'array',
            default: [
                { text: 'First item', active: false, disabled: false, variant: '', link: '' },
                { text: 'Second item', active: true, disabled: false, variant: '', link: '' },
                { text: 'Third item', active: false, disabled: false, variant: '', link: '' }
            ]
        },
        flush: {
            type: 'boolean',
            default: false
        },
        numbered: {
            type: 'boolean',
            default: false
        },
        horizontal: {
            type: 'boolean',
            default: false
        }
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { items, flush, numbered, horizontal } = attributes;

        return [
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'List Group Settings' },
                    wp.element.createElement(ToggleControl, {
                        label: 'Flush',
                        checked: flush,
                        onChange: (value) => setAttributes({ flush: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Numbered',
                        checked: numbered,
                        onChange: (value) => setAttributes({ numbered: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Horizontal',
                        checked: horizontal,
                        onChange: (value) => setAttributes({ horizontal: value })
                    })
                )
            ),
            wp.element.createElement('div', { className: 'trinity-block-placeholder' },
                wp.element.createElement('div', { className: 'bootstrap-icon' }, '📝'),
                wp.element.createElement('h3', null, 'Bootstrap List Group'),
                wp.element.createElement('p', null, `${items.length} items${flush ? ' (flush)' : ''}${numbered ? ' (numbered)' : ''}${horizontal ? ' (horizontal)' : ''}`)
            )
        ];
    },
    save: function() {
        return null; // Server-side rendering
    }
});

// Enhanced Modal Block
registerBlockType('trinity/enhanced-modal', {
    title: 'Bootstrap Enhanced Modal',
    icon: 'admin-page',
    category: 'trinity-blocks',
    attributes: {
        modalId: {
            type: 'string',
            default: ''
        },
        title: {
            type: 'string',
            default: 'Modal title'
        },
        content: {
            type: 'string',
            default: 'Modal body text goes here.'
        },
        size: {
            type: 'string',
            default: ''
        },
        centered: {
            type: 'boolean',
            default: false
        },
        scrollable: {
            type: 'boolean',
            default: false
        },
        fullscreen: {
            type: 'string',
            default: ''
        },
        backdrop: {
            type: 'string',
            default: 'true'
        },
        triggerText: {
            type: 'string',
            default: 'Launch demo modal'
        },
        triggerVariant: {
            type: 'string',
            default: 'primary'
        },
        showFooter: {
            type: 'boolean',
            default: true
        },
        primaryButtonText: {
            type: 'string',
            default: 'Save changes'
        },
        secondaryButtonText: {
            type: 'string',
            default: 'Close'
        }
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { modalId, title, content, size, centered, scrollable, fullscreen, backdrop, triggerText, triggerVariant, showFooter, primaryButtonText, secondaryButtonText } = attributes;

        return [
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Modal Settings' },
                    wp.element.createElement(TextControl, {
                        label: 'Modal ID',
                        value: modalId,
                        onChange: (value) => setAttributes({ modalId: value })
                    }),
                    wp.element.createElement(TextControl, {
                        label: 'Title',
                        value: title,
                        onChange: (value) => setAttributes({ title: value })
                    }),
                    wp.element.createElement(TextControl, {
                        label: 'Content',
                        value: content,
                        onChange: (value) => setAttributes({ content: value })
                    }),
                    wp.element.createElement(SelectControl, {
                        label: 'Size',
                        value: size,
                        options: [
                            { label: 'Default', value: '' },
                            { label: 'Small', value: 'sm' },
                            { label: 'Large', value: 'lg' },
                            { label: 'Extra Large', value: 'xl' }
                        ],
                        onChange: (value) => setAttributes({ size: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Centered',
                        checked: centered,
                        onChange: (value) => setAttributes({ centered: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Scrollable',
                        checked: scrollable,
                        onChange: (value) => setAttributes({ scrollable: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Show Footer',
                        checked: showFooter,
                        onChange: (value) => setAttributes({ showFooter: value })
                    })
                ),
                wp.element.createElement(PanelBody, { title: 'Trigger Button' },
                    wp.element.createElement(TextControl, {
                        label: 'Trigger Text',
                        value: triggerText,
                        onChange: (value) => setAttributes({ triggerText: value })
                    }),
                    wp.element.createElement(SelectControl, {
                        label: 'Trigger Variant',
                        value: triggerVariant,
                        options: [
                            { label: 'Primary', value: 'primary' },
                            { label: 'Secondary', value: 'secondary' },
                            { label: 'Success', value: 'success' },
                            { label: 'Danger', value: 'danger' },
                            { label: 'Warning', value: 'warning' },
                            { label: 'Info', value: 'info' }
                        ],
                        onChange: (value) => setAttributes({ triggerVariant: value })
                    })
                )
            ),
            wp.element.createElement('div', { className: 'trinity-block-placeholder' },
                wp.element.createElement('div', { className: 'bootstrap-icon' }, '🪟'),
                wp.element.createElement('h3', null, 'Bootstrap Enhanced Modal'),
                wp.element.createElement('p', null, `"${title}" - ${size ? size.toUpperCase() + ' ' : ''}${centered ? 'Centered ' : ''}Modal`)
            )
        ];
    },
    save: function() {
        return null; // Server-side rendering
    }
});

// Bootstrap Table Block
registerBlockType('trinity/table', {
    title: 'Bootstrap Table',
    icon: 'editor-table',
    category: 'trinity-blocks',
    attributes: {
        headers: {
            type: 'array',
            default: ['First', 'Last', 'Handle']
        },
        rows: {
            type: 'array',
            default: [
                ['Mark', 'Otto', '@mdo'],
                ['Jacob', 'Thornton', '@fat'],
                ['Larry', 'the Bird', '@twitter']
            ]
        },
        striped: {
            type: 'boolean',
            default: false
        },
        hover: {
            type: 'boolean',
            default: false
        },
        bordered: {
            type: 'boolean',
            default: false
        },
        borderless: {
            type: 'boolean',
            default: false
        },
        small: {
            type: 'boolean',
            default: false
        },
        responsive: {
            type: 'boolean',
            default: true
        },
        variant: {
            type: 'string',
            default: ''
        },
        caption: {
            type: 'string',
            default: ''
        }
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { headers, rows, striped, hover, bordered, borderless, small, responsive, variant, caption } = attributes;

        return [
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Table Settings' },
                    wp.element.createElement(ToggleControl, {
                        label: 'Striped',
                        checked: striped,
                        onChange: (value) => setAttributes({ striped: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Hover',
                        checked: hover,
                        onChange: (value) => setAttributes({ hover: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Bordered',
                        checked: bordered,
                        onChange: (value) => setAttributes({ bordered: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Borderless',
                        checked: borderless,
                        onChange: (value) => setAttributes({ borderless: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Small',
                        checked: small,
                        onChange: (value) => setAttributes({ small: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Responsive',
                        checked: responsive,
                        onChange: (value) => setAttributes({ responsive: value })
                    }),
                    wp.element.createElement(SelectControl, {
                        label: 'Variant',
                        value: variant,
                        options: [
                            { label: 'Default', value: '' },
                            { label: 'Dark', value: 'dark' },
                            { label: 'Primary', value: 'primary' },
                            { label: 'Secondary', value: 'secondary' },
                            { label: 'Success', value: 'success' },
                            { label: 'Danger', value: 'danger' },
                            { label: 'Warning', value: 'warning' },
                            { label: 'Info', value: 'info' }
                        ],
                        onChange: (value) => setAttributes({ variant: value })
                    }),
                    wp.element.createElement(TextControl, {
                        label: 'Caption',
                        value: caption,
                        onChange: (value) => setAttributes({ caption: value })
                    })
                )
            ),
            wp.element.createElement('div', { className: 'trinity-block-placeholder' },
                wp.element.createElement('div', { className: 'bootstrap-icon' }, '📊'),
                wp.element.createElement('h3', null, 'Bootstrap Table'),
                wp.element.createElement('p', null, `${headers.length} columns, ${rows.length} rows${hover ? ' (hover)' : ''}${striped ? ' (striped)' : ''}`)
            )
        ];
    },
    save: function() {
        return null; // Server-side rendering
    }
});
