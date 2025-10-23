# 📧 Inbox Viewer Setup Guide

## What It Does

The **Inbox Viewer** allows you to view **RECEIVED emails** (emails that arrive in your inbox) directly in the admin panel.

---

## Features

✅ View received emails from your inbox in admin panel  
✅ Read emails with formatted content  
✅ See unread email count  
✅ Mark emails as read automatically when viewing  
✅ Beautiful email list view with sender, subject, preview  
✅ Full email viewer with HTML rendering  
✅ Attachment count display  
✅ Real-time refresh  

---

## Setup Instructions

### Step 1: Install Package ✅ (Already Done)
```bash
composer require webklex/php-imap
```
✅ **Installed:** v6.2

### Step 2: Configure IMAP Settings

#### For Gmail Users:

1. **Go to Mail Settings:**
   - Visit `/admin/mail-settings`
   - Expand **"IMAP Settings (For Inbox Viewer)"** section

2. **Fill in IMAP Configuration:**
   ```
   IMAP Host: imap.gmail.com
   IMAP Port: 993
   IMAP Username: your-email@gmail.com
   IMAP Password: [Your App Password]
   Encryption: SSL
   ```

3. **Get Gmail App Password:**
   - Go to https://myaccount.google.com/security
   - Enable **2-Step Verification** (required)
   - Go to **App passwords**
   - Select "Mail" and "Windows Computer"
   - Copy the 16-character password
   - Paste it in **IMAP Password** field

4. **Click "Save Configuration"**

### Step 3: Access Inbox

1. Go to `/admin/inbox` or click **"Inbox"** in Mail menu
2. You'll see all your received emails!

---

## Gmail Configuration

### IMAP Settings for Gmail:
```env
IMAP_HOST=imap.gmail.com
IMAP_PORT=993
IMAP_USERNAME=your-email@gmail.com
IMAP_PASSWORD=your-16-char-app-password
IMAP_ENCRYPTION=ssl
```

### Enable IMAP in Gmail:
1. Gmail Settings → See all settings
2. Forwarding and POP/IMAP tab
3. Enable IMAP
4. Save Changes

---

## Other Email Providers

### Outlook/Office 365:
```env
IMAP_HOST=outlook.office365.com
IMAP_PORT=993
IMAP_ENCRYPTION=ssl
```

### Yahoo Mail:
```env
IMAP_HOST=imap.mail.yahoo.com
IMAP_PORT=993
IMAP_ENCRYPTION=ssl
```

### Custom/Business Email:
Ask your hosting provider for IMAP settings.

---

## Admin Panel Navigation

```
Mail
├─ 📬 Mails (Sent Emails)
├─ ⚙️ Mail Settings (SMTP + IMAP Config)
├─ 📨 Mails (Plugin)
└─ 📥 Inbox (RECEIVED Emails) ← NEW!
```

---

## Features in Detail

### Email List View:
- **Sender name** and email address
- **Subject** line
- **Preview** of first 150 characters
- **Date** (relative: "2 hours ago")
- **Unread indicator** (blue dot + highlighted row)
- **Stats** showing total emails and unread count

### Email Viewer:
- **Full email details** (from, to, date, attachments)
- **HTML rendering** in safe iframe
- **Plain text fallback** if no HTML
- **Click to view** any email
- **Auto-mark as read**
- **Close button** to return to list

### Refresh:
- Click **"Refresh Inbox"** button to load latest emails
- Fetches last 50 emails from inbox

---

## Troubleshooting

### Error: "IMAP not configured"
**Solution:** Configure IMAP settings in Mail Settings page

### Error: "Authentication failed"
**Solution:** 
- Gmail: Use App Password, not regular password
- Enable IMAP in email settings
- Check username is correct (usually full email)

### Error: "Connection refused"
**Solution:**
- Check IMAP host and port
- Verify encryption setting (SSL for port 993)
- Check firewall isn't blocking port 993

### No emails shown
**Solution:**
- Check inbox has emails
- Try clicking "Refresh Inbox"
- Verify IMAP username/password are correct

---

## Security Notes

- IMAP password stored in `.env` file (secure)
- Emails rendered in sandboxed iframe (safe)
- SSL/TLS encryption required
- App passwords recommended over regular passwords
- Read-only access (cannot send/delete emails)

---

## What This Shows

### ✅ Shows (Received Emails):
- Contact form submissions sent to your inbox
- Client emails
- Newsletter subscriptions
- Any email sent TO your inbox

### ❌ Doesn't Show (Sent Emails):
- Emails YOUR APP sends (see "Mails" resource for those)
- Emails in other folders (only shows INBOX)

---

## Comparison

### Inbox Page (NEW):
- Shows emails **YOU RECEIVE** in your inbox
- Reads from Gmail/Outlook/etc via IMAP
- Real inbox viewer

### Mails Resource (Existing):
- Shows emails **YOUR APP SENDS** to people
- Logs outgoing notifications
- Sent items tracker

---

## Quick Start

1. **Configure IMAP** in Mail Settings
2. **Visit** `/admin/inbox`
3. **See your emails** instantly!

---

## Example Use Case

**Scenario:** Client sends you an email asking about a project

**Without Inbox Viewer:**
- Open Gmail/Outlook
- Find email
- Read and respond

**With Inbox Viewer:**
- Stay in admin panel
- See email in Inbox page
- Read directly without leaving admin
- Can cross-reference with contact form submissions

---

## Benefits

✅ **Unified interface** - Everything in one place  
✅ **Quick access** - No need to open email client  
✅ **Professional** - Client-facing admin panel  
✅ **Convenient** - See contact submissions AND their follow-up emails  
✅ **Secure** - Encrypted IMAP connection  
✅ **Read-only** - Safe viewing without accidental actions  

---

## Files Created

- `app/Filament/Pages/Inbox.php` - Inbox page controller
- `resources/views/filament/pages/inbox.blade.php` - Inbox UI
- `INBOX-SETUP-GUIDE.md` - This guide

## Files Modified

- `app/Filament/Pages/MailSettings.php` - Added IMAP configuration fields

---

**Now you have a complete inbox viewer in your admin panel!** 📥
