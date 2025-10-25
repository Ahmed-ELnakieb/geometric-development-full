/**
 * Enhanced Preloader & Page Transition System
 * Standalone file to prevent conflicts with other JavaScript
 */

(function() {
    'use strict';
    
    function initPageTransition() {
        
        const preloader = document.querySelector('.lw-preloader');
        const transitionOverlay = document.querySelector('.page-transition-overlay');
        const transitionLayer = document.querySelector('.transition-layer');
        const mainContent = document.querySelector('.main-content-wrapper');
        let transitionStarted = false;
        
        // Only run if preloader exists
        if (!preloader) {
            return;
        }
        
        // Start as soon as DOM is ready so the layer is part of preloader phase
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', startTransitionSequence, { once: true });
        } else {
            startTransitionSequence();
        }
        
        function startTransitionSequence() {
            if (transitionStarted) {
                return;
            }
            transitionStarted = true;

            // Check if we have transition overlay (for pages with the effect)
            if (transitionOverlay && transitionLayer) {
                
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
                    });
                });
                
                // Step 2: Hide preloader when layer fully covers it
                setTimeout(function() {
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
                        }
                    }, 100);
                }, 2600); // Preloader hides when layer fully covers it + 100ms
                
                // Step 3: Wait a moment, then slide overlay back to reveal page
                setTimeout(function() {
                    // Gold overlay slides right to reveal static page (0 to 100%)
                    transitionLayer.style.transform = 'translateX(100%)';
                }, 2800); // Wait 300ms after full coverage before sliding back
                
                // Step 4: Clean up overlay (wait for slower slide-out to complete)
                // 2800ms (wait) + 2000ms (slide-out) = 4800ms
                setTimeout(function() {
                    transitionOverlay.classList.remove('active');
                }, 4800);
                
                // Step 5: Final cleanup of transition layer
                setTimeout(function() {
                    transitionOverlay.style.display = 'none';
                }, 5200);
            } else {
                // Fallback for pages without transition overlay - simple preloader hide
                setTimeout(function() {
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
                startTransitionSequence();
            }
        }, 3000); // Backup after 3 seconds
    }

    // Initialize the transition system for all pages - with safe timing
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initPageTransition();
        });
    } else {
        initPageTransition();
    }
    
})();
