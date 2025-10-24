# 📧 Email Configuration - Geometric Development CMS

## ✅ Working Email Configuration (Production)

This document contains the **verified working email settings** for `geometric-development.com` on Namecheap hosting.

---

## 📨 SMTP Settings (Outgoing Email)

### **Configuration:**

```env
MAIL_MAILER=smtp
MAIL_HOST=geometric-development.com
MAIL_PORT=465
MAIL_USERNAME=info@geometric-development.com
MAIL_PASSWORD=geometricinfomail.com
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=info@geometric-development.com
MAIL_FROM_NAME="Geometric Development"
```

### **Key Points:**
- ✅ **Port 465** requires **SSL** encryption (not TLS!)
- ✅ **Host:** Use `geometric-development.com` (without `mail.` prefix for SMTP)
- ✅ **Username:** Full email address
- ✅ **From Address:** Must match the email account

---

## 📥 IMAP Settings (Incoming Email - Inbox Viewer)

### **Configuration:**

```env
IMAP_HOST=mail.geometric-development.com
IMAP_PORT=993
IMAP_USERNAME=info@geometric-development.com
IMAP_PASSWORD=geometricinfomail.com
IMAP_ENCRYPTION=ssl
IMAP_VALIDATE_CERT=false
IMAP_PROTOCOL=imap
```

### **Key Points:**
- ✅ **Port 993** with **SSL** encryption
- ✅ **Host:** Use `mail.geometric-development.com` (WITH `mail.` prefix for IMAP!)
- ✅ **IMAP_VALIDATE_CERT=false** - Required for Namecheap shared hosting SSL certificates
- ✅ **Username:** Full email address

---

## 🔐 Email Account Details

| Setting | Value |
|---------|-------|
| **Email Address** | info@geometric-development.com |
| **Password** | geometricinfomail.com |
| **Created in** | cPanel → Email Accounts |
| **Purpose** | Contact form submissions, system notifications |

---

## 📋 Admin Panel Mail Settings

Access: https://geometric-development.com/admin/mail-settings

### **1. Mail Driver Configuration**

| Field | Value |
|-------|-------|
| Mail Driver | SMTP |

### **2. SMTP Settings**

| Field | Value |
|-------|-------|
| Mail Host | geometric-development.com |
| Mail Port | 465 |
| Mail Username | info@geometric-development.com |
| Mail Password | geometricinfomail.com |
| Encryption | SSL |

### **3. Sender Information**

| Field | Value |
|-------|-------|
| From Email Address | info@geometric-development.com |
| From Name | Geometric Development |

### **4. IMAP Settings (For Inbox Viewer)**

| Field | Value |
|-------|-------|
| IMAP Host | mail.geometric-development.com |
| IMAP Port | 993 |
| IMAP Username | info@geometric-development.com |
| IMAP Password | geometricinfomail.com |
| IMAP Encryption | SSL |

---

## 🚀 Testing Email

### **Test via Admin Panel:**

1. Go to: https://geometric-development.com/admin/mail-settings
2. Enter test email address
3. Click "Send Test Email"
4. Check inbox

### **Test via SSH/Artisan:**

```bash
php artisan tinker
```

```php
Mail::raw('Test email from Laravel', function($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email - Geometric Development');
});
// Should output: null (means success)
exit
```

---

## 🔧 Code Fixes Applied

### **1. LogSentEmail Listener (app/Listeners/LogSentEmail.php)**

**Issue:** Symfony\Component\Mime\Address object conversion error

**Fix:** Updated `formatAddresses()` method to handle Address objects:

```php
protected function formatAddresses(?array $addresses): ?string
{
    if (empty($addresses)) {
        return null;
    }

    $formatted = [];
    foreach ($addresses as $address) {
        // Check if it's a Symfony Address object
        if (is_object($address) && method_exists($address, 'getAddress')) {
            $email = $address->getAddress();
            $name = $address->getName();
            $formatted[] = $name ? "$name <$email>" : $email;
        } elseif (is_string($address)) {
            $formatted[] = $address;
        }
    }

    return implode(', ', $formatted);
}
```

### **2. IMAP Inbox (app/Filament/Pages/Inbox.php)**

**Issue:** SSL certificate validation failure, Livewire serialization errors

**Fixes:**
- Disabled SSL certificate validation: `'validate_cert' => false`
- Convert all data to primitive types for Livewire
- Handle Attribute objects from IMAP library
- Convert dates to ISO strings, parse back in Blade

---

## 🌐 Alternative Configuration (TLS - Port 587)

If SSL on port 465 doesn't work, try TLS:

### **SMTP (TLS):**

```env
MAIL_HOST=mail.geometric-development.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
```

### **IMAP (TLS):**

```env
IMAP_HOST=mail.geometric-development.com
IMAP_PORT=143
IMAP_ENCRYPTION=tls
```

---

## ⚠️ Common Issues & Solutions

### **Issue 1: "Connection failed"**

**Cause:** Wrong host or SSL certificate validation  
**Solution:** 
- Use `mail.geometric-development.com` for IMAP
- Set `IMAP_VALIDATE_CERT=false`

### **Issue 2: "Authentication failed"**

**Cause:** Wrong password or username  
**Solution:**
- Verify password: `geometricinfomail.com`
- Use full email as username: `info@geometric-development.com`

### **Issue 3: Port/Encryption Mismatch**

**Cause:** Using TLS with port 465 or SSL with port 587  
**Solution:**

| Port | Encryption |
|------|------------|
| 465 | SSL ✅ |
| 587 | TLS ✅ |
| 25 | None (not recommended) |

### **Issue 4: "Data truncated for column 'user_type'"**

**Cause:** Contact form enum mismatch  
**Solution:** Run migration:

```bash
php artisan migrate
# Applies: 2024_10_24_000000_update_messages_user_type_enum.php
```

---

## 📧 Email Features

### **Working Features:**

✅ **Contact Form** - Submissions sent to info@geometric-development.com  
✅ **Project Inquiries** - Property interest notifications  
✅ **Admin Inbox Viewer** - View received emails at `/admin/inbox`  
✅ **Test Email** - Send test emails from admin panel  
✅ **Mail Logs** - Track all sent emails at `/admin/mail-logs`  

### **Email Templates:**

Located in: `app/Mail/`
- `ContactFormSubmitted.php` - Contact form notifications

---

## 🔒 Security Notes

1. **Never commit `.env` file** - Contains sensitive passwords
2. **SSL validation disabled** - Acceptable for trusted mail servers on same hosting
3. **Sandbox attribute** - Iframe in inbox prevents XSS attacks
4. **Password storage** - Use environment variables only

---

## 📝 Maintenance

### **Check Email Logs:**

```bash
tail -f storage/logs/laravel.log | grep -i mail
```

### **Clear Email Cache:**

```bash
php artisan config:clear
php artisan cache:clear
```

### **Monitor Inbox:**

Visit: https://geometric-development.com/admin/inbox

---

## 🎯 Quick Reference

### **Production .env Settings:**

```env
# SMTP (Outgoing)
MAIL_MAILER=smtp
MAIL_HOST=geometric-development.com
MAIL_PORT=465
MAIL_USERNAME=info@geometric-development.com
MAIL_PASSWORD=geometricinfomail.com
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=info@geometric-development.com
MAIL_FROM_NAME="Geometric Development"

# IMAP (Incoming)
IMAP_HOST=mail.geometric-development.com
IMAP_PORT=993
IMAP_USERNAME=info@geometric-development.com
IMAP_PASSWORD=geometricinfomail.com
IMAP_ENCRYPTION=ssl
IMAP_VALIDATE_CERT=false
IMAP_PROTOCOL=imap
```

---

## 📞 Support

- **Hosting:** Namecheap Shared Hosting
- **Server:** premium165.web-hosting.com
- **cPanel:** https://premium165.web-hosting.com:2083
- **Email Management:** cPanel → Email Accounts

---

## ✅ Verified Working

- **Date Verified:** October 24, 2025
- **Server:** geometric-development.com (41.43.61.54)
- **Email Account:** info@geometric-development.com
- **Test Status:** ✅ Sending works | ✅ Receiving works | ✅ Inbox viewer works

---

**All email functionality is now fully operational! 📬✨**
