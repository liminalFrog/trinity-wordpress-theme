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

        // Smooth scrolling for anchor links
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        anchorLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId !== '#' && targetId.length > 1) {
                    const targetElement = document.querySelector(targetId);
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
                        const bsDropdown = new bootstrap.Dropdown(toggle);
                        bsDropdown.show();
                    }
                }
                
                // Function to hide dropdown with delay
                function hideDropdown() {
                    hoverTimeout = setTimeout(function() {
                        if (dropdown.classList.contains('show')) {
                            const bsDropdown = bootstrap.Dropdown.getInstance(toggle);
                            if (bsDropdown) {
                                bsDropdown.hide();
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
                        e.stopPropagation();
                        
                        // Navigate to the page
                        window.location.href = href;
                        return false;
                    } else {
                        // Prevent default for toggles without URLs
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Toggle dropdown manually
                        if (dropdown.classList.contains('show')) {
                            const bsDropdown = bootstrap.Dropdown.getInstance(toggle);
                            if (bsDropdown) {
                                bsDropdown.hide();
                            }
                        } else {
                            showDropdown();
                        }
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
                                    e.stopPropagation();
                                    window.location.href = href;
                                    return false;
                                } else {
                                    // Prevent default for toggles without URLs
                                    e.preventDefault();
                                    e.stopPropagation();
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
                        e.stopPropagation();
                        window.location.href = href;
                        return false;
                    } else {
                        // Standard Bootstrap dropdown behavior for mobile
                        e.preventDefault();
                    }
                });
            }
        });

        // Handle window resize for dropdown hover
        window.addEventListener('resize', function() {
            if (window.innerWidth < 992) {
                // Close any open dropdowns on mobile
                dropdownElements.forEach(function(dropdown) {
                    if (dropdown.classList.contains('show')) {
                        const toggle = dropdown.querySelector('.dropdown-toggle');
                        const bsDropdown = bootstrap.Dropdown.getInstance(toggle);
                        if (bsDropdown) {
                            bsDropdown.hide();
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
                
                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
            });
        }
    });
})();
