# Trinity WordPress Theme

A modern WordPress theme with Bootstrap framework, dark/light mode support, and custom blocks.

## Features

- **Bootstrap 5** framework integration
- **Dark/Light mode** with toggle switch
- **Fixed navbar** with blur effect and scroll transitions
- **Custom blocks** including heroes, cards, carousels, modals, and more
- **Responsive design** with mobile-first approach
- **Theme customizer** integration
- **GitHub-based updates**
- **SCSS compilation** and build tools
- **Announcement bar** functionality
- **Page subtitle** support
- **Widget-ready** footer areas

## Installation

1. Download the theme
2. Upload to `/wp-content/themes/trinity/`
3. Activate the theme in WordPress admin
4. Configure theme options in Customizer

## Development

### Prerequisites

- Node.js and npm
- Git

### Setup

```bash
npm install
```

### Build Commands

```bash
# Build CSS and JS for production
npm run build

# Watch for changes during development
npm run watch

# Development mode (build + watch)
npm run dev

# Create release zip
npm run zip

# Lint code
npm run lint
```

### Git Commands

```bash
# Pull latest changes
npm run git:pull

# Push changes
npm run git:push

# Create release tag and push
npm run git:tag

# Full release process
npm run release
```

## Custom Blocks

The theme includes several custom blocks:

### Hero Block
- Background image/video/color support
- Text positioning and alignment
- Parallax and static background effects
- Button integration

### Alert Block
- Bootstrap alert variants
- Dismissible functionality
- Icon support

### Accordion Block
- Collapsible content sections
- Single or multiple open support

### Card Block
- Image and content cards
- Stretched link support
- Various layouts

### Carousel Block
- Image/content slides
- Auto-play functionality
- Custom height and border radius

### Modal Block
- Trigger options (button, link, page load)
- Size variants (sm, md, lg)
- Custom content

## Theme Customization

### Customizer Options

- **Theme Mode**: Default light/dark mode
- **Logos**: Separate logos for light and dark modes
- **Navbar Backgrounds**: Pattern images for navbar
- **Announcement Bar**: Enable/disable with custom styling
- **GitHub Updates**: Repository URL for updates

### Page Options

Each page includes:
- Hide page title option
- Subtitle field (SEO-friendly)

## File Structure

```
trinity/
├── assets/
│   ├── js/
│   │   ├── theme.js
│   │   └── customizer.js
│   ├── scss/
│   │   ├── style.scss
│   │   ├── _variables.scss
│   │   ├── _mixins.scss
│   │   ├── _base.scss
│   │   ├── _utilities.scss
│   │   └── components/
│   └── images/
├── inc/
│   ├── nav-walker.php
│   ├── customizer.php
│   ├── blocks.php
│   ├── theme-updates.php
│   └── template-functions.php
├── scripts/
│   ├── create-zip.js
│   └── create-tag.js
├── functions.php
├── index.php
├── header.php
├── footer.php
├── page.php
├── single.php
├── sidebar.php
├── comments.php
├── style.css
├── package.json
└── webpack.config.js
```

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Internet Explorer 11+ (limited support)

## License

GPL v2 or later

## Author

Adam Mureiko

## Changelog

### Version 1.0.0
- Initial release
- Bootstrap 5 integration
- Dark/light mode support
- Custom blocks
- Theme customizer options
- GitHub update system
