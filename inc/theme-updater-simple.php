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
        
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }
    
    /**
     * Check for theme updates (simplified - no external dependencies)
     */
    public function check_for_update($transient) {
        // For now, this is a placeholder
        // In a real implementation, you'd check your server for updates
        return $transient;
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
        if (isset($_POST['submit'])) {
            update_option('trinity_auto_update', isset($_POST['auto_update']));
            echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
        }
        
        $auto_update = get_option('trinity_auto_update', false);
        $theme = wp_get_theme();
        ?>
        <div class="wrap">
            <h1>Trinity Theme Updates</h1>
            
            <div class="card" style="max-width: 600px;">
                <div class="card-body" style="padding: 20px;">
                    <h2>Theme Information</h2>
                    <p><strong>Theme:</strong> <?php echo $theme->get('Name'); ?></p>
                    <p><strong>Version:</strong> <?php echo $theme->get('Version'); ?></p>
                    <p><strong>Author:</strong> <?php echo $theme->get('Author'); ?></p>
                    
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
                </div>
            </div>
            
            <div class="card" style="max-width: 600px; margin-top: 20px;">
                <div class="card-body" style="padding: 20px;">
                    <h3>Update Information</h3>
                    <p>This is a custom theme. Updates will be managed manually or through your hosting provider.</p>
                    
                    <h4>For Manual Updates:</h4>
                    <ol>
                        <li>Download the latest theme version</li>
                        <li>Go to Appearance → Themes</li>
                        <li>Click "Add New" → "Upload Theme"</li>
                        <li>Upload the new theme file</li>
                        <li>Activate if needed</li>
                    </ol>
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
        </style>
        <?php
    }
}

// Initialize the updater
function trinity_init_updater() {
    new Trinity_Theme_Updater();
}
add_action('admin_init', 'trinity_init_updater');
