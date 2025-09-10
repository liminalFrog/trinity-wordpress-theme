/**
 * WordPress Core Table Block Extension for Bootstrap
 */

const { addFilter } = wp.hooks;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, ToggleControl, SelectControl } = wp.components;
const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;

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

// Apply Bootstrap classes to table block save
const withBootstrapTableClasses = createHigherOrderComponent((BlockListBlock) => {
    return (props) => {
        if (props.name !== 'core/table') {
            return wp.element.createElement(BlockListBlock, props);
        }

        const { attributes } = props;
        const {
            bootstrapStriped,
            bootstrapHover,
            bootstrapBordered,
            bootstrapBorderless,
            bootstrapSmall,
            bootstrapResponsive,
            bootstrapVariant
        } = attributes;

        // Build Bootstrap classes
        const bootstrapClasses = [
            'table',
            bootstrapStriped ? 'table-striped' : '',
            bootstrapHover ? 'table-hover' : '',
            bootstrapBordered ? 'table-bordered' : '',
            bootstrapBorderless ? 'table-borderless' : '',
            bootstrapSmall ? 'table-sm' : '',
            bootstrapVariant ? `table-${bootstrapVariant}` : ''
        ].filter(Boolean).join(' ');

        // Create wrapper for responsive if needed
        const tableElement = wp.element.createElement(BlockListBlock, {
            ...props,
            className: `${props.className || ''} ${bootstrapClasses}`.trim()
        });

        if (bootstrapResponsive) {
            return wp.element.createElement('div', {
                className: 'table-responsive'
            }, tableElement);
        }

        return tableElement;
    };
}, 'withBootstrapTableClasses');

addFilter(
    'editor.BlockListBlock',
    'trinity/table-bootstrap-classes',
    withBootstrapTableClasses
);
