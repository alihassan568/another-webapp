<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation to Join AnotherGo</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
        }
        .content p {
            margin: 0 0 20px;
            color: #4b5563;
        }
        .invitation-box {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
        }
        .invitation-box p {
            margin: 8px 0;
            color: #1f2937;
        }
        .invitation-box strong {
            color: #667eea;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .button:hover {
            transform: translateY(-2px);
        }
        .footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 8px 0;
        }
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e5e7eb, transparent);
            margin: 30px 0;
        }
        .note {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            font-size: 14px;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 You're Invited!</h1>
        </div>
        
        <div class="content">
            <p>Hello,</p>
            
            <p>{{ $message }}</p>

            <div class="invitation-box">
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Role:</strong> {{ $role }}</p>
            </div>

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ $inviteLink }}" class="button">
                    Accept Invitation
                </a>
            </p>

            <div class="divider"></div>

            <div class="note">
                <strong>⚠️ Important:</strong> This invitation link is unique to you. Please do not share it with others.
            </div>

            <p style="font-size: 14px; color: #6b7280; margin-top: 30px;">
                If you didn't expect this invitation, you can safely ignore this email.
            </p>
        </div>

        <div class="footer">
            <p><strong>AnotherGo Team</strong></p>
            <p>Thank you for joining our platform!</p>
            <p style="margin-top: 20px; font-size: 12px;">
                If you're having trouble clicking the button, copy and paste this URL into your browser:<br>
                <span style="color: #667eea; word-break: break-all;">{{ $inviteLink }}</span>
            </p>
        </div>
    </div>
</body>
</html>
