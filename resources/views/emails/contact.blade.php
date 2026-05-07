<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
        }

        .header {
            background: #1a1a1a;
            padding: 24px;
            text-align: center;
        }

        .header h1 {
            color: #f59e0b;
            font-size: 1.4rem;
            margin: 0;
        }

        .body {
            padding: 32px;
        }

        .field {
            margin-bottom: 20px;
        }

        .label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .value {
            font-size: 1rem;
            color: #2a2a2a;
        }

        .message-box {
            background: #f9fafb;
            border-radius: 8px;
            padding: 16px;
            border-left: 4px solid #f59e0b;
        }

        .footer {
            background: #f9fafb;
            padding: 16px;
            text-align: center;
            font-size: 0.8rem;
            color: #9ca3af;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h3>New Message From WE</h3>
        </div>
        <div class="body">
            <div class="field">
                <p class="label">Name</p>
                <p class="value">{{ $senderName }}</p>
            </div>
            <div class="field">
                <p class="label">Email</p>
                <p class="value">{{ $senderEmail }}</p>
            </div>
            <div class="field">
                <p class="label">Subject</p>
                <p class="value">{{ $mailSubject }}</p>
            </div>
            <div class="field">
                <p class="label">Message</p>
                <div class="message-box">
                    <p class="value">{{ $messageText }}</p>
                </div>
            </div>
        </div>
        <div class="footer">
            This message was sent from the contact form on your website.
        </div>
    </div>
</body>

</html>
