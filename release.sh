#!/bin/bash

# Trinity Theme Release Script
# Usage: ./release.sh <version>
# Example: ./release.sh 1.1.0

set -e

# Check if version is provided
if [ -z "$1" ]; then
    echo "Usage: ./release.sh <version>"
    echo "Example: ./release.sh 1.1.0"
    exit 1
fi

VERSION=$1

echo "🚀 Preparing Trinity Theme release v$VERSION"

# Update version in files
echo "📝 Updating version in files..."

# Update style.css
sed -i.bak "s/Version: [0-9]\+\.[0-9]\+\.[0-9]\+/Version: $VERSION/" style.css

# Update style.scss
sed -i.bak "s/Version: [0-9]\+\.[0-9]\+\.[0-9]\+/Version: $VERSION/" src/scss/style.scss

# Update VERSION file
echo "$VERSION" > VERSION

# Update package.json
npm version $VERSION --no-git-tag-version

echo "✅ Version updated to $VERSION"

# Build the theme
echo "🔨 Building theme..."
npm run build-prod

# Git operations
echo "📦 Committing changes..."
git add .
git commit -m "Release version $VERSION"

# Create and push tag
echo "🏷️  Creating tag v$VERSION..."
git tag -a "v$VERSION" -m "Release version $VERSION"

echo "🚀 Pushing to GitHub..."
git push origin master
git push origin "v$VERSION"

echo "✨ Release v$VERSION completed!"
echo "🌐 GitHub Actions will automatically create the release with zip file"
echo "📋 Check: https://github.com/your-username/trinity-theme/releases"
