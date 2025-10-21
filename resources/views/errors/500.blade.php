@php
    // Check if request is from admin panel
    if (request()->is('admin/*') || request()->is('admin')) {
        // Admin Panel 500 - Completely standalone, NO frontend layout
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f5f3ed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .error-container {
            text-align: center;
            padding: 2rem;
        }
        .error-header {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 3rem;
            letter-spacing: -0.5px;
        }
        .error-code {
            font-size: 15rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 2rem;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><defs><linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:%23C3905F;stop-opacity:0.3"/><stop offset="100%" style="stop-color:%23D4A574;stop-opacity:0.3"/></linearGradient></defs><rect width="1200" height="600" fill="url(%23grad)"/><path d="M0,300 Q300,200 600,300 T1200,300" stroke="%23C3905F" stroke-width="3" fill="none" opacity="0.3"/><path d="M0,350 Q300,250 600,350 T1200,350" stroke="%23C3905F" stroke-width="2" fill="none" opacity="0.2"/><circle cx="200" cy="150" r="80" fill="%23C3905F" opacity="0.1"/><circle cx="1000" cy="450" r="120" fill="%23D4A574" opacity="0.1"/></svg>') center/cover;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 30px rgba(195, 144, 95, 0.1);
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
        }
        .error-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 2.5rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .btn {
            display: inline-block;
            padding: 1rem 3rem;
            background: #C3905F;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 1rem;
            text-transform: lowercase;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(195, 144, 95, 0.3);
        }
        .btn:hover {
            background: #b17f4f;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(195, 144, 95, 0.4);
        }
        .footer-credit {
            position: absolute;
            bottom: 2rem;
            left: 0;
            right: 0;
            text-align: center;
            color: #7f8c8d;
            font-size: 0.875rem;
        }
        .footer-credit a {
            color: #C3905F;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .footer-credit a:hover {
            color: #b17f4f;
        }
        @media (max-width: 768px) {
            .error-code {
                font-size: 8rem;
            }
            .error-header {
                font-size: 1.75rem;
            }
            .error-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h3 class="error-header">Admin Panel</h3>
        <h1 class="error-code">500</h1>
        <h2 class="error-title">Server Error</h2>
        <a href="<?php echo route('filament.admin.pages.dashboard'); ?>" class="btn">back to dashboard</a>
    </div>
    
    <div class="footer-credit">
        © <?php echo date('Y'); ?> Geometric Development. All rights reserved | Designed and Developed By <a href="https://elnakieb.online" target="_blank" rel="noopener">Elnakieb</a>
    </div>
</body>
</html>
<?php
        exit; // Stop execution, don't render frontend layout
    }
@endphp

{{-- Frontend 500 Page --}}
@extends('layouts.app')

@section('title', '500 - Server Error - Geometric Development')

@section('body-class', '')

@section('content')
<section class="bs-error-page-area pt-160 pb-160">
    <div class="container bs-container-1">
        <div class="bs-error-page-wrap">
            <div class="bs-error-page-content">
                <h4 class="bs-h-4 title-1 ">Server Error!</h4>
                <h4 class="bs-h-4 title-2 ">500 Error!</h4>
                <p class="bs-p-4 disc">Something went wrong on our server. We're working to fix it. Please try again later.</p>

                <a href="{{ route('home') }}" aria-label="name" class="bs-btn-1">
                    <span class="text">
                        back to home page
                    </span>
                    <span class="icon">
                        <i class="fa-solid fa-right-long"></i>
                        <i class="fa-solid fa-right-long"></i>
                    </span>
                    <span class="shape" ></span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
