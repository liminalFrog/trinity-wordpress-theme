    </div><!-- #content -->

    <!-- Footer -->
    <footer id="colophon" class="site-footer mt-5">
        <!-- Footer Widgets -->
        <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) : ?>
            <div class="footer-widgets py-5">
                <div class="container">
                    <div class="row">
                        <?php for ($i = 1; $i <= 4; $i++) : ?>
                            <?php if (is_active_sidebar('footer-' . $i)) : ?>
                                <div class="col-lg-3 col-md-6 mb-4">
                                    <?php dynamic_sidebar('footer-' . $i); ?>
                                </div>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Copyright Section -->
        <div class="footer-bottom bg-dark text-light py-3">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">
                            &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. 
                            <?php esc_html_e('All rights reserved.', 'trinity'); ?>
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="d-flex align-items-center justify-content-md-end gap-3">
                            <!-- Theme Mode Toggle -->
                            <div class="theme-toggle dropdown">
                                <button type="button" class="btn btn-outline-light btn-sm dropdown-toggle" id="theme-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-sun" id="theme-icon"></i>
                                    <span class="toggle-text ms-1" id="theme-text"><?php esc_html_e('Light', 'trinity'); ?></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="theme-toggle">
                                    <li>
                                        <a class="dropdown-item theme-option" href="#" data-theme="auto">
                                            <i class="fas fa-adjust me-2"></i>
                                            <?php esc_html_e('Auto', 'trinity'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item theme-option" href="#" data-theme="light">
                                            <i class="fas fa-sun me-2"></i>
                                            <?php esc_html_e('Light', 'trinity'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item theme-option" href="#" data-theme="dark">
                                            <i class="fas fa-moon me-2"></i>
                                            <?php esc_html_e('Dark', 'trinity'); ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                            <!-- Footer Logo -->
                            <?php
                            $dark_logo = get_theme_mod('trinity_dark_logo');
                            if ($dark_logo) : ?>
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo">
                                    <img src="<?php echo esc_url($dark_logo); ?>" 
                                         alt="<?php bloginfo('name'); ?>" 
                                         class="footer-logo-img" 
                                         height="32">
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
