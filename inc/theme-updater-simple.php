<?php
/**
 * Trinity Theme Updater - Simplified Version
 * 
 * Professional WordPress theme updater without complex dependencies
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Trinity_Theme_Updater {
    
    private $theme_slug;
    private $version;
    
    public function __construct() {
        $theme = wp_get_theme();
        $this->theme_slug = get_template();
        $this->version = $theme->get('Version');
        
        // Only run if auto-updates are enabled
        if (get_option('trinity_auto_update', false)) {
            add_filter('pre_set_site_transient_update_themes', array($this, 'check_for_update'));
        }
        
        // Always add theme information handler
        add_filter('themes_api', array($this, 'get_theme_info'), 10, 3);
        
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }
    
    /**
     * Check for theme updates (now with server integration)
     */
    public function check_for_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }
        
        // Check for updates only once per day
        $last_check = get_transient('trinity_update_check');
        if ($last_check !== false && !isset($_GET['force-check'])) {
            return $transient;
        }
        
        // Get remote version info
        $remote_info = $this->get_remote_version();
        
        if ($remote_info && $remote_info['update_available']) {
            $transient->response[$this->theme_slug] = array(
                'theme' => $this->theme_slug,
                'new_version' => $remote_info['version'],
                'url' => $remote_info['details_url'],
                'package' => $remote_info['download_url'],
            );
        }
        
        // Cache the check for 24 hours
        set_transient('trinity_update_check', $remote_info, DAY_IN_SECONDS);
        
        return $transient;
    }
    
    /**
     * Get remote version info from update server
     */
    private function get_remote_version() {
        $update_server_url = 'https://trinitymetalworks.com/wp-updates/check-version.php';
        
        $request_args = array(
            'timeout' => 15,
            'body' => array(
                'theme_slug' => $this->theme_slug,
                'current_version' => $this->version,
                'site_url' => home_url(),
                'php_version' => PHP_VERSION,
                'wp_version' => get_bloginfo('version'),
            ),
        );
        
        $request = wp_remote_post($update_server_url, $request_args);
        
        if (!is_wp_error($request) && wp_remote_retrieve_response_code($request) === 200) {
            $body = wp_remote_retrieve_body($request);
            $data = json_decode($body, true);
            
            if ($data && !isset($data['error'])) {
                return $data;
            }
        }
        
        return false;
    }
    
    /**
     * Handle theme information popup
     */
    public function get_theme_info($result, $action, $args) {
        if ($action === 'theme_information' && isset($args->slug) && $args->slug === $this->theme_slug) {
            $remote_info = $this->get_remote_version();
            
            if ($remote_info) {
                $info = new stdClass();
                $info->name = 'Trinity Theme';
                $info->slug = $this->theme_slug;
                $info->version = $remote_info['version'];
                $info->author = '<a href="https://trinitymetalworks.com">Trinity Metalworks</a>';
                $info->homepage = 'https://trinitymetalworks.com';
                $info->sections = array(
                    'description' => 'A modern WordPress theme with Bootstrap 5 integration and TMHero block support. Perfect for business and portfolio websites.',
                );
                
                // Add changelog if available
                if (!empty($remote_info['changelog'])) {
                    $info->sections['changelog'] = $remote_info['changelog'];
                }
                
                $info->download_link = $remote_info['download_url'];
                $info->requires = '5.0';
                $info->tested = $remote_info['tested'] ?? get_bloginfo('version');
                
                return $info;
            }
        }
        
        return false;
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            'Trinity Updates',
            'Theme Updates',
            'manage_options',
            'trinity-updates',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        // Handle manual update check
        if (isset($_POST['check_updates'])) {
            delete_transient('trinity_update_check');
            wp_redirect(add_query_arg('force-check', '1', admin_url('themes.php')));
            exit;
        }
        
        if (isset($_POST['submit'])) {
            update_option('trinity_auto_update', isset($_POST['auto_update']));
            echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
        }
        
        $auto_update = get_option('trinity_auto_update', false);
        $theme = wp_get_theme();
        $last_check = get_transient('trinity_update_check');
        $remote_info = $this->get_remote_version();
        ?>
        <div class="wrap">
            <h1>Trinity Theme Updates</h1>
            
            <div class="card" style="max-width: 600px;">
                <div class="card-body" style="padding: 20px;">
                    <h2>Theme Information</h2>
                    <p><strong>Theme:</strong> <?php echo $theme->get('Name'); ?></p>
                    <p><strong>Current Version:</strong> <?php echo $theme->get('Version'); ?></p>
                    <p><strong>Author:</strong> <?php echo $theme->get('Author'); ?></p>
                    
                    <?php if ($remote_info): ?>
                        <p><strong>Latest Version:</strong> <?php echo esc_html($remote_info['version']); ?></p>
                        <?php if (version_compare($this->version, $remote_info['version'], '<')): ?>
                            <p style="color: #d63384;"><strong>⚠ Update Available!</strong></p>
                        <?php else: ?>
                            <p style="color: #198754;"><strong>✓ Up to Date</strong></p>
                        <?php endif; ?>
                        
                        <p><strong>Last Checked:</strong> 
                        <?php 
                        if ($last_check && isset($last_check['timestamp'])) {
                            echo esc_html(date('F j, Y g:i A', $last_check['timestamp']));
                        } else {
                            echo 'Never';
                        }
                        ?>
                        </p>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <form method="post" action="">
                        <table class="form-table">
                            <tr>
                                <th scope="row">Auto Updates</th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="auto_update" <?php checked($auto_update); ?> />
                                        Enable automatic theme updates
                                    </label>
                                    <p class="description">When available, the theme will automatically update.</p>
                                </td>
                            </tr>
                        </table>
                        <?php submit_button('Save Settings'); ?>
                    </form>
                    
                    <form method="post" style="margin-top: 10px;">
                        <input type="submit" name="check_updates" 
                               class="button-secondary" value="Check for Updates Now">
                    </form>
                </div>
            </div>
            
            <?php if ($remote_info && !empty($remote_info['changelog'])): ?>
            <div class="card" style="max-width: 600px; margin-top: 20px;">
                <div class="card-body" style="padding: 20px;">
                    <h3>Recent Changes</h3>
                    <div style="max-height: 200px; overflow-y: auto; padding: 10px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px;">
                        <?php echo wp_kses_post(wpautop($remote_info['changelog'])); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="card" style="max-width: 600px; margin-top: 20px;">
                <div class="card-body" style="padding: 20px;">
                    <h3>Update Information</h3>
                    <p>This theme connects to <strong>trinitymetalworks.com</strong> to check for updates. 
                    Updates are delivered through WordPress's standard update system.</p>
                    
                    <?php if (isset($last_check['error'])): ?>
                    <div class="notice notice-error inline">
                        <p><strong>Connection Error:</strong> <?php echo esc_html($last_check['error']); ?></p>
                        <p>Please check your internet connection or contact support.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <style>
        .card {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        .notice.inline {
            margin: 10px 0;
        }
        </style>
        <?php
    }
}

// Initialize the updater
function trinity_init_updater() {
    new Trinity_Theme_Updater();
}
add_action('admin_init', 'trinity_init_updater');
