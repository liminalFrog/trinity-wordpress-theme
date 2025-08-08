<?php
/**
 * Trinity Theme Updater
 * 
 * This class handles theme updates from GitHub releases
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Trinity_Theme_Updater {
    
    private $theme_slug;
    private $version;
    private $author;
    private $github_username;
    private $github_repo;
    private $github_access_token;
    
    public function __construct($theme_slug, $version, $author, $github_username, $github_repo, $access_token = '') {
        $this->theme_slug = $theme_slug;
        $this->version = $version;
        $this->author = $author;
        $this->github_username = $github_username;
        $this->github_repo = $github_repo;
        $this->github_access_token = $access_token;
        
        add_filter('pre_set_site_transient_update_themes', array($this, 'check_for_update'));
        add_filter('themes_api', array($this, 'theme_api_call'), 10, 3);
    }
    
    /**
     * Check for theme updates
     */
    public function check_for_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }
        
        // Get remote version
        $remote_version = $this->get_remote_version();
        
        if (version_compare($this->version, $remote_version, '<')) {
            $transient->response[$this->theme_slug] = array(
                'theme' => $this->theme_slug,
                'new_version' => $remote_version,
                'url' => $this->get_github_repo_url(),
                'package' => $this->get_download_url($remote_version),
            );
        }
        
        return $transient;
    }
    
    /**
     * Get remote version from GitHub
     */
    private function get_remote_version() {
        $request = wp_remote_get($this->get_api_url());
        
        if (!is_wp_error($request) && wp_remote_retrieve_response_code($request) === 200) {
            $body = wp_remote_retrieve_body($request);
            $data = json_decode($body, true);
            
            if (isset($data['tag_name'])) {
                return $data['tag_name'];
            }
        }
        
        return $this->version;
    }
    
    /**
     * Get GitHub API URL for latest release
     */
    private function get_api_url() {
        return "https://api.github.com/repos/{$this->github_username}/{$this->github_repo}/releases/latest";
    }
    
    /**
     * Get GitHub repository URL
     */
    private function get_github_repo_url() {
        return "https://github.com/{$this->github_username}/{$this->github_repo}";
    }
    
    /**
     * Get download URL for specific version
     */
    private function get_download_url($version) {
        return "https://github.com/{$this->github_username}/{$this->github_repo}/archive/refs/tags/{$version}.zip";
    }
    
    /**
     * Handle theme API calls
     */
    public function theme_api_call($result, $action, $args) {
        if ($action !== 'theme_information' || $args->slug !== $this->theme_slug) {
            return $result;
        }
        
        $remote_version = $this->get_remote_version();
        
        return (object) array(
            'slug' => $this->theme_slug,
            'name' => 'Trinity',
            'version' => $remote_version,
            'author' => $this->author,
            'homepage' => $this->get_github_repo_url(),
            'description' => 'A custom WordPress theme created for local development with Bootstrap and auto-update capability.',
            'download_link' => $this->get_download_url($remote_version),
        );
    }
    
    /**
     * Add settings page for updater configuration
     */
    public static function add_settings_page() {
        add_theme_page(
            'Trinity Theme Updates',
            'Theme Updates',
            'manage_options',
            'trinity-updates',
            array(__CLASS__, 'settings_page')
        );
    }
    
    /**
     * Settings page content
     */
    public static function settings_page() {
        if (isset($_POST['submit'])) {
            update_option('trinity_auto_update', isset($_POST['auto_update']));
            update_option('trinity_github_token', sanitize_text_field($_POST['github_token']));
            echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
        }
        
        $auto_update = get_option('trinity_auto_update', false);
        $github_token = get_option('trinity_github_token', '');
        ?>
        <div class="wrap">
            <h1>Trinity Theme Updates</h1>
            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th scope="row">Auto Update</th>
                        <td>
                            <label>
                                <input type="checkbox" name="auto_update" <?php checked($auto_update); ?> />
                                Enable automatic theme updates
                            </label>
                            <p class="description">Automatically update the theme when new versions are available.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">GitHub Access Token</th>
                        <td>
                            <input type="password" name="github_token" value="<?php echo esc_attr($github_token); ?>" class="regular-text" />
                            <p class="description">Optional: GitHub personal access token for private repositories or higher rate limits.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
            
            <h2>Update Information</h2>
            <p><strong>Current Version:</strong> <?php echo wp_get_theme()->get('Version'); ?></p>
            <p><strong>Repository:</strong> <a href="https://github.com/liminalFrog/trinity-wordpress-theme" target="_blank">GitHub Repository</a></p>
            
            <h3>How to Set Up Updates</h3>
            <ol>
                <li>Create a GitHub repository for your theme</li>
                <li>Push your theme code to the repository</li>
                <li>Create releases with version tags (e.g., v1.0.0, v1.1.0)</li>
                <li>Updates will be automatically detected</li>
            </ol>
        </div>
        <?php
    }
}

// Initialize the updater if auto-updates are enabled
function trinity_init_updater() {
    $auto_update = get_option('trinity_auto_update', false);
    $github_token = get_option('trinity_github_token', '');
    
    if ($auto_update) {
        $theme = wp_get_theme();
        new Trinity_Theme_Updater(
            get_template(),
            $theme->get('Version'),
            $theme->get('Author'),
            'liminalFrog', // Replace with your GitHub username
            'trinity-wordpress-theme', // Replace with your repository name
            $github_token
        );
    }
}
add_action('init', 'trinity_init_updater');

// Add settings page
add_action('admin_menu', array('Trinity_Theme_Updater', 'add_settings_page'));
