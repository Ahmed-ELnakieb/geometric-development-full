/**
 * Enhanced Preloader & Page Transition System
 * Standalone file to prevent conflicts with other JavaScript
 */

(function() {
    'use strict';
    
    console.log('Preloader.js loaded');
    
    function initPageTransition() {
        console.log('initPageTransition called');
        
        const preloader = document.querySelector('.lw-preloader');
        const transitionOverlay = document.querySelector('.page-transition-overlay');
        const transitionLayer = document.querySelector('.transition-layer');
        const mainContent = document.querySelector('.main-content-wrapper');
        let transitionStarted = false;
        
        // Only run if preloader exists
        if (!preloader) {
            console.log('No preloader found, skipping transition');
            return;
        }
        
        console.log('Preloader found, initializing...');
        
        // Start as soon as DOM is ready so the layer is part of preloader phase
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', startTransitionSequence, { once: true });
        } else {
            startTransitionSequence();
        }
        
        function startTransitionSequence() {
            if (transitionStarted) {
                console.log('Transition already started, skipping');
                return;
            }
            transitionStarted = true;
            console.log('Starting transition sequence');

            // Check if we have transition overlay (for pages with the effect)
            if (transitionOverlay && transitionLayer) {
                console.log('Transition overlay found, applying cinematic effect');
                
                // Step 1: Prepare layer fully off-screen right and then animate to cover
                transitionOverlay.classList.add('active');
                transitionLayer.style.transition = 'none';
                transitionLayer.style.transform = 'translateX(100%)';
                
                // Force reflow to ensure the browser applies the initial position
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        transitionLayer.style.transition = '';
                        // Animate to fully cover (from 100% to 0) - right to left
                        transitionLayer.style.transform = 'translateX(0)';
                        console.log('Layer animation started');
                    });
                });
                
                // Step 2: Hide preloader when layer fully covers it
                setTimeout(function() {
                    console.log('Hiding preloader at:', Date.now());
                    // Override all CSS transitions for immediate hide
                    preloader.style.transition = 'none';
                    preloader.style.display = 'none';
                    preloader.style.visibility = 'hidden';
                    preloader.style.opacity = '0';
                    preloader.style.zIndex = '-9999';
                    // Force immediate removal
                    setTimeout(() => {
                        if (preloader.parentNode) {
                            preloader.remove();
                            console.log('Preloader removed from DOM');
                        }
                    }, 100);
                }, 2600); // Preloader hides when layer fully covers it + 100ms
                
                // Step 3: Wait a moment, then slide overlay back to reveal page
                setTimeout(function() {
                    // Gold overlay slides right to reveal static page (0 to 100%)
                    transitionLayer.style.transform = 'translateX(100%)';
                    console.log('Layer sliding out to reveal page');
                }, 2800); // Wait 300ms after full coverage before sliding back
                
                // Step 4: Clean up overlay (wait for slower slide-out to complete)
                // 2800ms (wait) + 2000ms (slide-out) = 4800ms
                setTimeout(function() {
                    transitionOverlay.classList.remove('active');
                    console.log('Transition overlay deactivated');
                }, 4800);
                
                // Step 5: Final cleanup of transition layer
                setTimeout(function() {
                    transitionOverlay.style.display = 'none';
                    console.log('Transition complete');
                }, 5200);
            } else {
                console.log('No transition overlay, using simple preloader hide');
                // Fallback for pages without transition overlay - simple preloader hide
                setTimeout(function() {
                    console.log('Simple preloader hide at:', Date.now());
                    preloader.style.transition = 'none';
                    preloader.style.display = 'none';
                    preloader.style.visibility = 'hidden';
                    preloader.style.opacity = '0';
                    setTimeout(() => {
                        if (preloader.parentNode) {
                            preloader.remove();
                        }
                    }, 100);
                }, 2500); // Standard preloader time for other pages
            }
        }
        
        // Backup method in case DOMContentLoaded doesn't fire (rare)
        setTimeout(function() {
            if (!transitionStarted) {
                console.log('Backup timer triggered, starting transition');
                startTransitionSequence();
            }
        }, 3000); // Backup after 3 seconds
    }

    // Initialize the transition system for all pages - with safe timing
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOMContentLoaded - initializing preloader');
            initPageTransition();
        });
    } else {
        console.log('DOM already loaded - initializing preloader immediately');
        initPageTransition();
    }
    
})();
