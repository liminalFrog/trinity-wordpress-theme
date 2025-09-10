/**
 * Trinity Bootstrap Blocks
 */

const { registerBlockType } = wp.blocks;
const { InspectorControls, RichText, MediaUpload } = wp.blockEditor;
const { PanelBody, TextControl, TextareaControl, SelectControl, ToggleControl, RangeControl, Button, ButtonGroup, IconButton, Toolbar } = wp.components;
const { useState, Fragment } = wp.element;

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
        },
        showIcon: {
            type: 'boolean',
            default: false
        }
    },
    edit: function(props) {
        const { attributes, setAttributes, isSelected } = props;
        const { content, type, dismissible, heading, showIcon } = attributes;

        // Alert type options with icons and colors
        const alertTypes = [
            { label: '🔵 Primary', value: 'primary', icon: 'info' },
            { label: '⚪ Secondary', value: 'secondary', icon: 'admin-generic' },
            { label: '✅ Success', value: 'success', icon: 'yes-alt' },
            { label: '❌ Danger', value: 'danger', icon: 'dismiss' },
            { label: '⚠️ Warning', value: 'warning', icon: 'warning' },
            { label: 'ℹ️ Info', value: 'info', icon: 'info-outline' }
        ];

        const currentType = alertTypes.find(t => t.value === type) || alertTypes[0];

        return wp.element.createElement(Fragment, null,
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Alert Settings', initialOpen: true },
                    wp.element.createElement('div', { style: { marginBottom: '16px' } },
                        wp.element.createElement('label', { style: { display: 'block', marginBottom: '8px', fontWeight: 'bold' } }, 'Alert Type'),
                        wp.element.createElement(ButtonGroup, null,
                            alertTypes.slice(0, 3).map(alertType =>
                                wp.element.createElement(Button, {
                                    key: alertType.value,
                                    isPrimary: type === alertType.value,
                                    isSecondary: type !== alertType.value,
                                    onClick: () => setAttributes({ type: alertType.value }),
                                    style: { fontSize: '12px' }
                                }, alertType.label)
                            )
                        ),
                        wp.element.createElement('br'),
                        wp.element.createElement('br'),
                        wp.element.createElement(ButtonGroup, null,
                            alertTypes.slice(3).map(alertType =>
                                wp.element.createElement(Button, {
                                    key: alertType.value,
                                    isPrimary: type === alertType.value,
                                    isSecondary: type !== alertType.value,
                                    onClick: () => setAttributes({ type: alertType.value }),
                                    style: { fontSize: '12px' }
                                }, alertType.label)
                            )
                        )
                    ),
                    wp.element.createElement(ToggleControl, {
                        label: 'Show Dismissible Close Button',
                        checked: dismissible,
                        onChange: (value) => setAttributes({ dismissible: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Show Icon',
                        checked: showIcon,
                        onChange: (value) => setAttributes({ showIcon: value })
                    })
                )
            ),
            wp.element.createElement('div', { 
                className: 'trinity-alert-preview',
                style: { 
                    position: 'relative',
                    border: isSelected ? '2px solid #007cba' : '1px solid transparent',
                    borderRadius: '4px',
                    padding: isSelected ? '8px' : '0'
                }
            },
                // Quick type selector toolbar
                isSelected && wp.element.createElement('div', {
                    style: {
                        position: 'absolute',
                        top: '-40px',
                        left: '0',
                        background: 'white',
                        border: '1px solid #ddd',
                        borderRadius: '4px',
                        padding: '4px',
                        boxShadow: '0 2px 8px rgba(0,0,0,0.1)',
                        zIndex: 100
                    }
                },
                    alertTypes.map(alertType =>
                        wp.element.createElement(Button, {
                            key: alertType.value,
                            isSmall: true,
                            isPrimary: type === alertType.value,
                            isSecondary: type !== alertType.value,
                            onClick: () => setAttributes({ type: alertType.value }),
                            style: { margin: '2px', fontSize: '11px' },
                            title: `Switch to ${alertType.label}`
                        }, alertType.value.charAt(0).toUpperCase())
                    )
                ),
                
                wp.element.createElement('div', {
                    className: `alert alert-${type}${dismissible ? ' alert-dismissible' : ''}`,
                    style: { marginBottom: 0, position: 'relative' }
                },
                    // Icon
                    showIcon && wp.element.createElement('span', {
                        className: `dashicons dashicons-${currentType.icon}`,
                        style: { 
                            marginRight: '8px',
                            fontSize: '16px',
                            verticalAlign: 'middle'
                        }
                    }),
                    
                    // Editable heading
                    wp.element.createElement(RichText, {
                        tagName: 'h4',
                        className: 'alert-heading',
                        value: heading,
                        onChange: (value) => setAttributes({ heading: value }),
                        placeholder: 'Alert heading (optional)...',
                        style: { 
                            display: heading || isSelected ? 'block' : 'none',
                            margin: '0 0 8px 0',
                            fontSize: '1.25rem',
                            fontWeight: 'bold'
                        }
                    }),
                    
                    // Editable content
                    wp.element.createElement(RichText, {
                        tagName: 'div',
                        value: content,
                        onChange: (value) => setAttributes({ content: value }),
                        placeholder: 'Enter your alert message here...',
                        style: { margin: 0 }
                    }),
                    
                    // Close button
                    dismissible && wp.element.createElement('button', {
                        type: 'button',
                        className: 'btn-close',
                        'aria-label': 'Close',
                        style: { opacity: 0.7 }
                    }),
                    
                    // Floating controls when selected
                    isSelected && wp.element.createElement('div', {
                        style: {
                            position: 'absolute',
                            top: '8px',
                            right: dismissible ? '40px' : '8px',
                            display: 'flex',
                            gap: '4px'
                        }
                    },
                        wp.element.createElement(Button, {
                            isSmall: true,
                            onClick: () => setAttributes({ dismissible: !dismissible }),
                            title: dismissible ? 'Remove close button' : 'Add close button'
                        }, dismissible ? '✕' : '+'),
                        wp.element.createElement(Button, {
                            isSmall: true,
                            onClick: () => setAttributes({ showIcon: !showIcon }),
                            title: showIcon ? 'Hide icon' : 'Show icon'
                        }, showIcon ? '🚫' : '🎯')
                    )
                )
            )
        );
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
        },
        headerFontSize: {
            type: 'string',
            default: '1.25rem'
        },
        headerFontWeight: {
            type: 'string',
            default: '500'
        },
        headerFontFamily: {
            type: 'string',
            default: ''
        },
        contentFontSize: {
            type: 'string',
            default: '1rem'
        },
        contentFontWeight: {
            type: 'string',
            default: '400'
        },
        contentFontFamily: {
            type: 'string',
            default: ''
        }
    },
    edit: function(props) {
        const { attributes, setAttributes, isSelected } = props;
        const { items, flush, headerFontSize, headerFontWeight, headerFontFamily, contentFontSize, contentFontWeight, contentFontFamily } = attributes;

        const addItem = () => {
            const newItems = [...items, {
                title: `Accordion Item #${items.length + 1}`,
                content: 'Click here to edit accordion content...',
                open: false
            }];
            setAttributes({ items: newItems });
        };

        const removeItem = (index) => {
            if (items.length > 1) {
                const newItems = items.filter((_, i) => i !== index);
                setAttributes({ items: newItems });
            }
        };

        const updateItem = (index, field, value) => {
            const newItems = [...items];
            newItems[index][field] = value;
            setAttributes({ items: newItems });
        };

        const moveItem = (index, direction) => {
            const newItems = [...items];
            const targetIndex = direction === 'up' ? index - 1 : index + 1;
            
            if (targetIndex >= 0 && targetIndex < items.length) {
                [newItems[index], newItems[targetIndex]] = [newItems[targetIndex], newItems[index]];
                setAttributes({ items: newItems });
            }
        };

        return wp.element.createElement(Fragment, null,
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Accordion Settings', initialOpen: true },
                    wp.element.createElement(ToggleControl, {
                        label: 'Flush Design (No borders)',
                        checked: flush,
                        onChange: (value) => setAttributes({ flush: value })
                    }),
                    wp.element.createElement('div', { style: { marginTop: '16px' } },
                        wp.element.createElement(Button, {
                            isPrimary: true,
                            onClick: addItem,
                            icon: 'plus-alt'
                        }, `Add Item (${items.length + 1})`)
                    )
                ),
                wp.element.createElement(PanelBody, { title: 'Header Typography', initialOpen: false },
                    wp.element.createElement(TextControl, {
                        label: 'Font Size',
                        value: headerFontSize,
                        onChange: (value) => setAttributes({ headerFontSize: value }),
                        placeholder: '1.25rem'
                    }),
                    wp.element.createElement(SelectControl, {
                        label: 'Font Weight',
                        value: headerFontWeight,
                        options: [
                            { label: 'Light (300)', value: '300' },
                            { label: 'Normal (400)', value: '400' },
                            { label: 'Medium (500)', value: '500' },
                            { label: 'Semi-bold (600)', value: '600' },
                            { label: 'Bold (700)', value: '700' },
                            { label: 'Extra-bold (800)', value: '800' }
                        ],
                        onChange: (value) => setAttributes({ headerFontWeight: value })
                    }),
                    wp.element.createElement(TextControl, {
                        label: 'Font Family',
                        value: headerFontFamily,
                        onChange: (value) => setAttributes({ headerFontFamily: value }),
                        placeholder: 'inherit'
                    })
                ),
                wp.element.createElement(PanelBody, { title: 'Content Typography', initialOpen: false },
                    wp.element.createElement(TextControl, {
                        label: 'Font Size',
                        value: contentFontSize,
                        onChange: (value) => setAttributes({ contentFontSize: value }),
                        placeholder: '1rem'
                    }),
                    wp.element.createElement(SelectControl, {
                        label: 'Font Weight',
                        value: contentFontWeight,
                        options: [
                            { label: 'Light (300)', value: '300' },
                            { label: 'Normal (400)', value: '400' },
                            { label: 'Medium (500)', value: '500' },
                            { label: 'Semi-bold (600)', value: '600' },
                            { label: 'Bold (700)', value: '700' },
                            { label: 'Extra-bold (800)', value: '800' }
                        ],
                        onChange: (value) => setAttributes({ contentFontWeight: value })
                    }),
                    wp.element.createElement(TextControl, {
                        label: 'Font Family',
                        value: contentFontFamily,
                        onChange: (value) => setAttributes({ contentFontFamily: value }),
                        placeholder: 'inherit'
                    })
                )
            ),
            wp.element.createElement('div', { 
                className: 'trinity-accordion-preview',
                style: {
                    border: isSelected ? '2px solid #007cba' : '1px solid transparent',
                    borderRadius: '4px',
                    padding: isSelected ? '8px' : '0',
                    position: 'relative'
                }
            },
                // Add item button at top when selected
                isSelected && wp.element.createElement('div', {
                    style: {
                        textAlign: 'center',
                        marginBottom: '12px',
                        padding: '8px',
                        background: '#f0f0f0',
                        borderRadius: '4px'
                    }
                },
                    wp.element.createElement(Button, {
                        isPrimary: true,
                        isSmall: true,
                        onClick: addItem,
                        icon: 'plus-alt'
                    }, 'Add New Item')
                ),

                wp.element.createElement('div', { className: `accordion${flush ? ' accordion-flush' : ''}` },
                    items.map((item, index) => 
                        wp.element.createElement('div', { 
                            key: index, 
                            className: 'accordion-item',
                            style: { position: 'relative' }
                        },
                            // Item controls when selected
                            isSelected && wp.element.createElement('div', {
                                style: {
                                    position: 'absolute',
                                    top: '8px',
                                    right: '8px',
                                    display: 'flex',
                                    gap: '4px',
                                    zIndex: 10,
                                    background: 'rgba(255,255,255,0.9)',
                                    padding: '4px',
                                    borderRadius: '4px'
                                }
                            },
                                wp.element.createElement(Button, {
                                    isSmall: true,
                                    onClick: () => moveItem(index, 'up'),
                                    disabled: index === 0,
                                    title: 'Move up',
                                    icon: 'arrow-up-alt'
                                }),
                                wp.element.createElement(Button, {
                                    isSmall: true,
                                    onClick: () => moveItem(index, 'down'),
                                    disabled: index === items.length - 1,
                                    title: 'Move down',
                                    icon: 'arrow-down-alt'
                                }),
                                wp.element.createElement(Button, {
                                    isSmall: true,
                                    onClick: () => updateItem(index, 'open', !item.open),
                                    title: item.open ? 'Collapse' : 'Expand',
                                    icon: item.open ? 'arrow-up' : 'arrow-down'
                                }),
                                items.length > 1 && wp.element.createElement(Button, {
                                    isSmall: true,
                                    isDestructive: true,
                                    onClick: () => removeItem(index),
                                    title: 'Remove item',
                                    icon: 'trash'
                                })
                            ),

                            wp.element.createElement('h2', { className: 'accordion-header' },
                                wp.element.createElement('button', {
                                    className: `accordion-button${item.open ? '' : ' collapsed'}`,
                                    type: 'button',
                                    style: {
                                        fontSize: headerFontSize,
                                        fontWeight: headerFontWeight,
                                        fontFamily: headerFontFamily || 'inherit',
                                        padding: '1rem 1.25rem',
                                        border: 'none',
                                        background: 'transparent',
                                        textAlign: 'left',
                                        width: '100%',
                                        cursor: 'pointer'
                                    },
                                    onClick: () => updateItem(index, 'open', !item.open)
                                },
                                    wp.element.createElement(RichText, {
                                        tagName: 'span',
                                        value: item.title,
                                        onChange: (value) => updateItem(index, 'title', value),
                                        placeholder: `Accordion Item #${index + 1}`,
                                        style: { outline: 'none' }
                                    })
                                )
                            ),
                            item.open && wp.element.createElement('div', { 
                                className: 'accordion-collapse collapse show'
                            },
                                wp.element.createElement('div', { 
                                    className: 'accordion-body',
                                    style: {
                                        fontSize: contentFontSize,
                                        fontWeight: contentFontWeight,
                                        fontFamily: contentFontFamily || 'inherit'
                                    }
                                },
                                    wp.element.createElement(RichText, {
                                        tagName: 'div',
                                        value: item.content,
                                        onChange: (value) => updateItem(index, 'content', value),
                                        placeholder: 'Click here to edit accordion content...',
                                        style: { 
                                            outline: 'none',
                                            minHeight: '50px'
                                        }
                                    })
                                )
                            )
                        )
                    )
                )
            )
        );
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
        slides: {
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
        autoSlide: {
            type: 'boolean',
            default: false
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
        const { attributes, setAttributes, isSelected } = props;
        const { slides, showControls, showIndicators, autoSlide, fullWidth, height, fade, interval } = attributes;

        const addSlide = () => {
            const newSlides = [...slides, {
                imageId: 0,
                image: '',
                imagePosition: 'center center',
                title: '',
                content: '',
                link: '',
                linkText: ''
            }];
            setAttributes({ slides: newSlides });
        };

        const removeSlide = (index) => {
            const newSlides = slides.filter((_, i) => i !== index);
            setAttributes({ slides: newSlides });
        };

        const updateSlide = (index, field, value) => {
            const newSlides = [...slides];
            newSlides[index] = { ...newSlides[index], [field]: value };
            setAttributes({ slides: newSlides });
        };

        const selectImage = (index) => {
            const frame = wp.media({
                title: 'Select Carousel Image',
                button: { text: 'Use Image' },
                multiple: false
            });

            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();
                updateSlide(index, 'imageId', attachment.id);
                updateSlide(index, 'image', attachment.url);
            });

            frame.open();
        };

        return wp.element.createElement(Fragment, null,
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Carousel Settings' },
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
                        label: 'Auto Slide',
                        checked: autoSlide,
                        onChange: (value) => setAttributes({ autoSlide: value })
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
                    })
                ),
                wp.element.createElement(wp.components.PanelBody, { title: 'Slides', initialOpen: false },
                    wp.element.createElement(wp.components.Button, {
                        isPrimary: true,
                        onClick: addSlide
                    }, 'Add Slide'),
                    slides.map((slide, index) => 
                        wp.element.createElement('div', { 
                            key: index, 
                            style: { 
                                padding: '1rem', 
                                marginTop: '1rem', 
                                border: '1px solid #ddd', 
                                borderRadius: '4px' 
                            } 
                        },
                            wp.element.createElement('h4', null, `Slide ${index + 1}`),
                            wp.element.createElement(wp.components.Button, {
                                isSecondary: true,
                                onClick: () => selectImage(index)
                            }, slide.image ? 'Change Image' : 'Select Image'),
                            slide.image && wp.element.createElement('img', {
                                src: slide.image,
                                style: { width: '100%', maxWidth: '200px', height: 'auto', marginTop: '0.5rem' }
                            }),
                            slide.image && wp.element.createElement(wp.components.SelectControl, {
                                label: 'Image Position',
                                value: slide.imagePosition || 'center center',
                                options: [
                                    { label: 'Top Left', value: 'top left' },
                                    { label: 'Top Center', value: 'top center' },
                                    { label: 'Top Right', value: 'top right' },
                                    { label: 'Center Left', value: 'center left' },
                                    { label: 'Center Center', value: 'center center' },
                                    { label: 'Center Right', value: 'center right' },
                                    { label: 'Bottom Left', value: 'bottom left' },
                                    { label: 'Bottom Center', value: 'bottom center' },
                                    { label: 'Bottom Right', value: 'bottom right' }
                                ],
                                onChange: (value) => updateSlide(index, 'imagePosition', value)
                            }),
                            wp.element.createElement(wp.components.TextControl, {
                                label: 'Title',
                                value: slide.title || '',
                                onChange: (value) => updateSlide(index, 'title', value)
                            }),
                            wp.element.createElement(wp.components.TextareaControl, {
                                label: 'Content',
                                value: slide.content || '',
                                onChange: (value) => updateSlide(index, 'content', value)
                            }),
                            wp.element.createElement(wp.components.TextControl, {
                                label: 'Link URL',
                                value: slide.link || '',
                                onChange: (value) => updateSlide(index, 'link', value)
                            }),
                            wp.element.createElement(wp.components.TextControl, {
                                label: 'Link Text',
                                value: slide.linkText || '',
                                onChange: (value) => updateSlide(index, 'linkText', value)
                            }),
                            wp.element.createElement(wp.components.Button, {
                                isDestructive: true,
                                onClick: () => removeSlide(index),
                                style: { marginTop: '0.5rem' }
                            }, 'Remove Slide')
                        )
                    )
                )
            ),
            
            // Interactive Carousel Preview
            wp.element.createElement('div', {
                className: 'trinity-carousel-preview',
                style: {
                    border: isSelected ? '2px solid #007cba' : '1px solid transparent',
                    borderRadius: '4px',
                    padding: isSelected ? '8px' : '0',
                    position: 'relative'
                }
            },
                slides.length > 0 ? 
                    wp.element.createElement('div', {
                        className: `carousel slide${fade ? ' carousel-fade' : ''}`,
                        style: { 
                            maxWidth: fullWidth ? '100vw' : '600px',
                            marginLeft: fullWidth ? 'calc(50% - 50vw)' : 'auto',
                            marginRight: fullWidth ? 'calc(50% - 50vw)' : 'auto',
                            height: height || '400px',
                            background: '#f8f9fa',
                            border: '1px solid #dee2e6',
                            borderRadius: '0.25rem',
                            overflow: 'hidden'
                        }
                    },
                        // Carousel indicators
                        showIndicators && slides.length > 1 && wp.element.createElement('div', {
                            className: 'carousel-indicators',
                            style: { position: 'absolute', bottom: '10px', left: '50%', transform: 'translateX(-50%)', zIndex: '10' }
                        },
                            slides.map((_, index) => 
                                wp.element.createElement('button', {
                                    key: index,
                                    type: 'button',
                                    className: index === 0 ? 'active' : '',
                                    style: {
                                        width: '30px',
                                        height: '3px',
                                        margin: '0 3px',
                                        background: index === 0 ? '#fff' : 'rgba(255,255,255,0.5)',
                                        border: 'none',
                                        borderRadius: '1px'
                                    }
                                })
                            )
                        ),
                        
                        // Carousel inner
                        wp.element.createElement('div', {
                            className: 'carousel-inner',
                            style: { height: '100%' }
                        },
                            slides.map((slide, index) => 
                                wp.element.createElement('div', {
                                    key: index,
                                    className: `carousel-item${index === 0 ? ' active' : ''}`,
                                    style: { 
                                        height: '100%',
                                        position: 'relative',
                                        background: slide.image ? `url(${slide.image})` : '#e9ecef',
                                        backgroundSize: 'cover',
                                        backgroundPosition: slide.imagePosition || 'center center',
                                        display: index === 0 ? 'block' : 'none'
                                    }
                                },
                                    (slide.title || slide.content) && wp.element.createElement('div', {
                                        className: 'carousel-caption d-block',
                                        style: {
                                            position: 'absolute',
                                            bottom: '20px',
                                            left: '15%',
                                            right: '15%',
                                            paddingTop: '20px',
                                            paddingBottom: '20px',
                                            color: '#fff',
                                            textAlign: 'center',
                                            background: 'rgba(0,0,0,0.5)',
                                            borderRadius: '4px'
                                        }
                                    },
                                        slide.title && wp.element.createElement(RichText, {
                                            tagName: 'h5',
                                            value: slide.title,
                                            onChange: (value) => updateSlide(index, 'title', value),
                                            placeholder: 'Slide title...',
                                            style: { margin: '0 0 8px 0' }
                                        }),
                                        slide.content && wp.element.createElement(RichText, {
                                            tagName: 'p',
                                            value: slide.content,
                                            onChange: (value) => updateSlide(index, 'content', value),
                                            placeholder: 'Slide content...',
                                            style: { margin: '0' }
                                        })
                                    )
                                )
                            )
                        ),
                        
                        // Carousel controls
                        showControls && slides.length > 1 && wp.element.createElement(Fragment, null,
                            wp.element.createElement('button', {
                                className: 'carousel-control-prev',
                                type: 'button',
                                style: {
                                    position: 'absolute',
                                    top: '0',
                                    bottom: '0',
                                    left: '0',
                                    width: '15%',
                                    background: 'transparent',
                                    border: 'none',
                                    opacity: '0.5'
                                }
                            },
                                wp.element.createElement('span', {
                                    className: 'carousel-control-prev-icon',
                                    style: { fontSize: '20px' }
                                }, '‹')
                            ),
                            wp.element.createElement('button', {
                                className: 'carousel-control-next',
                                type: 'button',
                                style: {
                                    position: 'absolute',
                                    top: '0',
                                    bottom: '0',
                                    right: '0',
                                    width: '15%',
                                    background: 'transparent',
                                    border: 'none',
                                    opacity: '0.5'
                                }
                            },
                                wp.element.createElement('span', {
                                    className: 'carousel-control-next-icon',
                                    style: { fontSize: '20px' }
                                }, '›')
                            )
                        )
                    ) : 
                    // Empty state
                    wp.element.createElement('div', {
                        style: {
                            padding: '40px',
                            textAlign: 'center',
                            background: '#f8f9fa',
                            border: '2px dashed #dee2e6',
                            borderRadius: '4px',
                            color: '#6c757d'
                        }
                    },
                        wp.element.createElement('div', { style: { fontSize: '48px', marginBottom: '16px' } }, '🎠'),
                        wp.element.createElement('h4', { style: { marginBottom: '8px' } }, 'Bootstrap Carousel'),
                        wp.element.createElement('p', { style: { margin: '0' } }, 'Add slides to create your carousel')
                    ),
                
                // Quick add slide button when selected
                isSelected && wp.element.createElement('div', {
                    style: {
                        textAlign: 'center',
                        marginTop: '10px',
                        padding: '8px',
                        background: '#f0f0f0',
                        borderRadius: '4px'
                    }
                },
                    wp.element.createElement(Button, {
                        isPrimary: true,
                        isSmall: true,
                        onClick: addSlide,
                        icon: 'plus-alt'
                    }, 'Add Slide'),
                    slides.length > 0 && wp.element.createElement(Button, {
                        isDestructive: true,
                        isSmall: true,
                        onClick: () => removeSlide(slides.length - 1),
                        icon: 'trash',
                        style: { marginLeft: '8px' }
                    }, 'Remove Last')
                )
            )
        );
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
registerBlockType('trinity/tabs', {
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
        const { attributes, setAttributes, isSelected } = props;
        const { tabs, style } = attributes;

        const addTab = () => {
            const newTabs = [...tabs, {
                title: `Tab ${tabs.length + 1}`,
                content: `Content for tab ${tabs.length + 1}`
            }];
            setAttributes({ tabs: newTabs });
        };

        const removeTab = (index) => {
            const newTabs = tabs.filter((_, i) => i !== index);
            setAttributes({ tabs: newTabs });
        };

        const updateTab = (index, field, value) => {
            const newTabs = [...tabs];
            newTabs[index] = { ...newTabs[index], [field]: value };
            setAttributes({ tabs: newTabs });
        };

        return wp.element.createElement(Fragment, null,
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Tabs Settings' },
                    wp.element.createElement(SelectControl, {
                        label: 'Style',
                        value: style,
                        options: [
                            { label: 'Tabs', value: 'tabs' },
                            { label: 'Pills', value: 'pills' }
                        ],
                        onChange: (value) => setAttributes({ style: value })
                    })
                ),
                wp.element.createElement(PanelBody, { title: 'Tab Management', initialOpen: false },
                    wp.element.createElement(Button, {
                        isPrimary: true,
                        onClick: addTab
                    }, 'Add Tab'),
                    tabs.map((tab, index) => 
                        wp.element.createElement('div', { 
                            key: index, 
                            style: { 
                                padding: '1rem', 
                                marginTop: '1rem', 
                                border: '1px solid #ddd', 
                                borderRadius: '4px' 
                            } 
                        },
                            wp.element.createElement('h4', null, `Tab ${index + 1}`),
                            wp.element.createElement(TextControl, {
                                label: 'Title',
                                value: tab.title || '',
                                onChange: (value) => updateTab(index, 'title', value)
                            }),
                            wp.element.createElement(TextareaControl, {
                                label: 'Content',
                                value: tab.content || '',
                                onChange: (value) => updateTab(index, 'content', value)
                            }),
                            wp.element.createElement(Button, {
                                isDestructive: true,
                                onClick: () => removeTab(index),
                                style: { marginTop: '0.5rem' }
                            }, 'Remove Tab')
                        )
                    )
                )
            ),
            
            // Interactive Tabs Preview
            wp.element.createElement('div', {
                className: 'trinity-tabs-preview',
                style: {
                    border: isSelected ? '2px solid #007cba' : '1px solid transparent',
                    borderRadius: '4px',
                    padding: isSelected ? '8px' : '0',
                    position: 'relative'
                }
            },
                tabs.length > 0 ? 
                    wp.element.createElement('div', {
                        style: { maxWidth: '600px' }
                    },
                        // Tab navigation
                        wp.element.createElement('ul', {
                            className: `nav nav-${style}`,
                            style: {
                                marginBottom: '16px',
                                borderBottom: style === 'tabs' ? '1px solid #dee2e6' : 'none'
                            }
                        },
                            tabs.map((tab, index) => 
                                wp.element.createElement('li', {
                                    key: index,
                                    className: 'nav-item'
                                },
                                    wp.element.createElement(RichText, {
                                        tagName: 'button',
                                        className: `nav-link${index === 0 ? ' active' : ''}`,
                                        value: tab.title,
                                        onChange: (value) => updateTab(index, 'title', value),
                                        placeholder: `Tab ${index + 1} title...`,
                                        style: {
                                            cursor: 'text',
                                            minWidth: '80px',
                                            border: style === 'tabs' ? '1px solid transparent' : 'none',
                                            borderBottomColor: index === 0 && style === 'tabs' ? '#007cba' : 'transparent',
                                            borderRadius: style === 'pills' ? '0.25rem' : '0.25rem 0.25rem 0 0',
                                            background: index === 0 ? (style === 'pills' ? '#007cba' : 'transparent') : 'transparent',
                                            color: index === 0 && style === 'pills' ? '#fff' : '#007cba',
                                            padding: '8px 16px'
                                        }
                                    })
                                )
                            )
                        ),
                        
                        // Tab content
                        wp.element.createElement('div', {
                            className: 'tab-content',
                            style: {
                                border: style === 'tabs' ? '1px solid #dee2e6' : 'none',
                                borderTop: style === 'tabs' ? 'none' : undefined,
                                borderRadius: style === 'tabs' ? '0 0 0.25rem 0.25rem' : undefined,
                                padding: '16px',
                                background: '#fff',
                                minHeight: '120px'
                            }
                        },
                            tabs.map((tab, index) => 
                                wp.element.createElement('div', {
                                    key: index,
                                    className: `tab-pane${index === 0 ? ' active show' : ''}`,
                                    style: { display: index === 0 ? 'block' : 'none' }
                                },
                                    wp.element.createElement(RichText, {
                                        tagName: 'div',
                                        value: tab.content,
                                        onChange: (value) => updateTab(index, 'content', value),
                                        placeholder: `Content for ${tab.title || `Tab ${index + 1}`}...`,
                                        style: {
                                            cursor: 'text',
                                            minHeight: '60px'
                                        }
                                    })
                                )
                            )
                        )
                    ) : 
                    // Empty state
                    wp.element.createElement('div', {
                        style: {
                            padding: '40px',
                            textAlign: 'center',
                            background: '#f8f9fa',
                            border: '2px dashed #dee2e6',
                            borderRadius: '4px',
                            color: '#6c757d'
                        }
                    },
                        wp.element.createElement('div', { style: { fontSize: '48px', marginBottom: '16px' } }, '📑'),
                        wp.element.createElement('h4', { style: { marginBottom: '8px' } }, 'Bootstrap Tabs'),
                        wp.element.createElement('p', { style: { margin: '0' } }, 'Add tabs to create your tabbed interface')
                    ),
                
                // Quick controls when selected
                isSelected && wp.element.createElement('div', {
                    style: {
                        textAlign: 'center',
                        marginTop: '10px',
                        padding: '8px',
                        background: '#f0f0f0',
                        borderRadius: '4px'
                    }
                },
                    wp.element.createElement(Button, {
                        isPrimary: true,
                        isSmall: true,
                        onClick: addTab,
                        icon: 'plus-alt'
                    }, 'Add Tab'),
                    tabs.length > 0 && wp.element.createElement(Button, {
                        isDestructive: true,
                        isSmall: true,
                        onClick: () => removeTab(tabs.length - 1),
                        icon: 'trash',
                        style: { marginLeft: '8px' }
                    }, 'Remove Last')
                )
            )
        );
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
        const { attributes, setAttributes, isSelected } = props;
        const { items, flush, numbered, horizontal } = attributes;

        const addItem = () => {
            const newItems = [...items, {
                text: 'New item',
                active: false,
                disabled: false,
                variant: '',
                link: ''
            }];
            setAttributes({ items: newItems });
        };

        const removeItem = (index) => {
            if (items.length > 1) {
                const newItems = items.filter((_, i) => i !== index);
                setAttributes({ items: newItems });
            }
        };

        const updateItem = (index, field, value) => {
            const newItems = [...items];
            newItems[index] = { ...newItems[index], [field]: value };
            setAttributes({ items: newItems });
        };

        const moveItem = (index, direction) => {
            const newItems = [...items];
            const targetIndex = direction === 'up' ? index - 1 : index + 1;
            
            if (targetIndex >= 0 && targetIndex < items.length) {
                [newItems[index], newItems[targetIndex]] = [newItems[targetIndex], newItems[index]];
                setAttributes({ items: newItems });
            }
        };

        return wp.element.createElement(Fragment, null,
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'List Group Settings', initialOpen: true },
                    wp.element.createElement(ToggleControl, {
                        label: 'Flush Design (No borders)',
                        checked: flush,
                        onChange: (value) => setAttributes({ flush: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Numbered List',
                        checked: numbered,
                        onChange: (value) => setAttributes({ numbered: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Horizontal Layout',
                        checked: horizontal,
                        onChange: (value) => setAttributes({ horizontal: value })
                    }),
                    wp.element.createElement('div', { style: { marginTop: '16px' } },
                        wp.element.createElement(Button, {
                            isPrimary: true,
                            onClick: addItem,
                            icon: 'plus-alt'
                        }, `Add Item (${items.length + 1})`)
                    )
                ),
                wp.element.createElement(PanelBody, { title: 'Item Settings', initialOpen: false },
                    items.map((item, index) => 
                        wp.element.createElement('div', { 
                            key: index, 
                            style: { 
                                padding: '12px', 
                                marginBottom: '12px', 
                                border: '1px solid #ddd', 
                                borderRadius: '4px',
                                backgroundColor: '#f9f9f9'
                            } 
                        },
                            wp.element.createElement('h4', { style: { margin: '0 0 12px 0', fontSize: '14px' } }, `Item ${index + 1}: ${item.text || 'Untitled'}`),
                            wp.element.createElement(SelectControl, {
                                label: 'Variant',
                                value: item.variant || '',
                                options: [
                                    { label: 'Default', value: '' },
                                    { label: 'Primary', value: 'primary' },
                                    { label: 'Secondary', value: 'secondary' },
                                    { label: 'Success', value: 'success' },
                                    { label: 'Danger', value: 'danger' },
                                    { label: 'Warning', value: 'warning' },
                                    { label: 'Info', value: 'info' },
                                    { label: 'Light', value: 'light' },
                                    { label: 'Dark', value: 'dark' }
                                ],
                                onChange: (value) => updateItem(index, 'variant', value)
                            }),
                            wp.element.createElement('div', { style: { display: 'flex', gap: '8px', marginTop: '8px' } },
                                wp.element.createElement(ToggleControl, {
                                    label: 'Active',
                                    checked: item.active || false,
                                    onChange: (value) => {
                                        updateItem(index, 'active', value);
                                        if (value) {
                                            updateItem(index, 'disabled', false);
                                        }
                                    }
                                }),
                                wp.element.createElement(ToggleControl, {
                                    label: 'Disabled',
                                    checked: item.disabled || false,
                                    onChange: (value) => {
                                        updateItem(index, 'disabled', value);
                                        if (value) {
                                            updateItem(index, 'active', false);
                                        }
                                    }
                                })
                            ),
                            wp.element.createElement(TextControl, {
                                label: 'Link URL (optional)',
                                value: item.link || '',
                                onChange: (value) => updateItem(index, 'link', value),
                                placeholder: 'https://example.com'
                            }),
                            items.length > 1 && wp.element.createElement(Button, {
                                isDestructive: true,
                                isSmall: true,
                                onClick: () => removeItem(index),
                                style: { marginTop: '8px' }
                            }, 'Remove Item')
                        )
                    )
                )
            ),
            
            // List Group Preview with Inline Editing
            wp.element.createElement('div', {
                className: 'trinity-list-group-editor',
                style: {
                    border: isSelected ? '2px solid #007cba' : '1px solid #ddd',
                    borderRadius: '4px',
                    overflow: 'hidden'
                }
            },
                wp.element.createElement('div', {
                    className: `list-group${flush ? ' list-group-flush' : ''}${numbered ? ' list-group-numbered' : ''}${horizontal ? ' list-group-horizontal' : ''}`,
                    style: { 
                        marginBottom: '0',
                        maxWidth: horizontal ? 'none' : '500px'
                    }
                },
                    items.map((item, index) => {
                        // Ensure only one state at a time
                        const isActive = item.active && !item.disabled;
                        const isDisabled = item.disabled && !item.active;
                        const hasVariant = item.variant && !item.active && !item.disabled;
                        
                        const itemClasses = [
                            'list-group-item',
                            isActive ? 'active' : '',
                            isDisabled ? 'disabled' : '',
                            hasVariant ? `list-group-item-${item.variant}` : ''
                        ].filter(Boolean).join(' ');

                        return wp.element.createElement('div', {
                            key: index,
                            className: itemClasses,
                            style: {
                                position: 'relative',
                                display: 'flex',
                                alignItems: 'center',
                                padding: numbered ? '0.75rem 1rem' : '0.75rem 1.25rem',
                                backgroundColor: isActive ? '#0d6efd' : hasVariant && item.variant === 'primary' ? '#cff4fc' : hasVariant && item.variant === 'danger' ? '#f8d7da' : hasVariant && item.variant === 'success' ? '#d1e7dd' : hasVariant && item.variant === 'warning' ? '#fff3cd' : 'transparent',
                                color: isActive ? '#fff' : hasVariant && item.variant === 'primary' ? '#055160' : hasVariant && item.variant === 'danger' ? '#721c24' : hasVariant && item.variant === 'success' ? '#0f5132' : hasVariant && item.variant === 'warning' ? '#664d03' : 'inherit',
                                opacity: isDisabled ? 0.5 : 1,
                                border: flush ? '0' : '1px solid rgba(0,0,0,.125)',
                                borderTop: flush || index === 0 ? '0' : '0'
                            }
                        },
                            // Item number for numbered lists
                            numbered && wp.element.createElement('span', {
                                style: {
                                    marginRight: '0.5rem',
                                    fontWeight: '600'
                                }
                            }, `${index + 1}.`),
                            
                            // Editable content - testing with basic input first
                            wp.element.createElement('input', {
                                type: 'text',
                                value: item.text || '',
                                onChange: (e) => updateItem(index, 'text', e.target.value),
                                placeholder: 'Type here...',
                                style: {
                                    border: 'none',
                                    background: 'transparent',
                                    width: '100%',
                                    outline: 'none',
                                    fontSize: 'inherit',
                                    fontFamily: 'inherit',
                                    color: 'inherit'
                                }
                            })
                        );
                    })
                ),
                
                // Add item button at bottom
                isSelected && wp.element.createElement('div', {
                    style: {
                        padding: '8px',
                        textAlign: 'center',
                        backgroundColor: '#f8f9fa',
                        borderTop: '1px solid #dee2e6'
                    }
                },
                    wp.element.createElement(Button, {
                        isPrimary: true,
                        isSmall: true,
                        onClick: addItem,
                        icon: 'plus-alt'
                    }, 'Add Item')
                )
            )
        );
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
        primaryButtonLink: {
            type: 'string',
            default: ''
        },
        primaryButtonVariant: {
            type: 'string',
            default: 'primary'
        },
        primaryButtonAction: {
            type: 'string',
            default: ''
        },
        showPrimaryButton: {
            type: 'boolean',
            default: true
        },
        secondaryButtonText: {
            type: 'string',
            default: 'Close'
        },
        secondaryButtonLink: {
            type: 'string',
            default: ''
        },
        secondaryButtonVariant: {
            type: 'string',
            default: 'secondary'
        },
        secondaryButtonAction: {
            type: 'string',
            default: 'close'
        },
        showSecondaryButton: {
            type: 'boolean',
            default: true
        }
    },
    edit: function(props) {
        const { attributes, setAttributes, isSelected } = props;
        const { modalId, title, content, size, centered, scrollable, fullscreen, backdrop, triggerText, triggerVariant, showFooter, primaryButtonText, primaryButtonLink, primaryButtonVariant, primaryButtonAction, showPrimaryButton, secondaryButtonText, secondaryButtonLink, secondaryButtonVariant, secondaryButtonAction, showSecondaryButton } = attributes;

        return wp.element.createElement(Fragment, null,
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
                ),
                wp.element.createElement(PanelBody, { title: 'Footer Buttons', initialOpen: false },
                    wp.element.createElement(ToggleControl, {
                        label: 'Show Footer',
                        checked: showFooter,
                        onChange: (value) => setAttributes({ showFooter: value })
                    }),
                    showFooter && wp.element.createElement('div', null,
                        wp.element.createElement('h4', { style: { marginTop: '20px', marginBottom: '10px' } }, 'Primary Button'),
                        wp.element.createElement(ToggleControl, {
                            label: 'Show Primary Button',
                            checked: showPrimaryButton,
                            onChange: (value) => setAttributes({ showPrimaryButton: value })
                        }),
                        showPrimaryButton && wp.element.createElement('div', null,
                            wp.element.createElement(TextControl, {
                                label: 'Primary Button Text',
                                value: primaryButtonText,
                                onChange: (value) => setAttributes({ primaryButtonText: value })
                            }),
                            wp.element.createElement(TextControl, {
                                label: 'Primary Button Link',
                                value: primaryButtonLink,
                                onChange: (value) => setAttributes({ primaryButtonLink: value })
                            }),
                            wp.element.createElement(SelectControl, {
                                label: 'Primary Button Variant',
                                value: primaryButtonVariant,
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
                                onChange: (value) => setAttributes({ primaryButtonVariant: value })
                            }),
                            wp.element.createElement(SelectControl, {
                                label: 'Primary Button Action',
                                value: primaryButtonAction,
                                options: [
                                    { label: 'None', value: '' },
                                    { label: 'Close Modal', value: 'close' }
                                ],
                                onChange: (value) => setAttributes({ primaryButtonAction: value })
                            })
                        ),
                        wp.element.createElement('h4', { style: { marginTop: '20px', marginBottom: '10px' } }, 'Secondary Button'),
                        wp.element.createElement(ToggleControl, {
                            label: 'Show Secondary Button',
                            checked: showSecondaryButton,
                            onChange: (value) => setAttributes({ showSecondaryButton: value })
                        }),
                        showSecondaryButton && wp.element.createElement('div', null,
                            wp.element.createElement(TextControl, {
                                label: 'Secondary Button Text',
                                value: secondaryButtonText,
                                onChange: (value) => setAttributes({ secondaryButtonText: value })
                            }),
                            wp.element.createElement(TextControl, {
                                label: 'Secondary Button Link',
                                value: secondaryButtonLink,
                                onChange: (value) => setAttributes({ secondaryButtonLink: value })
                            }),
                            wp.element.createElement(SelectControl, {
                                label: 'Secondary Button Variant',
                                value: secondaryButtonVariant,
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
                                onChange: (value) => setAttributes({ secondaryButtonVariant: value })
                            }),
                            wp.element.createElement(SelectControl, {
                                label: 'Secondary Button Action',
                                value: secondaryButtonAction,
                                options: [
                                    { label: 'None', value: '' },
                                    { label: 'Close Modal', value: 'close' }
                                ],
                                onChange: (value) => setAttributes({ secondaryButtonAction: value })
                            })
                        )
                    )
                )
            ),
            
            // Interactive Modal Preview
            wp.element.createElement('div', {
                className: 'trinity-modal-preview',
                style: {
                    border: isSelected ? '2px solid #007cba' : '1px solid transparent',
                    borderRadius: '4px',
                    padding: isSelected ? '8px' : '0',
                    position: 'relative'
                }
            },
                // Trigger button
                wp.element.createElement('div', {
                    style: { marginBottom: '20px' }
                },
                    wp.element.createElement(RichText, {
                        tagName: 'button',
                        className: `btn btn-${triggerVariant}`,
                        value: triggerText,
                        onChange: (value) => setAttributes({ triggerText: value }),
                        placeholder: 'Button text...',
                        style: {
                            cursor: 'text',
                            minWidth: '120px',
                            padding: '8px 16px'
                        }
                    })
                ),
                
                // Modal preview (static version for editor)
                wp.element.createElement('div', {
                    style: {
                        background: 'rgba(0, 0, 0, 0.1)',
                        border: '2px dashed #007cba',
                        borderRadius: '4px',
                        padding: '20px',
                        marginTop: '10px'
                    }
                },
                    wp.element.createElement('div', {
                        className: 'modal-dialog' + (size ? ` modal-${size}` : '') + (centered ? ' modal-dialog-centered' : '') + (scrollable ? ' modal-dialog-scrollable' : ''),
                        style: {
                            maxWidth: size === 'sm' ? '300px' : size === 'lg' ? '800px' : size === 'xl' ? '1140px' : '500px',
                            margin: '0 auto',
                            background: '#fff',
                            border: '1px solid #dee2e6',
                            borderRadius: '0.25rem',
                            boxShadow: '0 0.5rem 1rem rgba(0, 0, 0, 0.15)'
                        }
                    },
                        wp.element.createElement('div', {
                            className: 'modal-content'
                        },
                            // Modal header
                            wp.element.createElement('div', {
                                className: 'modal-header',
                                style: {
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'space-between',
                                    padding: '1rem',
                                    borderBottom: '1px solid #dee2e6'
                                }
                            },
                                wp.element.createElement(RichText, {
                                    tagName: 'h5',
                                    className: 'modal-title',
                                    value: title,
                                    onChange: (value) => setAttributes({ title: value }),
                                    placeholder: 'Modal title...',
                                    style: {
                                        cursor: 'text',
                                        margin: '0',
                                        minWidth: '100px'
                                    }
                                }),
                                wp.element.createElement('span', {
                                    style: {
                                        fontSize: '24px',
                                        fontWeight: 'bold',
                                        color: '#6c757d',
                                        cursor: 'pointer'
                                    }
                                }, '×')
                            ),
                            
                            // Modal body
                            wp.element.createElement('div', {
                                className: 'modal-body',
                                style: {
                                    padding: '1rem',
                                    minHeight: '100px'
                                }
                            },
                                wp.element.createElement(RichText, {
                                    tagName: 'div',
                                    value: content,
                                    onChange: (value) => setAttributes({ content: value }),
                                    placeholder: 'Modal content...',
                                    style: {
                                        cursor: 'text',
                                        minHeight: '60px'
                                    }
                                })
                            ),
                            
                            // Modal footer
                            showFooter && wp.element.createElement('div', {
                                className: 'modal-footer',
                                style: {
                                    display: 'flex',
                                    justifyContent: 'flex-end',
                                    gap: '0.5rem',
                                    padding: '1rem',
                                    borderTop: '1px solid #dee2e6'
                                }
                            },
                                showSecondaryButton && wp.element.createElement(RichText, {
                                    tagName: 'button',
                                    className: `btn btn-${secondaryButtonVariant}`,
                                    value: secondaryButtonText,
                                    onChange: (value) => setAttributes({ secondaryButtonText: value }),
                                    placeholder: 'Secondary button...',
                                    style: {
                                        cursor: 'text',
                                        minWidth: '80px',
                                        marginRight: '8px'
                                    }
                                }),
                                showPrimaryButton && wp.element.createElement(RichText, {
                                    tagName: 'button',
                                    className: `btn btn-${primaryButtonVariant}`,
                                    value: primaryButtonText,
                                    onChange: (value) => setAttributes({ primaryButtonText: value }),
                                    placeholder: 'Primary button...',
                                    style: {
                                        cursor: 'text',
                                        minWidth: '80px'
                                    }
                                })
                            )
                        )
                    )
                ),
                
                // Editor hint
                wp.element.createElement('div', {
                    style: {
                        textAlign: 'center',
                        marginTop: '10px',
                        padding: '8px',
                        background: '#e3f2fd',
                        borderRadius: '4px',
                        fontSize: '12px',
                        color: '#1976d2'
                    }
                },
                    '💡 This is a preview - the actual modal will appear as an overlay on the frontend'
                )
            )
        );
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
        const { attributes, setAttributes, isSelected } = props;
        const { headers, rows, striped, hover, bordered, borderless, small, responsive, variant, caption } = attributes;

        const addColumn = () => {
            const newHeaders = [...headers, `Column ${headers.length + 1}`];
            const newRows = rows.map(row => [...row, '']);
            setAttributes({ headers: newHeaders, rows: newRows });
        };

        const removeColumn = (index) => {
            const newHeaders = headers.filter((_, i) => i !== index);
            const newRows = rows.map(row => row.filter((_, i) => i !== index));
            setAttributes({ headers: newHeaders, rows: newRows });
        };

        const addRow = () => {
            const newRow = new Array(headers.length).fill('');
            setAttributes({ rows: [...rows, newRow] });
        };

        const removeRow = (index) => {
            const newRows = rows.filter((_, i) => i !== index);
            setAttributes({ rows: newRows });
        };

        const updateHeader = (index, value) => {
            const newHeaders = [...headers];
            newHeaders[index] = value;
            setAttributes({ headers: newHeaders });
        };

        const updateCell = (rowIndex, colIndex, value) => {
            const newRows = [...rows];
            newRows[rowIndex][colIndex] = value;
            setAttributes({ rows: newRows });
        };

        return wp.element.createElement(Fragment, null,
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
                ),
                wp.element.createElement(PanelBody, { title: 'Table Structure', initialOpen: false },
                    wp.element.createElement('div', { style: { marginBottom: '1rem' } },
                        wp.element.createElement(Button, {
                            isPrimary: true,
                            isSmall: true,
                            onClick: addColumn,
                            style: { marginRight: '8px' }
                        }, 'Add Column'),
                        wp.element.createElement(Button, {
                            isPrimary: true,
                            isSmall: true,
                            onClick: addRow
                        }, 'Add Row')
                    )
                )
            ),
            
            // Interactive Table Preview
            wp.element.createElement('div', {
                className: 'trinity-table-preview',
                style: {
                    border: isSelected ? '2px solid #007cba' : '1px solid transparent',
                    borderRadius: '4px',
                    padding: isSelected ? '8px' : '0',
                    position: 'relative'
                }
            },
                wp.element.createElement('div', {
                    className: responsive ? 'table-responsive' : '',
                    style: {
                        maxWidth: '100%',
                        overflow: 'auto'
                    }
                },
                    wp.element.createElement('table', {
                        className: `table${striped ? ' table-striped' : ''}${hover ? ' table-hover' : ''}${bordered ? ' table-bordered' : ''}${borderless ? ' table-borderless' : ''}${small ? ' table-sm' : ''}${variant ? ` table-${variant}` : ''}`,
                        style: {
                            marginBottom: caption ? '0' : '1rem',
                            width: '100%',
                            border: bordered ? '1px solid #dee2e6' : borderless ? 'none' : '1px solid #dee2e6'
                        }
                    },
                        caption && wp.element.createElement(RichText, {
                            tagName: 'caption',
                            value: caption,
                            onChange: (value) => setAttributes({ caption: value }),
                            placeholder: 'Table caption...',
                            style: {
                                cursor: 'text',
                                captionSide: 'top',
                                padding: '0.5rem',
                                color: '#6c757d',
                                textAlign: 'left'
                            }
                        }),
                        wp.element.createElement('thead', null,
                            wp.element.createElement('tr', null,
                                headers.map((header, index) => 
                                    wp.element.createElement('th', {
                                        key: index,
                                        style: {
                                            position: 'relative',
                                            padding: small ? '0.25rem' : '0.75rem',
                                            borderBottom: '2px solid #dee2e6',
                                            fontWeight: '600'
                                        }
                                    },
                                        wp.element.createElement(RichText, {
                                            tagName: 'div',
                                            value: header,
                                            onChange: (value) => updateHeader(index, value),
                                            placeholder: `Header ${index + 1}...`,
                                            style: {
                                                cursor: 'text',
                                                minWidth: '80px',
                                                minHeight: '20px'
                                            }
                                        }),
                                        isSelected && headers.length > 1 && wp.element.createElement('button', {
                                            style: {
                                                position: 'absolute',
                                                top: '2px',
                                                right: '2px',
                                                background: '#dc3545',
                                                color: '#fff',
                                                border: 'none',
                                                borderRadius: '2px',
                                                width: '16px',
                                                height: '16px',
                                                fontSize: '10px',
                                                cursor: 'pointer',
                                                display: 'flex',
                                                alignItems: 'center',
                                                justifyContent: 'center'
                                            },
                                            onClick: () => removeColumn(index)
                                        }, '×')
                                    )
                                )
                            )
                        ),
                        wp.element.createElement('tbody', null,
                            rows.map((row, rowIndex) => 
                                wp.element.createElement('tr', {
                                    key: rowIndex,
                                    style: {
                                        position: 'relative'
                                    }
                                },
                                    row.map((cell, colIndex) => 
                                        wp.element.createElement('td', {
                                            key: colIndex,
                                            style: {
                                                padding: small ? '0.25rem' : '0.75rem',
                                                borderTop: '1px solid #dee2e6',
                                                verticalAlign: 'top'
                                            }
                                        },
                                            wp.element.createElement(RichText, {
                                                tagName: 'div',
                                                value: cell,
                                                onChange: (value) => updateCell(rowIndex, colIndex, value),
                                                placeholder: `Cell ${rowIndex + 1}.${colIndex + 1}...`,
                                                style: {
                                                    cursor: 'text',
                                                    minHeight: '20px'
                                                }
                                            })
                                        )
                                    ),
                                    isSelected && rows.length > 1 && colIndex === 0 && wp.element.createElement('button', {
                                        style: {
                                            position: 'absolute',
                                            left: '-20px',
                                            top: '50%',
                                            transform: 'translateY(-50%)',
                                            background: '#dc3545',
                                            color: '#fff',
                                            border: 'none',
                                            borderRadius: '2px',
                                            width: '16px',
                                            height: '16px',
                                            fontSize: '10px',
                                            cursor: 'pointer',
                                            display: 'flex',
                                            alignItems: 'center',
                                            justifyContent: 'center'
                                        },
                                        onClick: () => removeRow(rowIndex)
                                    }, '×')
                                )
                            )
                        )
                    )
                ),
                
                // Quick controls when selected
                isSelected && wp.element.createElement('div', {
                    style: {
                        textAlign: 'center',
                        marginTop: '10px',
                        padding: '8px',
                        background: '#f0f0f0',
                        borderRadius: '4px'
                    }
                },
                    wp.element.createElement(Button, {
                        isPrimary: true,
                        isSmall: true,
                        onClick: addColumn,
                        icon: 'plus-alt',
                        style: { marginRight: '8px' }
                    }, 'Add Column'),
                    wp.element.createElement(Button, {
                        isPrimary: true,
                        isSmall: true,
                        onClick: addRow,
                        icon: 'plus-alt'
                    }, 'Add Row')
                )
            )
        );
    },
    save: function() {
        return null; // Server-side rendering
    }
});

/**
 * WordPress Core Table Block Extension for Bootstrap
 */

const { addFilter } = wp.hooks;
const { createHigherOrderComponent } = wp.compose;

// Add Bootstrap attributes to core table block
function addBootstrapTableAttributes(settings, name) {
    if (name !== 'core/table') {
        return settings;
    }

    return {
        ...settings,
        attributes: {
            ...settings.attributes,
            bootstrapStriped: {
                type: 'boolean',
                default: false
            },
            bootstrapHover: {
                type: 'boolean',
                default: false
            },
            bootstrapBordered: {
                type: 'boolean',
                default: false
            },
            bootstrapBorderless: {
                type: 'boolean',
                default: false
            },
            bootstrapSmall: {
                type: 'boolean',
                default: false
            },
            bootstrapResponsive: {
                type: 'boolean',
                default: false
            },
            bootstrapVariant: {
                type: 'string',
                default: ''
            }
        }
    };
}

addFilter(
    'blocks.registerBlockType',
    'trinity/table-bootstrap-attributes',
    addBootstrapTableAttributes
);

// Add Bootstrap controls to table block inspector
const withBootstrapTableControls = createHigherOrderComponent((BlockEdit) => {
    return (props) => {
        if (props.name !== 'core/table') {
            return wp.element.createElement(BlockEdit, props);
        }

        const { attributes, setAttributes } = props;
        const {
            bootstrapStriped,
            bootstrapHover,
            bootstrapBordered,
            bootstrapBorderless,
            bootstrapSmall,
            bootstrapResponsive,
            bootstrapVariant
        } = attributes;

        return wp.element.createElement(Fragment, null,
            wp.element.createElement(BlockEdit, props),
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { 
                    title: 'Bootstrap Table Options',
                    initialOpen: false
                },
                    wp.element.createElement(ToggleControl, {
                        label: 'Striped Rows',
                        help: 'Add zebra-striping to table rows',
                        checked: bootstrapStriped,
                        onChange: (value) => setAttributes({ bootstrapStriped: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Hoverable Rows',
                        help: 'Enable hover state on table rows',
                        checked: bootstrapHover,
                        onChange: (value) => setAttributes({ bootstrapHover: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Bordered',
                        help: 'Add borders on all sides of the table and cells',
                        checked: bootstrapBordered,
                        onChange: (value) => setAttributes({ bootstrapBordered: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Borderless',
                        help: 'Remove all borders from the table',
                        checked: bootstrapBorderless,
                        onChange: (value) => setAttributes({ bootstrapBorderless: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Small/Compact',
                        help: 'Make the table more compact by cutting cell padding in half',
                        checked: bootstrapSmall,
                        onChange: (value) => setAttributes({ bootstrapSmall: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Responsive',
                        help: 'Make table scroll horizontally on smaller screens',
                        checked: bootstrapResponsive,
                        onChange: (value) => setAttributes({ bootstrapResponsive: value })
                    }),
                    wp.element.createElement(SelectControl, {
                        label: 'Color Variant',
                        help: 'Apply a color theme to the table',
                        value: bootstrapVariant,
                        options: [
                            { label: 'Default', value: '' },
                            { label: 'Dark', value: 'dark' },
                            { label: 'Primary', value: 'primary' },
                            { label: 'Secondary', value: 'secondary' },
                            { label: 'Success', value: 'success' },
                            { label: 'Danger', value: 'danger' },
                            { label: 'Warning', value: 'warning' },
                            { label: 'Info', value: 'info' },
                            { label: 'Light', value: 'light' }
                        ],
                        onChange: (value) => setAttributes({ bootstrapVariant: value })
                    })
                )
            )
        );
    };
}, 'withBootstrapTableControls');

addFilter(
    'editor.BlockEdit',
    'trinity/table-bootstrap-controls',
    withBootstrapTableControls
);
