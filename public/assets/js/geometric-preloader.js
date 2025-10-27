/**
 * Geometric Preloader Controller
 * Waits for all content to load before hiding
 */

(function() {
    'use strict';
    
    // Prevent body scroll while preloader is active
    document.documentElement.style.overflow = 'hidden';
    document.body.style.overflow = 'hidden';
    
    function initGeometricPreloader() {
        const preloader = document.querySelector('.geometric-preloader');
        
        if (!preloader) {
            document.documentElement.style.overflow = '';
            document.body.style.overflow = '';
            return;
        }
        
        let imagesLoaded = false;
        let domReady = false;
        let minTimeElapsed = false;
        
        // Minimum display time (4 seconds to show full animation)
        setTimeout(() => {
            minTimeElapsed = true;
            checkAndHidePreloader();
        }, 4000);
        
        // Wait for DOM
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                domReady = true;
                loadImages();
            });
        } else {
            domReady = true;
            loadImages();
        }
        
        // Load all images
        function loadImages() {
            const images = document.querySelectorAll('img');
            const totalImages = images.length;
            
            if (totalImages === 0) {
                imagesLoaded = true;
                checkAndHidePreloader();
                return;
            }
            
            let loadedCount = 0;
            
            function imageLoaded() {
                loadedCount++;
                if (loadedCount >= totalImages) {
                    imagesLoaded = true;
                    checkAndHidePreloader();
                }
            }
            
            images.forEach(img => {
                if (img.complete) {
                    imageLoaded();
                } else {
                    img.addEventListener('load', imageLoaded);
                    img.addEventListener('error', imageLoaded);
                }
            });
        }
        
        // Check if ready to hide
        function checkAndHidePreloader() {
            if (domReady && imagesLoaded && minTimeElapsed) {
                hidePreloader();
            }
        }
        
        // Hide preloader
        function hidePreloader() {
            // Re-enable scrolling
            document.documentElement.style.overflow = '';
            document.body.style.overflow = '';
            
            // Add fade out class
            preloader.classList.add('fade-out');
            
            // Remove from DOM after animation
            setTimeout(() => {
                if (preloader.parentNode) {
                    preloader.remove();
                }
            }, 800);
        }
        
        // Backup: Force hide after 10 seconds
        setTimeout(() => {
            if (preloader && !preloader.classList.contains('fade-out')) {
                console.warn('Preloader forced to hide after timeout');
                hidePreloader();
            }
        }, 10000);
    }
    
    // Initialize immediately
    initGeometricPreloader();
    
})();
