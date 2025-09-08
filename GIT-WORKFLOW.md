# Trinity WordPress Theme Development Workflow

## Git Workflow

### Branch Strategy 🌿
- **`master`** - Stable, production-ready code (protected)
- **`dev`** - Active development branch (default for new work)
- **`feature/*`** - Feature development branches
- **`bugfix/*`** - Bug fix branches
- **`hotfix/*`** - Critical fixes for production

### Initial Setup ✅
- [x] Git repository initialized
- [x] `.gitignore` configured for WordPress theme development
- [x] Initial commit with full theme structure
- [x] Remote repository connected
- [x] Development branch (`dev`) created

### Development Branches
```bash
# Switch to development branch (primary work branch)
git checkout dev

# Create feature branches from dev
git checkout dev
git checkout -b feature/block-enhancements
git checkout -b feature/new-components
git checkout -b feature/responsive-improvements

# Create bugfix branches from dev
git checkout dev
git checkout -b bugfix/accordion-spacing
git checkout -b bugfix/mobile-navbar

# Create hotfix branches from master (emergency fixes)
git checkout master
git checkout -b hotfix/critical-security-fix
```

### Workflow Process
```bash
# 1. Start new work on dev branch
git checkout dev
git pull origin dev

# 2. Create feature branch for specific work
git checkout -b feature/new-carousel-options

# 3. Work on feature, commit regularly
git add .
git commit -m "Add carousel autoplay interval option"

# 4. Push feature branch to remote
git push origin feature/new-carousel-options

# 5. When feature is complete, merge back to dev
git checkout dev
git merge feature/new-carousel-options

# 6. Push updated dev branch
git push origin dev

# 7. Delete feature branch (locally and remotely)
git branch -d feature/new-carousel-options
git push origin --delete feature/new-carousel-options

# 8. When dev is stable, merge to master for release
git checkout master
git merge dev
git tag -a v1.0.1 -m "Release v1.0.1"
git push origin master --tags
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
- ✅ **Always work on `dev` branch** for active development
- ✅ Keep `master` branch stable and production-ready
- ✅ Create feature branches from `dev` for specific work
- ✅ Always run `npm run build` before committing
- ✅ Test in WordPress before committing
- ✅ Use descriptive commit messages
- ✅ Keep commits focused and atomic
- ✅ Document breaking changes
- ✅ Tag releases for distribution
- ✅ Never commit directly to `master` in production

### Branch Protection (Recommended)
```bash
# On remote repositories (GitHub/GitLab), set up:
# - Protect master branch from direct pushes
# - Require pull requests for master
# - Require status checks to pass
# - Require branches to be up to date before merging
```

### Quick Reference Commands
```bash
# Check current branch
git branch

# Switch branches
git checkout dev
git checkout master

# Create and switch to new branch
git checkout -b feature/new-feature

# Show branch differences
git diff master..dev

# Show commit differences
git log master..dev --oneline

# Merge dev into master (for releases)
git checkout master
git merge dev

# Push all branches and tags
git push origin --all --tags
```

### Current Status
- **Repository**: Initialized and connected to remote
- **Current Branch**: dev (active development)
- **Stable Branch**: master (production-ready)
- **Last Release**: v1.0.0 (tagged on master)
- **Build Status**: Production assets compiled
- **WordPress**: Ready for activation

### Next Steps for Development
1. **Work on `dev` branch** for all new features
2. **Create feature branches** from `dev` for major changes
3. **Test thoroughly** before merging to `dev`
4. **Merge `dev` to `master`** only for stable releases
5. **Tag releases** on `master` branch
