<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Message</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color:#0f172a;">
    <div style="max-width:600px;margin:0 auto;padding:24px;">
        <h2 style="margin:0 0 16px;font-size:20px;">New Contact Message</h2>
        <p style="margin:0 0 12px;">You received a new message from the contact form.</p>

        <table style="width:100%;border-collapse:collapse;margin-top:12px;">
            <tr>
                <td style="width:140px;color:#475569;padding:8px 0;">Name</td>
                <td style="padding:8px 0;font-weight:600;">{{ $name }}</td>
            </tr>
            <tr>
                <td style="width:140px;color:#475569;padding:8px 0;">Email</td>
                <td style="padding:8px 0;">{{ $email }}</td>
            </tr>
            <tr>
                <td style="width:140px;color:#475569;padding:8px 0;">Subject</td>
                <td style="padding:8px 0;">{{ $subject }}</td>
            </tr>
        </table>

        <div style="margin-top:16px;padding:12px;border:1px solid #e2e8f0;border-radius:8px;background:#f8fafc;">
            <p style="margin:0;white-space:pre-line;">{{ $body }}</p>
        </div>

        <p style="margin-top:24px;color:#64748b;font-size:12px;">Crafty Art Marketplace</p>
    </div>
</body>
</html>
