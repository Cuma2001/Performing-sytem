<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset your password</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #1e2f3f;">
    <div style="max-width: 600px; margin: 0 auto; padding: 24px;">
        <h2 style="color: #1d6988;">Reset your password</h2>
        <p>We received a request to reset your password. Click the link below to continue.</p>
        <p>
            <a href="{{ $resetLink }}" style="display: inline-block; padding: 12px 18px; background: #e5222b; color: white; text-decoration: none; border-radius: 999px;">
                Reset Password
            </a>
        </p>
        <p>If you did not request this, you can safely ignore this email.</p>
    </div>
</body>
</html>
