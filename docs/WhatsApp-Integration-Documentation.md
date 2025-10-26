# 📘 WhatsApp Cloud API Integration Documentation

**For:** Geometric Development  
**Purpose:** Complete WhatsApp Cloud API integration with Laravel website  
**Date:** October 2025  
**Status:** Production Ready  

---

## 🎯 Table of Contents

1. [Overview](#overview)
2. [Prerequisites](#prerequisites)
3. [Meta Developer Setup](#meta-developer-setup)
4. [WhatsApp Cloud API Configuration](#whatsapp-cloud-api-configuration)
5. [Laravel Backend Implementation](#laravel-backend-implementation)
6. [Frontend Widget Implementation](#frontend-widget-implementation)
7. [Admin Panel Integration](#admin-panel-integration)
8. [SSL Certificate Handling](#ssl-certificate-handling)
9. [Testing & Debugging](#testing--debugging)
10. [Production Deployment](#production-deployment)
11. [Troubleshooting](#troubleshooting)
12. [API Reference](#api-reference)

---

## 🧩 1. Overview

The WhatsApp Cloud API integration enables real-time WhatsApp communication directly from your website. This implementation includes:

- **Real-time chat widget** on frontend pages
- **Admin panel management** for WhatsApp settings
- **Template message support** with dynamic parameters
- **SSL certificate handling** for development environments
- **Comprehensive error handling** and debugging tools
- **Production-ready architecture** with fallback systems

### Key Features Implemented

✅ **Frontend Chat Widget** - Diamond-shaped button matching site design  
✅ **Admin Panel Integration** - Complete settings management  
✅ **Template Messages** - Static and dynamic content support  
✅ **SSL Handling** - Automatic development/production detection  
✅ **Error Handling** - User-friendly error messages  
✅ **Debug Tools** - Command-line debugging utilities  
✅ **Responsive Design** - Mobile-optimized interface  

---

## 🔧 2. Prerequisites

Before starting, ensure you have:

- **Meta Developer Account** → [developers.facebook.com](https://developers.facebook.com/)
- **Facebook Business Page** (verified)
- **Meta Business Account** (verified)
- **Laravel Application** (v10+)
- **HTTPS Domain** (for production webhooks)
- **Basic API Knowledge**

### Required PHP Extensions
```bash
php -m | grep -E "(curl|openssl|json)"
```

---

## 🚀 3. Meta Developer Setup

### Step 1: Create Meta App

1. Go to [Meta Developer Console](https://developers.facebook.com/apps)
2. Click **"Create App"**
3. Choose **"Business"** type
4. Fill details:
   - **App Name:** `Geometric WhatsApp Integration`
   - **App Email:** `your-email@geometric-development.com`
   - **Business Account:** Select your business account
5. Click **"Create App"**

### Step 2: Add WhatsApp Product

1. In app dashboard, find **"Add a Product"**
2. Locate **"WhatsApp"** → Click **"Set Up"**
3. You'll be redirected to WhatsApp Cloud API setup

### Step 3: Configure Use Case

1. Select **"Connect with customers through WhatsApp"**
2. Description: `"Real-time customer support and communication"`
3. Connect to your business portfolio

---

## 🔑 4. WhatsApp Cloud API Configuration

### API Credentials

After setup, collect these credentials:

| Field | Example Value | Description |
|-------|---------------|-------------|
| **App ID** | `1154925629468568` | Your Meta App ID |
| **Phone Number ID** | `883502414836900` | WhatsApp Business Phone ID |
| **Access Token** | `EAAQaZAhPi75gBP...` | API Authentication Token |
| **App Secret** | `abc123def456...` | Webhook Verification Secret |
| **Webhook Verify Token** | `geometric_secure_token` | Custom verification token |

### Test Message Example

```bash
curl -i -X POST \
  'https://graph.facebook.com/v22.0/883502414836900/messages' \
  -H 'Authorization: Bearer YOUR_ACCESS_TOKEN' \
  -H 'Content-Type: application/json' \
  -d '{
    "messaging_product": "whatsapp",
    "to": "201095646948",
    "type": "template",
    "template": {
      "name": "hello_world",
      "language": { "code": "en_US" }
    }
  }'
```

---

## 💻 5. Laravel Backend Implementation

### Environment Configuration

Add to `.env`:

```env
# WhatsApp API Configuration
WHATSAPP_APP_ID=1154925629468568
WHATSAPP_PHONE_NUMBER_ID=883502414836900
WHATSAPP_ACCESS_TOKEN=EAAQaZAhPi75gBP...
WHATSAPP_APP_SECRET=abc123def456...
WHATSAPP_WEBHOOK_VERIFY_TOKEN=geometric_secure_token

# WhatsApp API Settings
WHATSAPP_TIMEOUT=30
WHATSAPP_CONNECT_TIMEOUT=10
WHATSAPP_SSL_VERIFY=null
WHATSAPP_API_VERSION=v18.0
WHATSAPP_BASE_URL=https://graph.facebook.com
```

### Core Service Implementation

**File:** `app/Services/WhatsAppCloudService.php`

Key features implemented:
- ✅ **SSL Certificate Handling** - Automatic dev/prod detection
- ✅ **Template Message Support** - Static and dynamic templates
- ✅ **Error Handling** - Comprehensive exception handling
- ✅ **Retry Logic** - Automatic retry with exponential backoff
- ✅ **Fallback System** - Template fallback to hello_world

### Database Models

**ChatSetting Model** - Stores WhatsApp configuration:
```php
// Encrypted storage for sensitive data
ChatSetting::set('whatsapp_access_token', $token, true, 'WhatsApp Access Token');

// Plain storage for non-sensitive data
ChatSetting::set('chat_enabled', 'true', false, 'Chat Widget Enabled');
```

### API Routes

**File:** `routes/api.php`

```php
Route::prefix('whatsapp')->group(function () {
    Route::post('/webhook', [WhatsAppController::class, 'webhook']);
    Route::get('/webhook', [WhatsAppController::class, 'verify']);
    Route::post('/send', [WhatsAppController::class, 'sendMessage']);
});
```

---

## 🎨 6. Frontend Widget Implementation

### Widget Design

The WhatsApp widget matches your site's back-to-top button design:

- **Shape:** Diamond (45° rotated square)
- **Size:** 40x40px
- **Position:** Fixed right side, `bottom: 10%`
- **Color:** WhatsApp green (`#25D366`)
- **Hover Effects:** Shadow animation matching back-to-top

### Widget Features

✅ **Real-time Messaging** - WebSocket support  
✅ **Offline Queue** - Messages queued when offline  
✅ **Typing Indicators** - Real-time typing status  
✅ **Message Status** - Sent/Delivered/Read indicators  
✅ **Responsive Design** - Mobile optimized  
✅ **Accessibility** - ARIA labels and keyboard navigation  

### Widget Files

- **HTML:** `resources/views/partials/whatsapp-chat.blade.php`
- **JavaScript:** `public/assets/js/whatsapp-chat.js`
- **Styling:** Embedded CSS with widget HTML

---

## ⚙️ 7. Admin Panel Integration

### Filament Admin Panel

**File:** `app/Filament/Pages/ChatSettings.php`

### Features Implemented

1. **API Configuration Tab**
   - App ID, Phone Number ID, Access Token
   - App Secret, Webhook URL, Verify Token
   - Real-time connection testing

2. **Chat Settings Tab**
   - Enable/disable chat widget
   - Auto-reply configuration
   - Business hours settings
   - Welcome and away messages

3. **Testing Tab**
   - Connection status indicator
   - Template vs text message options
   - Dynamic parameter toggle
   - Live message preview
   - SSL environment notices

4. **Troubleshooting Section**
   - Test mode vs production explanation
   - Dynamic parameter documentation
   - Common issues and solutions

### Admin Panel Actions

- **Test Connection** - Verify API credentials
- **Send Test Message** - Send template or text messages
- **Dynamic Preview** - Live preview of template content

---

## 🔒 8. SSL Certificate Handling

### Automatic Environment Detection

The system automatically handles SSL certificates based on environment:

**Development Mode:**
```php
// SSL verification disabled automatically
if (app()->environment(['local', 'development', 'testing'])) {
    $options['verify'] = false;
}
```

**Production Mode:**
```php
// SSL verification enabled with CA bundle detection
$caBundlePaths = [
    storage_path('app/cacert.pem'), // Custom bundle
    '/etc/ssl/certs/ca-certificates.crt', // Debian/Ubuntu
    '/etc/pki/tls/certs/ca-bundle.crt',   // RHEL/CentOS
];
```

### SSL Certificate Download Command

```bash
php artisan whatsapp:download-ca-bundle
```

This command downloads the latest CA certificate bundle for production use.

---

## 🧪 9. Testing & Debugging

### Debug Command

```bash
# Test text message
php artisan whatsapp:debug +201095646948 "Test message"

# Test template message
php artisan whatsapp:debug +201095646948 --template=hello_world
```

### Debug Features

✅ **Connection Testing** - Verify API credentials  
✅ **Account Mode Detection** - Test vs Production mode  
✅ **Template Listing** - Available templates  
✅ **Message Status** - Delivery confirmation  
✅ **Error Analysis** - Detailed error reporting  

### Common Test Scenarios

1. **Template Message Test:**
   ```bash
   php artisan whatsapp:debug +201095646948 --template=hello_world
   ```

2. **Connection Verification:**
   - Admin Panel → Chat Settings → Test Connection

3. **Dynamic Template Test:**
   - Admin Panel → Testing Tab → Template Message → Enable Dynamic Parameters

---

## 🌐 10. Production Deployment

### Pre-Production Checklist

- [ ] **Business Verification** completed in Meta Business Manager
- [ ] **WhatsApp Business Account** approved
- [ ] **Production Access Token** obtained
- [ ] **HTTPS Domain** configured for webhooks
- [ ] **SSL Certificates** properly configured
- [ ] **Environment Variables** set correctly

### Production Environment Variables

```env
APP_ENV=production
WHATSAPP_SSL_VERIFY=true
WHATSAPP_CA_BUNDLE=/path/to/cacert.pem
```

### Webhook Configuration

**Production Webhook URL:**
```
https://yourdomain.com/api/whatsapp/webhook
```

**Webhook Events to Subscribe:**
- `messages` - Incoming messages
- `message_deliveries` - Delivery status
- `message_reads` - Read receipts

---

## 🔧 11. Troubleshooting

### Common Issues & Solutions

#### 1. SSL Certificate Errors

**Error:** `cURL error 60: SSL certificate problem`

**Solution:**
```bash
# Download CA bundle
php artisan whatsapp:download-ca-bundle

# Or disable SSL for development
WHATSAPP_SSL_VERIFY=false
```

#### 2. Template Not Found

**Error:** `Template name does not exist in the translation`

**Solution:**
- System automatically falls back to `hello_world` template
- Check available templates in Meta Business Manager
- Ensure template is approved

#### 3. Messages Not Delivered

**Possible Causes:**
- Account in test mode (messages won't reach actual WhatsApp)
- Invalid phone number format
- No active conversation window for text messages
- Rate limiting exceeded

**Solution:**
- Use template messages for guaranteed delivery
- Verify phone number format (+countrycode)
- Complete business verification for production

#### 4. Webhook Verification Failed

**Error:** `Webhook verification failed`

**Solution:**
```php
// Ensure webhook verify token matches
WHATSAPP_WEBHOOK_VERIFY_TOKEN=your_secure_token
```

### Debug Logs Location

- **Laravel Logs:** `storage/logs/laravel.log`
- **WhatsApp API Logs:** Search for `WhatsApp API` in logs
- **Admin Panel:** Activity Logs section

---

## 📚 12. API Reference

### WhatsApp Cloud API Endpoints

#### Send Template Message
```http
POST https://graph.facebook.com/v18.0/{phone-number-id}/messages
Authorization: Bearer {access-token}
Content-Type: application/json

{
  "messaging_product": "whatsapp",
  "to": "phone_number",
  "type": "template",
  "template": {
    "name": "template_name",
    "language": { "code": "en_US" }
  }
}
```

#### Send Text Message
```http
POST https://graph.facebook.com/v18.0/{phone-number-id}/messages
Authorization: Bearer {access-token}
Content-Type: application/json

{
  "messaging_product": "whatsapp",
  "to": "phone_number",
  "type": "text",
  "text": { "body": "message_text" }
}
```

### Laravel Service Methods

#### WhatsAppCloudService

```php
// Send template message
$service->sendTemplate($phoneNumber, $templateName, $parameters);

// Send text message
$service->sendMessage($phoneNumber, $messageText);

// Test connection
$service->testConnection();

// Check production mode
$service->isProductionMode();
```

### Artisan Commands

```bash
# Download CA certificates
php artisan whatsapp:download-ca-bundle

# Debug WhatsApp integration
php artisan whatsapp:debug {phone} {message?} {--template=}

# Clear Filament cache
php artisan filament:clear-cached-components
```

---

## 📊 13. Configuration Summary

### Complete Environment Configuration

```env
# App Configuration
APP_NAME="Geometric Development"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# WhatsApp API Configuration
WHATSAPP_APP_ID=1154925629468568
WHATSAPP_PHONE_NUMBER_ID=883502414836900
WHATSAPP_ACCESS_TOKEN=EAAQaZAhPi75gBP...
WHATSAPP_APP_SECRET=abc123def456...
WHATSAPP_WEBHOOK_VERIFY_TOKEN=geometric_secure_token

# WhatsApp API Settings
WHATSAPP_TIMEOUT=30
WHATSAPP_CONNECT_TIMEOUT=10
WHATSAPP_SSL_VERIFY=null
WHATSAPP_API_VERSION=v18.0
WHATSAPP_BASE_URL=https://graph.facebook.com
```

### Admin Panel Configuration

Access via: `/admin/chat-settings`

**Required Fields:**
- App ID
- Phone Number ID  
- Access Token
- App Secret
- Webhook URL
- Webhook Verify Token

**Optional Settings:**
- Welcome Message
- Away Message
- Business Hours
- Auto-Reply Settings

---

## ✅ 14. Implementation Status

### Completed Features

- [x] **Meta Developer App Setup**
- [x] **WhatsApp Cloud API Integration**
- [x] **Laravel Backend Service**
- [x] **Frontend Chat Widget**
- [x] **Admin Panel Interface**
- [x] **SSL Certificate Handling**
- [x] **Template Message Support**
- [x] **Dynamic Parameters**
- [x] **Error Handling & Debugging**
- [x] **Responsive Design**
- [x] **Production Ready Code**

### Next Steps (Optional Enhancements)

- [ ] **Message History Storage**
- [ ] **Multi-language Templates**
- [ ] **Advanced Analytics**
- [ ] **Chatbot Integration**
- [ ] **File Upload Support**
- [ ] **Group Messaging**

---

## 📞 Support & Maintenance

### Key Files to Monitor

- `app/Services/WhatsAppCloudService.php` - Core API service
- `app/Filament/Pages/ChatSettings.php` - Admin interface
- `resources/views/partials/whatsapp-chat.blade.php` - Frontend widget
- `public/assets/js/whatsapp-chat.js` - Widget JavaScript

### Regular Maintenance Tasks

1. **Monitor Access Token Expiry** - Tokens expire periodically
2. **Update CA Certificates** - Run download command monthly
3. **Check Template Approvals** - New templates need Meta approval
4. **Review Error Logs** - Monitor for API issues
5. **Test Message Delivery** - Regular functionality testing

### Contact Information

**Developer:** Kiro AI Assistant  
**Project:** Geometric Development WhatsApp Integration  
**Documentation Version:** 1.0  
**Last Updated:** October 2025  

---

*This documentation covers the complete WhatsApp Cloud API integration implemented for Geometric Development. All features are production-ready and thoroughly tested.*