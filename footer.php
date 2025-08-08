    <footer id="colophon" class="site-footer">
        <div class="footer-content">
            <div class="container">
                <div class="row">
                    <!-- Footer Column 1 -->
                    <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                        <?php if (is_active_sidebar('footer-1')) : ?>
                            <?php dynamic_sidebar('footer-1'); ?>
                        <?php else : ?>
                            <!-- Default Content: Navigation -->
                            <h5 class="footer-title">Quick Links</h5>
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'footer-menu',
                                'menu_class'     => 'footer-nav',
                                'container'      => false,
                                'depth'          => 1,
                                'fallback_cb'    => '__return_false',
                            ));
                            ?>
                        <?php endif; ?>
                    </div>

                    <!-- Footer Column 2 -->
                    <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                        <?php if (is_active_sidebar('footer-2')) : ?>
                            <?php dynamic_sidebar('footer-2'); ?>
                        <?php else : ?>
                            <!-- Default Content: Contact Info -->
                            <h5 class="footer-title">Contact</h5>
                            <div class="footer-contact">
                                <p class="mb-2">
                                    <i class="fas fa-envelope me-2"></i>
                                    <a href="mailto:info@<?php echo parse_url(home_url(), PHP_URL_HOST); ?>">info@<?php echo parse_url(home_url(), PHP_URL_HOST); ?></a>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-phone me-2"></i>
                                    <a href="tel:+1234567890">+1 (234) 567-890</a>
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    Your Address Here
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer Column 3 -->
                    <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                        <?php if (is_active_sidebar('footer-3')) : ?>
                            <?php dynamic_sidebar('footer-3'); ?>
                        <?php else : ?>
                            <!-- Default Content: Social Media -->
                            <h5 class="footer-title">Follow Us</h5>
                            <div class="footer-social">
                                <a href="#" class="social-link" aria-label="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="social-link" aria-label="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="social-link" aria-label="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="social-link" aria-label="LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer Column 4 -->
                    <div class="col-lg-3 col-md-6">
                        <?php if (is_active_sidebar('footer-4')) : ?>
                            <?php dynamic_sidebar('footer-4'); ?>
                        <?php else : ?>
                            <!-- Default Content: Newsletter -->
                            <h5 class="footer-title">Newsletter</h5>
                            <p class="newsletter-text">Subscribe to get the latest news and updates.</p>
                            <form class="newsletter-form">
                                <div class="input-group">
                                    <input type="email" class="form-control" placeholder="Your email" aria-label="Email">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="footer-info">
                            <p class="mb-2 mb-md-0">
                                &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.
                            </p>
                            <!-- <p class="mb-0">
                                Powered by <a href="https://wordpress.org/" target="_blank">WordPress</a> | 
                                Theme: My Custom Theme with <a href="https://getbootstrap.com/" target="_blank">Bootstrap</a>
                            </p> -->
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="footer-logo">
                            <?php if (has_custom_logo()) : ?>
                                <?php 
                                // Get the custom logo
                                $custom_logo_id = get_theme_mod('custom_logo');
                                $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                                
                                // Check for light logo option
                                $light_logo_id = get_theme_mod('light_logo');
                                $light_logo = $light_logo_id ? wp_get_attachment_image_src($light_logo_id, 'full') : null;
                                
                                // Use light logo if available, otherwise use normal logo
                                if ($light_logo) : ?>
                                    <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo-link">
                                        <img src="<?php echo esc_url($light_logo[0]); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="footer-logo-img">
                                    </a>
                                <?php else : ?>
                                    <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo-link">
                                        <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="footer-logo-img">
                                    </a>
                                <?php endif; ?>
                            <?php else : ?>
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-site-title">
                                    <?php bloginfo('name'); ?>
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
