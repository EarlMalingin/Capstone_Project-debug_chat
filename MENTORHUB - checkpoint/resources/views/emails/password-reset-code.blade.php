<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Code - MentorHub</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .code {
            background: #fff;
            border: 2px dashed #667eea;
            padding: 20px;
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin: 20px 0;
            border-radius: 8px;
            letter-spacing: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>MentorHub</h1>
        <p>Password Reset Request</p>
    </div>
    
    <div class="content">
        <h2>Hello!</h2>
        
        <p>We received a request to reset your password for your MentorHub account. To proceed with the password reset, please use the verification code below:</p>
        
        <div class="code">{{ $code }}</div>
        
        <p><strong>Important:</strong></p>
        <ul>
            <li>This code will expire in 15 minutes</li>
            <li>If you didn't request this password reset, please ignore this email</li>
            <li>For security reasons, never share this code with anyone</li>
        </ul>
        
        <p>If you're having trouble with the code, you can request a new one by visiting the forgot password page.</p>
        
        <p>Best regards,<br>The MentorHub Team</p>
    </div>
    
    <div class="footer">
        <p>This email was sent to {{ $email }}. If you didn't request a password reset, please ignore this email.</p>
        <p>&copy; 2025 MentorHub. All rights reserved.</p>
    </div>
</body>
</html> 