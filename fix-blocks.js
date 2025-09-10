const fs = require('fs');
const path = require('path');

// Read the blocks.js file
const blocksFile = path.join(__dirname, 'assets', 'js', 'blocks', 'blocks.js');
let content = fs.readFileSync(blocksFile, 'utf8');

console.log('Fixing remaining blocks to use Fragment...');

// Fix any remaining wp.blocks.registerBlockType references
content = content.replace(/wp\.blocks\.registerBlockType/g, 'registerBlockType');

// Find and fix each block's return statement individually
const blockPatterns = [
    { name: 'Progress', pattern: /(\/\/ Progress Block[\s\S]*?edit: function\(props\) {[\s\S]*?return )\[/ },
    { name: 'Badge', pattern: /(\/\/ Badge Block[\s\S]*?edit: function\(props\) {[\s\S]*?return )\[/ },
    { name: 'Carousel', pattern: /(\/\/ Carousel Block[\s\S]*?edit: function\(props\) {[\s\S]*?return )\[/ },
    { name: 'Button Group', pattern: /(\/\/ Button Group Block[\s\S]*?edit: function\(props\) {[\s\S]*?return )\[/ },
    { name: 'Tabs', pattern: /(\/\/ Tabs Block[\s\S]*?edit: function\(props\) {[\s\S]*?return )\[/ },
    { name: 'ScrollSpy', pattern: /(\/\/ ScrollSpy Block[\s\S]*?edit: function\(props\) {[\s\S]*?return )\[/ },
    { name: 'List Group', pattern: /(\/\/ List Group Block[\s\S]*?edit: function\(props\) {[\s\S]*?return )\[/ },
    { name: 'Modal', pattern: /(\/\/ Enhanced Modal Block[\s\S]*?edit: function\(props\) {[\s\S]*?return )\[/ },
    { name: 'Table', pattern: /(\/\/ Table Block[\s\S]*?edit: function\(props\) {[\s\S]*?return )\[/ }
];

// Apply fixes for each block
blockPatterns.forEach(block => {
    if (block.pattern.test(content)) {
        content = content.replace(block.pattern, '$1wp.element.createElement(Fragment, null,');
        console.log(`Fixed ${block.name} block`);
    }
});

// Fix all closing "];}" patterns to ");}"
// This handles the end of edit functions
let fixedClosings = 0;
content = content.replace(/(\s+)\];\s*},\s*(save:|$)/g, (match, indent, next) => {
    fixedClosings++;
    return `${indent});${indent}},\n${indent}${next}`;
});

console.log(`Fixed ${fixedClosings} closing brackets`);

// Write the file back
fs.writeFileSync(blocksFile, content, 'utf8');

console.log('All blocks fixed to use Fragment instead of arrays');
