<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f3ed;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #C3905F 0%, #D4A574 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .email-body {
            padding: 30px;
        }
        .info-row {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: 700;
            color: #C3905F;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 16px;
            color: #333;
        }
        .message-content {
            background: #f8f8f8;
            padding: 20px;
            border-radius: 6px;
            border-left: 4px solid #C3905F;
            margin-top: 10px;
        }
        .email-footer {
            background: #f8f8f8;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            background: #C3905F;
            color: white;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>📧 New Contact Form Submission</h1>
        </div>
        
        <div class="email-body">
            <p style="font-size: 16px; margin-bottom: 25px;">
                You have received a new message from your website contact form.
            </p>

            <div class="info-row">
                <div class="info-label">Name</div>
                <div class="info-value">{{ $contactMessage->name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">
                    <a href="mailto:{{ $contactMessage->email }}" style="color: #C3905F; text-decoration: none;">
                        {{ $contactMessage->email }}
                    </a>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Phone</div>
                <div class="info-value">
                    <a href="tel:{{ $contactMessage->phone }}" style="color: #C3905F; text-decoration: none;">
                        {{ $contactMessage->phone }}
                    </a>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">User Type</div>
                <div class="info-value">
                    <span class="badge">{{ ucfirst($contactMessage->user_type) }}</span>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Subject</div>
                <div class="info-value">{{ $contactMessage->subject }}</div>
            </div>

            <div class="info-row" style="border-bottom: none;">
                <div class="info-label">Message</div>
                <div class="message-content">
                    {!! nl2br(e($contactMessage->message)) !!}
                </div>
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #eee;">
                <p style="margin: 0; font-size: 13px; color: #666;">
                    <strong>Submitted:</strong> {{ $contactMessage->created_at->format('F j, Y \a\t g:i A') }}<br>
                    <strong>IP Address:</strong> {{ $contactMessage->ip_address }}<br>
                    <strong>Source:</strong> {{ $contactMessage->source_page }}
                </p>
            </div>
        </div>

        <div class="email-footer">
            <p style="margin: 0;">
                This email was sent from your website contact form.<br>
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
