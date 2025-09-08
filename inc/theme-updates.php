<?php
/**
 * Trinity Theme Updates
 * 
 * @package Trinity
 */

/**
 * Theme updater class
 */
class Trinity_Theme_Updater {
    
    private $github_repo;
    private $theme_slug;
    private $version;
    
    public function __construct() {
        $this->github_repo = get_theme_mod('trinity_github_repo', '');
        $this->theme_slug = get_option('stylesheet');
        $this->version = TRINITY_VERSION;
        
        if (!empty($this->github_repo)) {
            add_filter('pre_set_site_transient_update_themes', [$this, 'check_for_update']);
            add_action('admin_init', [$this, 'add_update_page']);
        }
    }
    
    /**
     * Check for theme updates
     */
    public function check_for_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }
        
        $remote_version = $this->get_remote_version();
        
        if (version_compare($this->version, $remote_version, '<')) {
            $transient->response[$this->theme_slug] = [
                'theme' => $this->theme_slug,
                'new_version' => $remote_version,
                'url' => $this->github_repo,
                'package' => $this->get_download_url()
            ];
        }
        
        return $transient;
    }
    
    /**
     * Get remote version from GitHub
     */
    private function get_remote_version() {
        if (empty($this->github_repo)) {
            return false;
        }
        
        // Parse GitHub URL to get API endpoint
        $repo_parts = $this->parse_github_url($this->github_repo);
        if (!$repo_parts) {
            return false;
        }
        
        $api_url = "https://api.github.com/repos/{$repo_parts['owner']}/{$repo_parts['repo']}/releases/latest";
        
        $request = wp_remote_get($api_url);
        
        if (is_wp_error($request)) {
            return false;
        }
        
        $body = wp_remote_retrieve_body($request);
        $data = json_decode($body, true);
        
        if (isset($data['tag_name'])) {
            return ltrim($data['tag_name'], 'v');
        }
        
        return false;
    }
    
    /**
     * Get download URL
     */
    private function get_download_url() {
        $repo_parts = $this->parse_github_url($this->github_repo);
        if (!$repo_parts) {
            return false;
        }
        
        return "https://github.com/{$repo_parts['owner']}/{$repo_parts['repo']}/archive/refs/heads/master.zip";
    }
    
    /**
     * Parse GitHub URL
     */
    private function parse_github_url($url) {
        $pattern = '/github\.com\/([^\/]+)\/([^\/]+)/';
        
        if (preg_match($pattern, $url, $matches)) {
            return [
                'owner' => $matches[1],
                'repo' => rtrim($matches[2], '.git')
            ];
        }
        
        return false;
    }
    
    /**
     * Add update page to admin
     */
    public function add_update_page() {
        add_theme_page(
            esc_html__('Trinity Theme Updates', 'trinity'),
            esc_html__('Theme Updates', 'trinity'),
            'manage_options',
            'trinity-updates',
            [$this, 'update_page_content']
        );
    }
    
    /**
     * Update page content
     */
    public function update_page_content() {
        if (isset($_POST['trinity_update_theme']) && wp_verify_nonce($_POST['trinity_update_nonce'], 'trinity_update_theme')) {
            $this->perform_git_pull();
        }
        
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Trinity Theme Updates', 'trinity'); ?></h1>
            
            <div class="card">
                <h2 class="title"><?php esc_html_e('Current Version', 'trinity'); ?></h2>
                <p><?php echo esc_html($this->version); ?></p>
                
                <?php if (!empty($this->github_repo)) : ?>
                    <h2 class="title"><?php esc_html_e('GitHub Repository', 'trinity'); ?></h2>
                    <p><a href="<?php echo esc_url($this->github_repo); ?>" target="_blank"><?php echo esc_html($this->github_repo); ?></a></p>
                    
                    <form method="post" action="">
                        <?php wp_nonce_field('trinity_update_theme', 'trinity_update_nonce'); ?>
                        <p>
                            <input type="submit" name="trinity_update_theme" class="button button-primary" value="<?php esc_attr_e('Pull Latest from GitHub', 'trinity'); ?>">
                        </p>
                        <p class="description">
                            <?php esc_html_e('This will pull the latest changes from the master branch of your GitHub repository.', 'trinity'); ?>
                        </p>
                    </form>
                <?php else : ?>
                    <div class="notice notice-warning">
                        <p><?php esc_html_e('No GitHub repository configured. Please set the repository URL in the Customizer.', 'trinity'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Perform git pull
     */
    private function perform_git_pull() {
        $theme_dir = get_template_directory();
        
        // Check if .git directory exists
        if (!is_dir($theme_dir . '/.git')) {
            echo '<div class="notice notice-error"><p>' . esc_html__('This theme directory is not a git repository.', 'trinity') . '</p></div>';
            return;
        }
        
        // Change to theme directory and pull
        $old_cwd = getcwd();
        chdir($theme_dir);
        
        $output = [];
        $return_var = 0;
        
        exec('git pull origin master 2>&1', $output, $return_var);
        
        chdir($old_cwd);
        
        if ($return_var === 0) {
            echo '<div class="notice notice-success"><p>' . esc_html__('Theme updated successfully!', 'trinity') . '</p></div>';
            echo '<div class="notice notice-info"><p><strong>Output:</strong><br>' . implode('<br>', array_map('esc_html', $output)) . '</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>' . esc_html__('Update failed. Please check the output below:', 'trinity') . '</p></div>';
            echo '<div class="notice notice-error"><p><strong>Error Output:</strong><br>' . implode('<br>', array_map('esc_html', $output)) . '</p></div>';
        }
    }
}

// Initialize the updater
new Trinity_Theme_Updater();
?>
