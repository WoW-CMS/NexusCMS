<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Subscription Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4f46e5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #6b7280;
        }
        .button {
            display: inline-block;
            background-color: #4f46e5;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>¡Gracias por Suscribirte!</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $email }},</p>
        
        <p>Thank you for subscribing to our newsletter of {{ $siteName }}. From now on, you will receive updates about our latest news and content.</p>
        
        <p style="text-align: center;">
            <a href="{{ url('/confirm-subscription/'.$token) }}" class="button" style="color: white;">Confirm Subscription</a>
        </p>
        
        <p>If you did not subscribe to our newsletter, you can safely ignore this email.</p>
        
        <p>Best regards,<br>
        The {{ $siteName }} Team</p>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
        <p>This email was sent to {{ $email }}</p>
        <p>If you wish to unsubscribe, <a href="{{ url('/unsubscribe/'.$token) }}">click here</a>.</p>
    </div>
</body>
</html>