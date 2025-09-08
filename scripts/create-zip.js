const fs = require('fs');
const path = require('path');
const archiver = require('archiver');

// Get package.json for version
const packageJson = JSON.parse(fs.readFileSync('package.json', 'utf8'));
const version = packageJson.version;

// Create zip filename
const zipFilename = `trinity-theme-v${version}.zip`;
const outputPath = path.join(process.cwd(), '..', zipFilename);

// Create a file to stream archive data to
const output = fs.createWriteStream(outputPath);
const archive = archiver('zip', {
    zlib: { level: 9 } // Sets the compression level
});

// Listen for all archive data to be written
output.on('close', function() {
    console.log(`✅ Created ${zipFilename} (${archive.pointer()} total bytes)`);
    console.log(`📦 Archive location: ${outputPath}`);
});

// Catch warnings (ie stat failures and other non-blocking errors)
archive.on('warning', function(err) {
    if (err.code === 'ENOENT') {
        console.warn('Warning:', err);
    } else {
        throw err;
    }
});

// Catch errors
archive.on('error', function(err) {
    throw err;
});

// Pipe archive data to the file
archive.pipe(output);

// Define files and folders to exclude
const excludePatterns = [
    'node_modules/**',
    'scripts/**',
    '.git/**',
    '.gitignore',
    'package.json',
    'package-lock.json',
    'webpack.config.js',
    '.stylelintrc.json',
    '.eslintrc.json',
    'assets/scss/**',
    'assets/js/theme.js',
    'assets/js/customizer.js',
    '*.log',
    '.DS_Store',
    'Thumbs.db'
];

// Add all files except excluded ones
archive.glob('**/*', {
    ignore: excludePatterns,
    dot: false
});

// Include only the built files
archive.file('style.css', { name: 'style.css' });

// Finalize the archive
archive.finalize();
