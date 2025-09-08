# Trinity WordPress Theme - Bootstrap Custom Blocks

## Overview

The Trinity theme now includes custom WordPress blocks for Bootstrap components, making it easy to add professional, responsive components directly in the WordPress block editor.

## Available Bootstrap Blocks

### 1. Alert Block (`trinity/alert`)
- **Purpose**: Display styled alert messages
- **Options**: 
  - Style: Primary, Secondary, Success, Danger, Warning, Info, Light, Dark
  - Dismissible: Can be closed by users
  - Custom message text

### 2. Accordion Block (`trinity/accordion`)
- **Purpose**: Create collapsible content sections
- **Options**:
  - Flush design (borderless)
  - Add/remove accordion items
  - Custom titles and content for each item

### 3. Card Block (`trinity/card`)
- **Purpose**: Display content in flexible card containers
- **Options**:
  - Card header and footer
  - Custom content
  - Image support

### 4. Progress Bar Block (`trinity/progress`)
- **Purpose**: Show progress or completion status
- **Options**:
  - Progress value (0-100%)
  - Style: Primary, Secondary, Success, Danger, Warning, Info, Light, Dark
  - Striped and animated options

### 5. Badge Block (`trinity/badge`)
- **Purpose**: Add small labels or status indicators
- **Options**:
  - Style: Primary, Secondary, Success, Danger, Warning, Info, Light, Dark
  - Pill style (rounded)
  - Custom text

### 6. Carousel Block (`trinity/carousel`)
- **Purpose**: Create image slideshows
- **Options**:
  - Show/hide navigation controls
  - Show/hide indicators
  - Autoplay functionality
  - Multiple images

### 7. Button Group Block (`trinity/button-group`)
- **Purpose**: Group multiple buttons together
- **Options**:
  - Horizontal or vertical layout
  - Size: Default, Large, Small
  - Multiple button styles

### 8. Tabs Block (`trinity/tabs`)
- **Purpose**: Organize content in tabbed interface
- **Options**:
  - Style: Tabs or Pills
  - Multiple tab items
  - Custom content for each tab

## How to Use

1. **In the WordPress Editor**:
   - Click the "+" button to add a new block
   - Search for "Bootstrap" or "Trinity"
   - Select any of the available Bootstrap component blocks

2. **Block Settings**:
   - Each block has a settings panel on the right side
   - Customize appearance, behavior, and content
   - Changes are reflected immediately in the editor

3. **Preview**:
   - Blocks show a styled preview in the editor
   - Final appearance matches the Bootstrap styles on the frontend

## Technical Details

### Files Structure
```
wp-content/themes/trinity/
├── blocks/
│   └── blocks.php          # Server-side block registration and rendering
├── assets/
│   ├── js/blocks/
│   │   └── blocks.js       # Block editor JavaScript (source)
│   ├── dist/
│   │   └── blocks.min.js   # Compiled block editor JavaScript
│   └── css/
│       └── blocks-editor.css # Block editor styling
└── functions.php           # Block registration and asset enqueuing
```

### Development
- **Building**: Run `npm run build` or `npx webpack --mode production` to compile JavaScript
- **Dependencies**: Blocks use WordPress APIs (`wp-blocks`, `wp-block-editor`, `wp-components`)
- **Styling**: Bootstrap 5 classes are used for frontend rendering

### Block Category
All Trinity blocks are grouped under the "Trinity Blocks" category in the block editor for easy access.

## Browser Support

These blocks work in all modern browsers and are fully responsive. They inherit Bootstrap 5's browser support:
- Chrome, Firefox, Safari, Edge
- Mobile browsers (iOS Safari, Chrome Mobile)
- Internet Explorer 11+ (with polyfills)

## Customization

### Adding New Blocks
1. Add server-side registration in `blocks/blocks.php`
2. Add editor JavaScript in `assets/js/blocks/blocks.js`
3. Rebuild assets with `npm run build`

### Styling
- Frontend styles come from Bootstrap CSS
- Editor preview styles are in `assets/css/blocks-editor.css`
- Custom styling can be added to theme's main stylesheet

## Support

For theme support and customization requests, refer to the theme documentation or contact the theme developer.
