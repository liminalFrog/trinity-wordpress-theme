/**
 * Trinity Theme JavaScript
 */

(function($) {
    'use strict';

    // Theme object
    const Trinity = {
        
        init: function() {
            this.loadingSpinner();
            this.themeToggle();
            this.setupAutoThemeListener();
            this.navbarScroll();
            this.lazyLoading();
            this.smoothScrolling();
            this.parallaxEffects();
            this.announcementBar();
            this.handlePageHeaderSpacing();
        },

        /**
         * Loading spinner
         */
        loadingSpinner: function() {
            // Hide loading spinner when page is fully loaded
            const hideLoader = function() {
                const loader = document.getElementById('trinity-loading');
                if (loader) {
                    loader.classList.add('hidden');
                    // Remove element completely after transition
                    setTimeout(function() {
                        if (loader.parentNode) {
                            loader.parentNode.removeChild(loader);
                        }
                    }, 500);
                }
            };

            // Use both jQuery (if available) and vanilla JS for maximum compatibility
            if (typeof $ !== 'undefined') {
                $(window).on('load', hideLoader);
            } else {
                if (document.readyState === 'complete') {
                    hideLoader();
                } else {
                    window.addEventListener('load', hideLoader);
                }
            }
            
            // Fallback: Hide after 3 seconds regardless
            setTimeout(hideLoader, 3000);
        },

        /**
         * Theme mode toggle
         */
        themeToggle: function() {
            const $toggleDropdown = $('#theme-toggle');
            const $themeOptions = $('.theme-option');
            const $body = $('body');
            const $themeIcon = $('#theme-icon');
            const $themeText = $('#theme-text');
            
            // Get stored theme preference or default to light
            let currentTheme = localStorage.getItem('trinity-theme') || 'light';
            
            // Set initial theme
            this.setTheme(currentTheme);
            this.updateThemeToggleUI(currentTheme);
            
            // Handle theme option clicks
            $themeOptions.on('click', function(e) {
                e.preventDefault();
                const selectedTheme = $(this).data('theme');
                currentTheme = selectedTheme;
                Trinity.setTheme(currentTheme);
                Trinity.updateThemeToggleUI(currentTheme);
                localStorage.setItem('trinity-theme', currentTheme);
                
                // Close dropdown
                const dropdown = bootstrap.Dropdown.getInstance($toggleDropdown[0]);
                if (dropdown) {
                    dropdown.hide();
                }
            });
        },

        /**
         * Update theme toggle UI
         */
        updateThemeToggleUI: function(theme) {
            const $themeIcon = $('#theme-icon');
            const $themeText = $('#theme-text');
            const $themeOptions = $('.theme-option');
            
            // Remove active class from all options
            $themeOptions.removeClass('active');
            
            // Add active class to current theme option
            $themeOptions.filter(`[data-theme="${theme}"]`).addClass('active');
            
            // Update button icon and text
            switch(theme) {
                case 'auto':
                    $themeIcon.removeClass().addClass('fas fa-adjust');
                    $themeText.text('Auto');
                    break;
                case 'light':
                    $themeIcon.removeClass().addClass('fas fa-sun');
                    $themeText.text('Light');
                    break;
                case 'dark':
                    $themeIcon.removeClass().addClass('fas fa-moon');
                    $themeText.text('Dark');
                    break;
            }
        },

        /**
         * Setup auto theme listener for system changes
         */
        setupAutoThemeListener: function() {
            if (window.matchMedia) {
                const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                
                mediaQuery.addEventListener('change', () => {
                    const currentTheme = localStorage.getItem('trinity-theme') || 'auto';
                    if (currentTheme === 'auto') {
                        this.setTheme('auto');
                    }
                });
            }
        },

        /**
         * Set theme mode
         */
        setTheme: function(theme) {
            const $body = $('body');
            const $navbar = $('.trinity-navbar');
            let actualTheme = theme;
            
            // Handle auto mode - detect system preference
            if (theme === 'auto') {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    actualTheme = 'dark';
                } else {
                    actualTheme = 'light';
                }
            }
            
            $body.attr('data-theme', actualTheme);
            $body.removeClass('theme-light theme-dark').addClass('theme-' + actualTheme);
            
            // Update logos
            if (actualTheme === 'dark') {
                $('.logo-light').hide();
                $('.logo-dark').show();
            } else {
                $('.logo-dark').hide();
                $('.logo-light').show();
            }
            
            // Update navbar background
            this.updateNavbarBackground(actualTheme);
        },

        /**
         * Update navbar background based on theme
         */
        updateNavbarBackground: function(theme) {
            const $navbar = $('.trinity-navbar');
            $navbar.removeClass('navbar-light navbar-dark bg-light bg-dark');
            
            if (theme === 'dark') {
                $navbar.addClass('navbar-dark');
            } else {
                $navbar.addClass('navbar-light bg-light');
            }
        },

        /**
         * Navbar scroll effects
         */
        navbarScroll: function() {
            const $navbar = $('.trinity-navbar');
            const $window = $(window);
            
            $window.on('scroll', function() {
                const scrollTop = $window.scrollTop();
                const currentTheme = $('body').attr('data-theme');
                
                if (scrollTop > 100) {
                    $navbar.addClass('navbar-scrolled');
                    
                    // Switch to dark mode variant on scroll if in light mode
                    if (currentTheme === 'light') {
                        $navbar.removeClass('navbar-light bg-light').addClass('navbar-dark');
                        $('.logo-light').hide();
                        $('.logo-dark').show();
                    }
                } else {
                    $navbar.removeClass('navbar-scrolled');
                    
                    // Restore original theme styling
                    Trinity.updateNavbarBackground(currentTheme);
                    
                    if (currentTheme === 'light') {
                        $('.logo-dark').hide();
                        $('.logo-light').show();
                    }
                }
            });
        },

        /**
         * Lazy loading for images
         */
        lazyLoading: function() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            imageObserver.unobserve(img);
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }
        },

        /**
         * Smooth scrolling for anchor links
         */
        smoothScrolling: function() {
            $('a[href*="#"]:not([href="#"])').on('click', function(e) {
                if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && 
                    location.hostname === this.hostname) {
                    
                    let target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    
                    if (target.length) {
                        e.preventDefault();
                        const offset = $('.trinity-navbar').outerHeight() + 20;
                        
                        $('html, body').animate({
                            scrollTop: target.offset().top - offset
                        }, 1000, 'easeInOutExpo');
                    }
                }
            });
        },

        /**
         * Parallax effects
         */
        parallaxEffects: function() {
            $('.parallax').each(function() {
                const $element = $(this);
                const speed = $element.data('speed') || 0.5;
                
                $(window).on('scroll', function() {
                    const scrollTop = $(this).scrollTop();
                    const elementTop = $element.offset().top;
                    const elementHeight = $element.outerHeight();
                    const windowHeight = $(this).height();
                    
                    if ((elementTop - windowHeight) <= scrollTop && (elementTop + elementHeight) >= scrollTop) {
                        const yPos = Math.round((scrollTop - elementTop) * speed);
                        $element.css('transform', `translateY(${yPos}px)`);
                    }
                });
            });
        },

        /**
         * Announcement bar functionality
         */
        announcementBar: function() {
            const $announcementBar = $('#announcement-bar');
            
            if ($announcementBar.length) {
                // Check if user has dismissed the announcement
                if (localStorage.getItem('trinity-announcement-dismissed') === 'true') {
                    $announcementBar.hide();
                }
                
                // Handle dismiss button
                $announcementBar.find('.btn-close').on('click', function() {
                    $announcementBar.fadeOut();
                    localStorage.setItem('trinity-announcement-dismissed', 'true');
                });
            }
        },

        /**
         * Handle page header spacing
         */
        handlePageHeaderSpacing: function() {
            const pageHeader = document.querySelector('.page-header');
            const body = document.body;
            
            if (pageHeader) {
                body.classList.add('has-page-header');
            } else {
                body.classList.remove('has-page-header');
            }
            
            // Also handle dynamic content changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList') {
                        const pageHeader = document.querySelector('.page-header');
                        if (pageHeader) {
                            body.classList.add('has-page-header');
                            Trinity.handlePageHeaderMargin();
                        } else {
                            body.classList.remove('has-page-header');
                        }
                    }
                });
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
            
            // Initial check for page header margin
            this.handlePageHeaderMargin();
        },

        /**
         * Handle page header margin based on following elements
         */
        handlePageHeaderMargin: function() {
            const pageHeader = document.querySelector('.page-header');
            if (!pageHeader) return;
            
            const nextElement = pageHeader.nextElementSibling;
            
            // Check if the next element is TMHero or full-width carousel
            const isFollowedBySpecialBlock = nextElement && (
                nextElement.classList.contains('tmhero-block') ||
                nextElement.classList.contains('wp-block-tmhero-hero-block') ||
                (nextElement.classList.contains('trinity-carousel') && 
                 (nextElement.classList.contains('full-width') || 
                  nextElement.classList.contains('trinity-carousel-full-width')))
            );
            
            if (isFollowedBySpecialBlock) {
                pageHeader.classList.add('no-margin-bottom');
            } else {
                pageHeader.classList.remove('no-margin-bottom');
            }
        },

        /**
         * Initialize tooltips and popovers
         */
        initBootstrapComponents: function() {
            // Tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        },

        /**
         * Handle carousel auto-sizing
         */
        carouselInit: function() {
            $('.carousel').each(function() {
                const $carousel = $(this);
                const height = $carousel.data('height') || 400;
                
                $carousel.find('.carousel-item').css('height', height + 'px');
            });
        },

        /**
         * Form enhancements
         */
        formEnhancements: function() {
            // Add Bootstrap classes to WordPress forms
            $('.comment-form input, .comment-form textarea, .search-form input').addClass('form-control');
            $('.comment-form input[type="submit"], .search-form input[type="submit"]').removeClass('form-control').addClass('btn btn-primary');
            
            // Form validation
            $('form').on('submit', function(e) {
                const $form = $(this);
                let isValid = true;
                
                $form.find('[required]').each(function() {
                    const $field = $(this);
                    
                    if (!$field.val()) {
                        $field.addClass('is-invalid');
                        isValid = false;
                    } else {
                        $field.removeClass('is-invalid').addClass('is-valid');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    $form.find('.is-invalid').first().focus();
                }
            });
        },

        /**
         * Image zoom functionality
         */
        imageZoom: function() {
            $('.zoom-image').on('click', function(e) {
                e.preventDefault();
                const imageSrc = $(this).attr('href') || $(this).find('img').attr('src');
                
                // Create modal for image zoom
                const modal = `
                    <div class="modal fade" id="imageZoomModal" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content bg-transparent border-0">
                                <div class="modal-body p-0">
                                    <img src="${imageSrc}" class="img-fluid w-100" alt="">
                                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('body').append(modal);
                $('#imageZoomModal').modal('show').on('hidden.bs.modal', function() {
                    $(this).remove();
                });
            });
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        Trinity.init();
        Trinity.initBootstrapComponents();
        Trinity.carouselInit();
        Trinity.formEnhancements();
        Trinity.imageZoom();
    });

    // Fallback initialization if jQuery is not available
    if (typeof $ === 'undefined') {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                Trinity.init();
            });
        } else {
            Trinity.init();
        }
    }

    // Expose Trinity object globally
    window.Trinity = Trinity;

})(jQuery);

// Easing function for smooth scrolling
jQuery.easing.easeInOutExpo = function(x, t, b, c, d) {
    if (t === 0) return b;
    if (t === d) return b + c;
    if ((t /= d / 2) < 1) return c / 2 * Math.pow(2, 10 * (t - 1)) + b;
    return c / 2 * (-Math.pow(2, -10 * --t) + 2) + b;
};
