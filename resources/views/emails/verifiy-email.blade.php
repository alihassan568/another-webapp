<!DOCTYPE html>
<html>
<head>
    <title>AnotherGo Email Verification</title>
</head>
<body>
    <p>Dear User,</p>

    <p>Thank you for registering with us!</p>

    <p>Please verify your email address by clicking the link below:</p>

    <p>
        <a href="{{ $link }}" style="display: inline-block; padding: 10px 20px; background-color: #4f46e5; color: white; text-decoration: none; border-radius: 4px;">
            Verify Email
        </a>
    </p>

    <p>This link is valid for 60 minutes.</p>

    <p>If you didn’t create an account, no further action is required.</p>

    <p>Thanks,<br>AnotherGo Team</p>
</body>
</html>
