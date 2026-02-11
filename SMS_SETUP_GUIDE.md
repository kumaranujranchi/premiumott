# Payment Automation Setup Guide

To automate your payment verification, you need to set up an SMS Forwarding app on your phone.

## Step 1: Install App

1. On your Android phone, go to Play Store.
2. Search and install **"SMS Forwarder"** (or any similar app like "SMS to Webhook").
   - Recommended: _SMS Forwarder by Yasmani_ or _SMS Forwarder - Auto Forward_.

## Step 2: Configure Webhook

1. Open the App.
2. Create a new **Forwarding Rule** (or Filter).
3. **Sender/Filter**:
   - Allow SMS from your Bank (e.g., `HDFCBK`, `SBIN`, `PAYTM`, `GPAY`).
   - Or just select "All SMS" for testing (careful with privacy).
4. **Destination Type**: Select **Web URL** or **HTTP Post**.
5. **URL**: Enter your website webhook URL:
   `https://yourwebsite.com/webhook.php?secret=MY_SECURE_KEY`
   _(Replace `yourwebsite.com` with your actual domain)_
   _(Replace `MY_SECURE_KEY` with the secret key in `webhook.php`)_
6. **HTTP Method**: Choose **POST** or **GET**.
7. **Save**.

## Step 3: Test

1. Send a dummy SMS to your phone: "Credited Rs 100 Ref 1234567890".
2. Check if the app forwards it.
3. Check `webhook_log.txt` on your server (if enabled) to see if it reached.

## Step 4: Go Live

- Ensure your phone always has internet.
- Ensure the app is excluded from "Battery Optimization" so it doesn't get killed.
