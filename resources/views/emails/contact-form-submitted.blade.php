<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #525f7f;
            background: #f6f9fc;
            margin: 0;
            padding: 40px 20px;
        }
        
        .email-container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            background: #ffffff;
        }
        
        .email-header {
            background: #ffffff;
            padding: 40px 60px 40px 60px;
            border: 3px solid #C3905F;
            border-bottom: none;
        }
        
        .logo-section {
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo-section img {
            height: 50px;
            width: auto;
        }
        
        .website-title {
            font-size: 24px;
            font-weight: 700;
            color: #32325d;
            margin: 0;
        }
        
        .email-body {
            padding: 40px 60px;
            background: #f6f9fc;
        }
        
        .greeting {
            font-size: 16px;
            color: #525f7f;
            margin-bottom: 20px;
        }
        
        .main-content {
            font-size: 16px;
            color: #525f7f;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .contact-info-section {
            background: #ffffff;
            border: 1px solid #e6ebf1;
            border-radius: 6px;
            padding: 30px;
            margin: 30px 0;
        }
        
        .sender-name {
            font-size: 22px;
            font-weight: 600;
            color: #32325d;
            margin-bottom: 8px;
        }
        
        .sender-type {
            font-size: 14px;
            color: #C3905F;
            font-weight: 500;
            margin-bottom: 20px;
        }
        
        .contact-details {
            margin-bottom: 25px;
        }
        
        .contact-row {
            display: flex;
            margin-bottom: 12px;
            align-items: center;
        }
        
        .contact-label {
            font-size: 14px;
            color: #8898aa;
            font-weight: 500;
            min-width: 80px;
        }
        
        .contact-value {
            font-size: 16px;
            color: #32325d;
            font-weight: 500;
        }
        
        .contact-value a {
            color: #C3905F;
            text-decoration: none;
        }
        
        .contact-value a:hover {
            text-decoration: underline;
        }
        
        .subject-section {
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-left: 4px solid #C3905F;
            border-radius: 4px;
        }
        
        .subject-label {
            font-size: 12px;
            color: #8898aa;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .subject-text {
            font-size: 16px;
            color: #32325d;
            font-weight: 600;
        }
        
        .message-section {
            margin-top: 25px;
        }
        
        .message-label {
            font-size: 12px;
            color: #8898aa;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }
        
        .message-content {
            background: #ffffff;
            border: 1px solid #e6ebf1;
            border-radius: 6px;
            padding: 20px;
            font-size: 15px;
            line-height: 1.6;
            color: #525f7f;
            white-space: pre-wrap;
        }
        
        .footer-section {
            background: #f6f9fc;
            padding: 30px 60px;
            border-top: 1px solid #e6ebf1;
        }
        
        .footer-text {
            font-size: 13px;
            color: #8898aa;
            margin-bottom: 15px;
        }
        
        .footer-text a {
            color: #C3905F;
            text-decoration: none;
        }
        
        .footer-text a:hover {
            text-decoration: underline;
        }
        
        .company-info {
            font-size: 13px;
            color: #8898aa;
            margin-bottom: 20px;
        }
        
        .footer-brand {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .brand-name {
            font-size: 16px;
            font-weight: 600;
            color: #8898aa;
        }
        
        .contact-links {
            display: flex;
            gap: 20px;
        }
        
        .contact-link {
            font-size: 13px;
            color: #C3905F;
            text-decoration: none;
        }
        
        .contact-link:hover {
            text-decoration: underline;
            border-bottom-color: #C3905F;
        }
        
        .metadata-section {
            background: #f8f9fa;
            border: 1px solid #e6ebf1;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .metadata-title {
            font-size: 12px;
            color: #8898aa;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }
        
        .metadata-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .metadata-item {
            font-size: 13px;
        }
        
        .metadata-label {
            color: #8898aa;
            font-weight: 500;
            margin-bottom: 2px;
        }
        
        .metadata-value {
            color: #32325d;
            font-weight: 600;
        }
        
        @media (max-width: 600px) {
            body {
                padding: 20px 10px;
            }
            
            .email-header, .email-body, .footer-section {
                padding: 30px 20px;
            }
            
            .contact-row {
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 15px;
            }
            
            .contact-label {
                margin-bottom: 4px;
            }
            
            .footer-brand {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .contact-links {
                justify-content: center;
            }
            
            .metadata-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="logo-section">
                <img src="{{ asset(settings('logo_dark', 'logo.png')) }}" alt="{{ settings('site_name', 'Geometric Development') }}">
                <h1 class="website-title">{{ settings('site_name', 'Geometric Development') }}</h1>
            </div>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            <div class="greeting">Hi there,</div>
            
            <div class="main-content">
                You have received a new contact message through your website. All the details are provided below.
            </div>
            
            <div class="contact-info-section">
                <div class="sender-name">{{ $contactMessage->name }}</div>
                <div class="sender-type">{{ ucfirst($contactMessage->user_type) }} Contact</div>
                
                <div class="contact-details">
                    <div class="contact-row">
                        <span class="contact-label">Email:</span>
                        <span class="contact-value">
                            <a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a>
                        </span>
                    </div>
                    <div class="contact-row">
                        <span class="contact-label">Phone:</span>
                        <span class="contact-value">
                            <a href="tel:{{ $contactMessage->phone }}">{{ $contactMessage->phone }}</a>
                        </span>
                    </div>
                </div>
                
                <div class="subject-section">
                    <div class="subject-label">Subject</div>
                    <div class="subject-text">{{ $contactMessage->subject }}</div>
                </div>
                
                <div class="message-section">
                    <div class="message-label">Message</div>
                    <div class="message-content">{{ $contactMessage->message }}</div>
                </div>
            </div>
            
            <div class="metadata-section">
                <div class="metadata-title">Submission Details</div>
                <div class="metadata-grid">
                    <div class="metadata-item">
                        <div class="metadata-label">Submitted:</div>
                        <div class="metadata-value">{{ $contactMessage->created_at->format('M j, Y g:i A') }}</div>
                    </div>
                    <div class="metadata-item">
                        <div class="metadata-label">IP Address:</div>
                        <div class="metadata-value">{{ $contactMessage->ip_address }}</div>
                    </div>
                    <div class="metadata-item">
                        <div class="metadata-label">Source Page:</div>
                        <div class="metadata-value">{{ $contactMessage->source_page }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer-section">
            <div class="footer-text">
                This email was sent to you from your website contact form. 
                If you'd rather not receive this kind of email, you can 
                <a href="#">unsubscribe or manage your email preferences</a>.
            </div>
            
            <div class="company-info">
                {{ settings('site_name', 'Geometric Development') }}, {{ settings('site_tagline', 'Professional Web Development & Design Services') }}
            </div>
            
            <div class="footer-brand">
                <div class="brand-name">{{ settings('site_name', 'Geometric Development') }}</div>
                <div class="contact-links">
                    <a href="mailto:{{ settings('contact_email', 'info@geometricdevelopment.com') }}" class="contact-link">{{ settings('contact_email', 'info@geometricdevelopment.com') }}</a>
                    <a href="tel:{{ settings('contact_phone', '+1234567890') }}" class="contact-link">{{ settings('contact_phone', '+1 (234) 567-890') }}</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>