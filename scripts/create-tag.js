const fs = require('fs');
const { execSync } = require('child_process');

// Get package.json for version
const packageJson = JSON.parse(fs.readFileSync('package.json', 'utf8'));
const version = packageJson.version;
const tagName = `v${version}`;

try {
    // Check if tag already exists
    try {
        execSync(`git tag -l ${tagName}`, { stdio: 'pipe' });
        console.log(`ℹ️  Tag ${tagName} already exists. Skipping tag creation.`);
        process.exit(0);
    } catch (e) {
        // Tag doesn't exist, continue
    }

    // Add all changes
    execSync('git add .', { stdio: 'inherit' });

    // Commit changes
    try {
        execSync(`git commit -m "Release version ${version}"`, { stdio: 'inherit' });
    } catch (e) {
        console.log('ℹ️  No changes to commit.');
    }

    // Create and push tag
    execSync(`git tag -a ${tagName} -m "Release version ${version}"`, { stdio: 'inherit' });
    execSync(`git push origin ${tagName}`, { stdio: 'inherit' });
    execSync('git push origin master', { stdio: 'inherit' });

    console.log(`✅ Successfully created and pushed tag ${tagName}`);
    console.log(`🚀 Release ${version} is now available on GitHub`);

} catch (error) {
    console.error('❌ Error creating release:', error.message);
    process.exit(1);
}
