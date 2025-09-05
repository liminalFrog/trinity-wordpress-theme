/**
 * File navigation.js.
 *
 * Enhanced navigation with Bootstrap functionality and message bar
 */
(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        
        // Top message bar functionality
        const messageBar = document.querySelector('.top-message-bar');
        const messageClose = document.querySelector('.message-close');
        
        if (messageBar && messageClose) {
            messageClose.addEventListener('click', function() {
                messageBar.style.transition = 'all 0.3s ease';
                messageBar.style.transform = 'translateY(-100%)';
                messageBar.style.opacity = '0';
                
                setTimeout(function() {
                    messageBar.style.display = 'none';
                    
                    // Store in sessionStorage to remember for the session
                    sessionStorage.setItem('messageBarClosed', 'true');
                }, 300);
            });
            
            // Check if message bar was closed in this session
            if (sessionStorage.getItem('messageBarClosed') === 'true') {
                messageBar.style.display = 'none';
            }
        }
        
        // Add active class to current menu item
        const currentLocation = location.pathname;
        const menuItems = document.querySelectorAll('.navbar-nav .nav-link');
        
        // First, remove any existing active classes added by our script
        menuItems.forEach(function(item) {
            item.classList.remove('active');
            item.removeAttribute('aria-current');
        });
        
        // Check for WordPress current menu item classes first
        const currentWordPressItems = document.querySelectorAll('.navbar-nav .current-menu-item .nav-link, .navbar-nav .current_page_item .nav-link, .navbar-nav .current-page-ancestor .nav-link');
        if (currentWordPressItems.length > 0) {
            currentWordPressItems.forEach(function(item) {
                item.classList.add('active');
                item.setAttribute('aria-current', 'page');
            });
        } else {
            // Fallback to URL matching if WordPress classes aren't present
            menuItems.forEach(function(item) {
                const itemHref = item.getAttribute('href');
                if (itemHref && (itemHref === currentLocation || 
                    (currentLocation !== '/' && itemHref !== '/' && currentLocation.includes(itemHref)))) {
                    item.classList.add('active');
                    item.setAttribute('aria-current', 'page');
                }
            });
        }

        // Smooth scrolling for anchor links (completely exclude all navbar dropdown elements)
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        anchorLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                
                // Skip all navbar-related links - only process non-navbar anchor links
                if (this.closest('.navbar')) {
                    return; // Don't process any navbar links at all
                }
                
                // Also skip dropdown-related elements specifically
                if (this.closest('.dropdown') || this.classList.contains('dropdown-toggle') || 
                    this.classList.contains('dropdown-item')) {
                    return; // Don't process any dropdown-related links
                }
                
                // Skip any link that just has "#" as href to prevent unwanted scrolling
                if (targetId === '#') {
                    e.preventDefault();
                    return false;
                }
                
                // Only process valid anchor links outside the navbar and dropdowns
                if (targetId.length > 1) {
                    const targetElement = document.querySelector(targetId);
                    
                    // Only scroll if target element actually exists on the page
                    if (targetElement) {
                        e.preventDefault();
                        
                        // Calculate offset for fixed navbar
                        const navbar = document.querySelector('.navbar');
                        const offset = navbar ? navbar.offsetHeight : 0;
                        
                        const targetPosition = targetElement.offsetTop - offset;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                        
                        // Close mobile menu if open
                        const navbarToggle = document.querySelector('.navbar-toggler');
                        const navbarCollapse = document.querySelector('.navbar-collapse');
                        
                        if (navbarToggle && navbarCollapse && navbarCollapse.classList.contains('show')) {
                            navbarToggle.click();
                        }
                    }
                }
            });
        });

        // Additional safety net: prevent any anchor link with href="#" from scrolling
        document.addEventListener('click', function(e) {
            if (e.target.matches('a[href="#"]') || e.target.closest('a[href="#"]')) {
                const link = e.target.matches('a[href="#"]') ? e.target : e.target.closest('a[href="#"]');
                // Only prevent if it's in navbar or dropdown
                if (link.closest('.navbar') || link.closest('.dropdown')) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            const navbar = document.querySelector('.navbar');
            const navbarToggle = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');
            
            if (navbar && navbarToggle && navbarCollapse && 
                navbarCollapse.classList.contains('show') && 
                !navbar.contains(e.target)) {
                navbarToggle.click();
            }
        });

        // Enhanced dropdown hover effect on desktop with multi-level support
        const dropdownElements = document.querySelectorAll('.navbar-nav .dropdown');
        
        dropdownElements.forEach(function(dropdown) {
            const toggle = dropdown.querySelector('.dropdown-toggle');
            const menu = dropdown.querySelector('.dropdown-menu');
            let hoverTimeout;
            
            if (window.innerWidth >= 992) { // Bootstrap lg breakpoint
                // Function to show dropdown
                function showDropdown() {
                    clearTimeout(hoverTimeout);
                    if (!dropdown.classList.contains('show')) {
                        // Manual dropdown show without Bootstrap's show() method to prevent scroll issues
                        dropdown.classList.add('show');
                        toggle.classList.add('show');
                        toggle.setAttribute('aria-expanded', 'true');
                        if (menu) {
                            menu.classList.add('show');
                            menu.setAttribute('data-bs-popper', 'static');
                        }
                    }
                }
                
                // Function to hide dropdown with delay
                function hideDropdown() {
                    hoverTimeout = setTimeout(function() {
                        if (dropdown.classList.contains('show')) {
                            // Manual dropdown hide without Bootstrap's hide() method
                            dropdown.classList.remove('show');
                            toggle.classList.remove('show');
                            toggle.setAttribute('aria-expanded', 'false');
                            if (menu) {
                                menu.classList.remove('show');
                                menu.removeAttribute('data-bs-popper');
                            }
                        }
                    }, 150); // Increased delay for easier navigation
                }
                
                // Show dropdown on parent hover
                dropdown.addEventListener('mouseenter', showDropdown);
                
                // Hide dropdown on parent leave
                dropdown.addEventListener('mouseleave', hideDropdown);
                
                // Handle dropdown toggle click - navigate to page if link exists
                toggle.addEventListener('click', function(e) {
                    const href = toggle.getAttribute('href');
                    
                    // If toggle has a real URL (not #), navigate to it
                    if (href && href !== '#' && href !== 'javascript:void(0)' && href !== '') {
                        // Prevent Bootstrap dropdown from opening
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Navigate to the page
                        window.location.href = href;
                        return false;
                    } else {
                        // Prevent default for toggles without URLs to stop any scroll behavior
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Toggle dropdown manually without Bootstrap methods
                        if (dropdown.classList.contains('show')) {
                            hideDropdown();
                        } else {
                            showDropdown();
                        }
                        return false;
                    }
                });
                
                // Keep dropdown open when hovering over menu items and sub-items
                if (menu) {
                    menu.addEventListener('mouseenter', function() {
                        clearTimeout(hoverTimeout);
                    });
                    
                    menu.addEventListener('mouseleave', hideDropdown);
                    
                    // Handle nested dropdowns (sub-menus)
                    const nestedDropdowns = menu.querySelectorAll('.dropdown');
                    nestedDropdowns.forEach(function(nestedDropdown) {
                        const nestedToggle = nestedDropdown.querySelector('.dropdown-toggle');
                        
                        nestedDropdown.addEventListener('mouseenter', function() {
                            clearTimeout(hoverTimeout);
                        });
                        
                        nestedDropdown.addEventListener('mouseleave', hideDropdown);
                        
                        // Handle nested dropdown toggle click
                        if (nestedToggle) {
                            nestedToggle.addEventListener('click', function(e) {
                                const href = nestedToggle.getAttribute('href');
                                
                                // If toggle has a real URL (not #), navigate to it
                                if (href && href !== '#' && href !== 'javascript:void(0)' && href !== '') {
                                    // Prevent Bootstrap dropdown from interfering and navigate
                                    e.preventDefault();
                                    e.stopPropagation();
                                    window.location.href = href;
                                    return false;
                                } else {
                                    // Prevent default for toggles without URLs to stop scroll behavior
                                    e.preventDefault();
                                    e.stopPropagation();
                                    return false;
                                }
                            });
                        }
                        
                        // Handle sub-sub-menus if they exist
                        const deepNestedMenu = nestedDropdown.querySelector('.dropdown-menu');
                        if (deepNestedMenu) {
                            deepNestedMenu.addEventListener('mouseenter', function() {
                                clearTimeout(hoverTimeout);
                            });
                            
                            deepNestedMenu.addEventListener('mouseleave', hideDropdown);
                        }
                    });
                }
            } else {
                // Mobile behavior - click only
                toggle.addEventListener('click', function(e) {
                    const href = toggle.getAttribute('href');
                    
                    // If toggle has a real URL (not #), navigate to it
                    if (href && href !== '#' && href !== 'javascript:void(0)' && href !== '') {
                        // Navigate to the page on mobile too
                        e.preventDefault();
                        e.stopPropagation();
                        window.location.href = href;
                        return false;
                    } else {
                        // Standard Bootstrap dropdown behavior for mobile, but prevent scroll
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                });
            }
        });

        // Handle window resize for dropdown hover
        window.addEventListener('resize', function() {
            if (window.innerWidth < 992) {
                // Close any open dropdowns on mobile using manual method
                dropdownElements.forEach(function(dropdown) {
                    if (dropdown.classList.contains('show')) {
                        const toggle = dropdown.querySelector('.dropdown-toggle');
                        const menu = dropdown.querySelector('.dropdown-menu');
                        
                        // Manual hide without Bootstrap methods
                        dropdown.classList.remove('show');
                        toggle.classList.remove('show');
                        toggle.setAttribute('aria-expanded', 'false');
                        if (menu) {
                            menu.classList.remove('show');
                            menu.removeAttribute('data-bs-popper');
                        }
                    }
                });
            }
        });

        // Add scroll behavior for navbar
        let lastScrollTop = 0;
        const navbar = document.querySelector('.navbar');
        const scrollThreshold = 50; // Change navbar after scrolling 50px
        
        if (navbar) {
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                // Add scrolled class after threshold
                if (scrollTop > scrollThreshold) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }
                
                // Check if navbar is over a TMHero block
                const tmheroBlocks = document.querySelectorAll('.tmhero-block');
                let isOverTMHero = false;
                
                if (tmheroBlocks.length > 0) {
                    const navbarRect = navbar.getBoundingClientRect();
                    const navbarBottom = navbarRect.bottom;
                    
                    tmheroBlocks.forEach(function(block) {
                        const blockRect = block.getBoundingClientRect();
                        // Check if navbar overlaps with any TMHero block
                        if (blockRect.top < navbarBottom && blockRect.bottom > 0) {
                            isOverTMHero = true;
                        }
                    });
                }
                
                // Apply or remove TMHero class
                if (isOverTMHero) {
                    navbar.classList.add('navbar-over-tmhero');
                } else {
                    navbar.classList.remove('navbar-over-tmhero');
                }
                
                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
            });
        }
    });
})();
