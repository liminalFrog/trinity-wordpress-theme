<?php
/**
 * Trinity Theme Update Server
 * Host this on your server (e.g., trinitymetalworks.com/wp-updates/check-version.php)
 */

// Prevent direct access
if (!defined('PHP_VERSION')) {
    http_response_code(403);
    exit('Direct access not allowed');
}

// Set JSON header
header('Content-Type: application/json');

// Get request data
$theme_slug = $_POST['theme_slug'] ?? '';
$current_version = $_POST['current_version'] ?? '';
$site_url = $_POST['site_url'] ?? '';

// Log the request (optional)
$log_data = [
    'theme_slug' => $theme_slug,
    'current_version' => $current_version,
    'site_url' => $site_url,
    'ip' => $_SERVER['REMOTE_ADDR'],
    'timestamp' => date('Y-m-d H:i:s')
];
file_put_contents('update_requests.log', json_encode($log_data) . "\n", FILE_APPEND);

// Define latest version info
$latest_version_info = [
    'trinity' => [
        'version' => '1.1.0',
        'download_url' => 'https://trinitymetalworks.com/downloads/trinity-theme.zip',
        'details_url' => 'https://trinitymetalworks.com/trinity-theme/',
        'changelog' => [
            '1.1.0' => [
                'date' => '2025-08-08',
                'changes' => [
                    'Added TMHero integration styles',
                    'Improved responsive design',
                    'Fixed minor CSS issues',
                    'Enhanced footer attribution'
                ]
            ],
            '1.0.7' => [
                'date' => '2025-08-07', 
                'changes' => [
                    'Added auto-update functionality',
                    'Improved theme structure',
                    'Added update settings page'
                ]
            ]
        ],
        'requires_wp' => '5.0',
        'tested_up_to' => '6.6',
        'requires_php' => '7.4'
    ]
];

// Validate theme slug
if (!isset($latest_version_info[$theme_slug])) {
    http_response_code(404);
    echo json_encode(['error' => 'Theme not found']);
    exit;
}

$theme_info = $latest_version_info[$theme_slug];

// Check if update is needed
$update_available = version_compare($current_version, $theme_info['version'], '<');

if ($update_available) {
    // Return update information
    echo json_encode([
        'update_available' => true,
        'version' => $theme_info['version'],
        'download_url' => $theme_info['download_url'],
        'details_url' => $theme_info['details_url'],
        'changelog' => $theme_info['changelog'][$theme_info['version']],
        'requires_wp' => $theme_info['requires_wp'],
        'tested_up_to' => $theme_info['tested_up_to'],
        'requires_php' => $theme_info['requires_php']
    ]);
} else {
    // No update needed
    echo json_encode([
        'update_available' => false,
        'current_version' => $current_version,
        'latest_version' => $theme_info['version']
    ]);
}
?>
