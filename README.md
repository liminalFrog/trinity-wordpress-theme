# My Custom WordPress Theme

A modern WordPress theme built with Bootstrap 5 and Sass.

## Features

- ✅ Bootstrap 5 integration
- ✅ Sass compilation workflow
- ✅ Responsive design
- ✅ Modern CSS Grid and Flexbox
- ✅ WordPress coding standards
- ✅ SEO optimized
- ✅ Accessibility ready

## Development Setup

### Prerequisites

- Node.js (version 14 or higher)
- npm or yarn package manager

### Installation

1. Navigate to the theme directory:
   ```bash
   cd wp-content/themes/mytheme
   ```

2. Install dependencies:
   ```bash
   npm install
   ```

### Development Commands

- **Build styles (development):**
  ```bash
  npm run build
  ```

- **Watch for changes (development):**
  ```bash
  npm run watch
  ```

- **Development with source maps:**
  ```bash
  npm run dev
  ```

- **Build for production:**
  ```bash
  npm run build-prod
  ```

## File Structure

```
mytheme/
├── src/
│   └── scss/
│       ├── components/
│       │   ├── _header.scss
│       │   ├── _navigation.scss
│       │   ├── _content.scss
│       │   ├── _footer.scss
│       │   └── _responsive.scss
│       └── style.scss
├── js/
│   └── navigation.js
├── node_modules/
├── functions.php
├── header.php
├── footer.php
├── index.php
├── single.php
├── page.php
├── style.css (compiled)
└── package.json
```

## Customization

### Colors

Edit the CSS custom properties in `src/scss/style.scss`:

```scss
:root {
  --bs-primary: #2c3e50;
  --theme-primary: #2c3e50;
  --theme-accent: #3498db;
  // ... more variables
}
```

### Bootstrap Components

The theme includes Bootstrap 5. You can use any Bootstrap classes in your templates:

```html
<div class="container">
  <div class="row">
    <div class="col-md-8">
      <!-- Content -->
    </div>
    <div class="col-md-4">
      <!-- Sidebar -->
    </div>
  </div>
</div>
```

### Adding New Styles

1. Create a new SCSS file in `src/scss/components/`
2. Import it in `src/scss/style.scss`
3. Run `npm run watch` to automatically compile changes

## Browser Support

- Chrome (last 2 versions)
- Firefox (last 2 versions)
- Safari (last 2 versions)
- Edge (last 2 versions)

## License

MIT License
