/**
 * Trinity Theme Customizer JS
 */

(function($) {
    'use strict';

    // Site title and description.
    wp.customize('blogname', function(value) {
        value.bind(function(to) {
            $('.site-title a').text(to);
        });
    });

    wp.customize('blogdescription', function(value) {
        value.bind(function(to) {
            $('.site-description').text(to);
        });
    });

    // Header text color.
    wp.customize('header_textcolor', function(value) {
        value.bind(function(to) {
            if ('blank' === to) {
                $('.site-title, .site-description').css({
                    'clip': 'rect(1px, 1px, 1px, 1px)',
                    'position': 'absolute'
                });
            } else {
                $('.site-title, .site-description').css({
                    'clip': 'auto',
                    'position': 'relative'
                });
                $('.site-title a, .site-description').css({
                    'color': to
                });
            }
        });
    });

    // Theme mode
    wp.customize('trinity_default_mode', function(value) {
        value.bind(function(to) {
            $('body').attr('data-theme', to);
            $('body').removeClass('theme-light theme-dark').addClass('theme-' + to);
            
            // Update navbar
            const $navbar = $('.trinity-navbar');
            $navbar.removeClass('navbar-light navbar-dark bg-light bg-dark');
            
            if (to === 'dark') {
                $navbar.addClass('navbar-dark');
                $('.logo-light').hide();
                $('.logo-dark').show();
            } else {
                $navbar.addClass('navbar-light bg-light');
                $('.logo-dark').hide();
                $('.logo-light').show();
            }
        });
    });

    // Light logo
    wp.customize('trinity_light_logo', function(value) {
        value.bind(function(to) {
            if (to) {
                if ($('.logo-light').length) {
                    $('.logo-light').attr('src', to);
                } else {
                    $('.navbar-brand').prepend('<img src="' + to + '" alt="Logo" class="logo logo-light" height="40">');
                }
            } else {
                $('.logo-light').remove();
            }
        });
    });

    // Dark logo
    wp.customize('trinity_dark_logo', function(value) {
        value.bind(function(to) {
            if (to) {
                if ($('.logo-dark').length) {
                    $('.logo-dark').attr('src', to);
                } else {
                    $('.navbar-brand').prepend('<img src="' + to + '" alt="Logo" class="logo logo-dark" height="40">');
                }
            } else {
                $('.logo-dark').remove();
            }
        });
    });

    // Navbar background images
    wp.customize('trinity_navbar_light_bg', function(value) {
        value.bind(function(to) {
            if (to) {
                document.documentElement.style.setProperty('--trinity-navbar-bg-light', 'url(' + to + ')');
            } else {
                document.documentElement.style.removeProperty('--trinity-navbar-bg-light');
            }
        });
    });

    wp.customize('trinity_navbar_dark_bg', function(value) {
        value.bind(function(to) {
            if (to) {
                document.documentElement.style.setProperty('--trinity-navbar-bg-dark', 'url(' + to + ')');
            } else {
                document.documentElement.style.removeProperty('--trinity-navbar-bg-dark');
            }
        });
    });

    // Announcement bar
    wp.customize('trinity_announcement_bar_enabled', function(value) {
        value.bind(function(to) {
            if (to) {
                if (!$('#announcement-bar').length) {
                    const announcementText = wp.customize('trinity_announcement_text')();
                    const bgColor = wp.customize('trinity_announcement_bg_color')();
                    const textColor = wp.customize('trinity_announcement_text_color')();
                    
                    const announcementBar = `
                        <div id="announcement-bar" class="announcement-bar" style="background-color: ${bgColor}; color: ${textColor};">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 text-center py-2">
                                        <small>${announcementText}</small>
                                        <button type="button" class="btn-close btn-close-white ms-2" aria-label="Close" data-bs-dismiss="alert"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    $('body').prepend(announcementBar);
                    $('.trinity-navbar').css('margin-top', '40px');
                }
            } else {
                $('#announcement-bar').remove();
                $('.trinity-navbar').css('margin-top', '0');
            }
        });
    });

    wp.customize('trinity_announcement_text', function(value) {
        value.bind(function(to) {
            $('#announcement-bar small').html(to);
        });
    });

    wp.customize('trinity_announcement_bg_color', function(value) {
        value.bind(function(to) {
            $('#announcement-bar').css('background-color', to);
        });
    });

    wp.customize('trinity_announcement_text_color', function(value) {
        value.bind(function(to) {
            $('#announcement-bar').css('color', to);
        });
    });

    // GitHub repository
    wp.customize('trinity_github_repo', function(value) {
        value.bind(function(to) {
            // This would typically update admin display or trigger update checks
            console.log('GitHub repository updated:', to);
        });
    });

})(jQuery);
