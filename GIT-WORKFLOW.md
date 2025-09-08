# Trinity WordPress Theme Development Workflow

## Git Workflow

### Initial Setup ✅
- [x] Git repository initialized
- [x] `.gitignore` configured for WordPress theme development
- [x] Initial commit with full theme structure

### Development Branches
```bash
# Main development branch
git checkout master

# Create feature branches for new development
git checkout -b feature/block-enhancements
git checkout -b feature/new-components
git checkout -b bugfix/specific-issue
```

### Common Git Commands
```bash
# Check status
git status

# Add files to staging
git add .
git add specific-file.php

# Commit changes
git commit -m "Descriptive commit message"

# View commit history
git log --oneline

# Create and push tags for releases
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0
```

### Build Workflow
```bash
# Development build (with source maps)
npm run build:dev

# Production build (minified)
npm run build

# Watch for changes during development
npm run watch

# Build CSS only
npm run build:css
```

### File Structure (Git Tracked)
```
trinity/
├── .gitignore              # Git ignore rules
├── package.json            # Node.js dependencies
├── webpack.config.js       # Build configuration
├── README.md               # Theme documentation
├── BLOCKS-README.md        # Block system documentation
├── style.css               # Main theme stylesheet
├── functions.php           # Theme functions
├── assets/
│   ├── scss/              # Source SCSS files
│   ├── js/                # Source JavaScript files
│   └── css/               # Additional CSS files
├── blocks/
│   └── blocks.php         # Bootstrap component blocks
├── inc/
│   ├── blocks.php         # Theme-specific blocks
│   ├── customizer.php     # WordPress customizer
│   └── *.php             # Other theme includes
└── *.php                  # WordPress template files
```

### File Structure (Git Ignored)
```
trinity/
├── node_modules/          # Node.js dependencies (ignored)
├── assets/dist/           # Built/compiled assets (ignored)
│   ├── *.min.js          # Minified JavaScript
│   ├── *.min.css         # Minified CSS
│   └── *.map             # Source maps
└── *.log                 # Log files (ignored)
```

### Release Process
1. **Development**: Work on feature branches
2. **Testing**: Test all functionality thoroughly
3. **Build**: Run production build (`npm run build`)
4. **Version**: Update version in `style.css` and `package.json`
5. **Commit**: Commit all changes with clear message
6. **Tag**: Create version tag (`git tag -a v1.0.1 -m "Version 1.0.1"`)
7. **Archive**: Create distribution ZIP (built assets included)

### Best Practices
- ✅ Always run `npm run build` before committing
- ✅ Test in WordPress before committing
- ✅ Use descriptive commit messages
- ✅ Keep commits focused and atomic
- ✅ Document breaking changes
- ✅ Tag releases for distribution

### Current Status
- **Repository**: Initialized and clean
- **Branch**: master
- **Last Commit**: Initial theme structure with Bootstrap blocks
- **Build Status**: Production assets compiled
- **WordPress**: Ready for activation
