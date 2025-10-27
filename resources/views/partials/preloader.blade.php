<!-- Modern Geometric Preloader -->
@if (settings('preloader_enabled', true))
    <div class="geometric-preloader">
        <div class="preloader-content">
            <!-- Logo and Text Container (moves together) -->
            <div class="logo-text-container">
                <!-- Animated Dot (above logo) -->
                <div class="animated-dot"></div>

                <!-- Logo and Text Wrapper (side by side) -->
                <div class="logo-text-wrapper">
                    <!-- Dynamic Logo (Only show if enabled) -->
                    @if (settings('preloader_use_logo', true))
                        <div class="geometric-logo">
                            @if(settings('preloader_custom_image'))
                                <!-- Use custom preloader image -->
                                <img src="{{ asset('storage/' . settings('preloader_custom_image')) }}"
                                    alt="{{ settings('site_name', 'Geometric Development') }}" class="logo-image">
                            @else
                                <!-- Use website logo -->
                                <img src="{{ asset(settings('logo_light', settings('logo_dark', 'logo.png'))) }}"
                                    alt="{{ settings('site_name', 'Geometric Development') }}" class="logo-image">
                            @endif
                        </div>
                    @endif

                    <!-- Dynamic Brand Text (beside logo) -->
                    <div class="brand-text">
                        <span
                            class="brand-name">{{ strtoupper(settings('preloader_main_text', settings('site_name', 'GEOMETRIC'))) }}</span>
                        <span
                            class="brand-subtitle">{{ strtoupper(settings('preloader_sub_text', settings('site_tagline', 'DEVELOPMENT'))) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Geometric Preloader Styles */
        .geometric-preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            @if (settings('preloader_background_type', 'color') === 'image' && settings('preloader_background_image'))
                background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('{{ asset('storage/' . settings('preloader_background_image')) }}') center/cover no-repeat;
            @else
                background: {{ settings('preloader_background_color', '#060606') }};
            @endif
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            overflow: hidden;
        }

        .preloader-content {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        /* Container for logo and text that moves together */
        .logo-text-container {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: containerSlide 4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        /* Wrapper for logo and text side by side */
        .logo-text-wrapper {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        /* Logo container - appears after dot bounces */
        .geometric-logo {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            opacity: 0;
            animation: logoAppear 4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        /* Animated Dot */
        .animated-dot {
            width: 12px;
            height: 12px;
            background: linear-gradient(135deg, #C3905F 0%, #D4A574 100%);
            border-radius: 50%;
            box-shadow:
                0 0 20px rgba(195, 144, 95, 0.6),
                0 0 40px rgba(195, 144, 95, 0.3);
            animation: dotBounce 1s ease-in-out infinite;
            z-index: 10;
            margin-bottom: 15px;
            opacity: 1;
        }



        .logo-image {
            width: 80px;
            height: 80px;
            object-fit: contain;
            filter:
                brightness(1.2) drop-shadow(0 0 20px rgba(195, 144, 95, 0.4)) drop-shadow(0 0 40px rgba(255, 255, 255, 0.2));
        }

        .logo-circle {
            fill: none;
            stroke: #C3905F;
            stroke-width: 1.5;
            filter: drop-shadow(0 0 10px rgba(195, 144, 95, 0.4));
            animation: logoGlow 2s ease-in-out infinite alternate;
        }

        .logo-shape {
            fill: none;
            stroke: #FFFFFF;
            stroke-width: 1;
            opacity: 0.8;
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.3));
        }

        .logo-center {
            fill: #C3905F;
            filter: drop-shadow(0 0 6px rgba(195, 144, 95, 0.6));
        }

        /* Brand Text */
        .brand-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            opacity: 0;
            animation: textAppear 4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .brand-name {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: clamp(24px, 4vw, 36px);
            font-weight: 800;
            letter-spacing: 4px;
            color: #FFFFFF;
            text-shadow:
                0 0 20px rgba(255, 255, 255, 0.3),
                0 0 40px rgba(195, 144, 95, 0.2);
            margin-bottom: 4px;
        }

        .brand-subtitle {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: clamp(12px, 2vw, 13px);
            font-weight: 600;
            letter-spacing: 6px;
            color: #C3905F;
            text-shadow: 0 0 15px rgba(195, 144, 95, 0.4);
        }

        /* Animations */
        
        /* Dot continuously bounces up and down */
        @keyframes dotBounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-15px);
            }
        }

        /* Logo appears below bouncing dot (after 1 second) */
        @keyframes logoAppear {
            0%, 25% {
                opacity: 0;
                transform: scale(0.8);
            }
            40% {
                opacity: 1;
                transform: scale(1);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Phase 3: Container stays centered (logo+text will be balanced) */
        @keyframes containerSlide {
            0%, 100% {
                transform: translateX(0);
            }
        }

        /* Phase 4: Text appears beside logo (45-60%) */
        @keyframes textAppear {
            0%, 45% {
                opacity: 0;
                transform: translateX(50px);
            }
            60%, 100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes logoGlow {
            from {
                filter: drop-shadow(0 0 10px rgba(195, 144, 95, 0.4));
            }

            to {
                filter: drop-shadow(0 0 20px rgba(195, 144, 95, 0.6));
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .logo-text-wrapper {
                gap: 20px;
            }

            .geometric-logo {
                width: 60px;
                height: 60px;
            }

            .logo-image {
                width: 60px;
                height: 60px;
            }

            .animated-dot {
                width: 10px;
                height: 10px;
            }

            @keyframes containerSlide {
                0%, 100% {
                    transform: translateX(0);
                }
            }
        }

        @media (max-width: 480px) {
            .logo-text-wrapper {
                gap: 15px;
            }

            .geometric-logo {
                width: 50px;
                height: 50px;
            }

            .logo-image {
                width: 50px;
                height: 50px;
            }

            .animated-dot {
                width: 8px;
                height: 8px;
            }

            @keyframes containerSlide {
                0%, 100% {
                    transform: translateX(0);
                }
            }
        }

        /* Preloader Hide Animation */
        .geometric-preloader.fade-out {
            animation: fadeOutPreloader 0.8s ease-out forwards;
        }

        @keyframes fadeOutPreloader {
            to {
                opacity: 0;
                visibility: hidden;
            }
        }
    </style>
@endif
