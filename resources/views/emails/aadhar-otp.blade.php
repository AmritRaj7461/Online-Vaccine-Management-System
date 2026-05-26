<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aadhaar e-KYC OTP Verification - UIDAI</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; margin: 0; padding: 0; background-color: #f6f8fa; }
        .wrapper { max-width: 600px; margin: 30px auto; background-color: #ffffff; border: 1px solid #e1e4e8; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .header { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 30px 24px; text-align: center; color: #ffffff; }
        .logo-emblem { font-size: 32px; font-weight: 800; background: #059669; color: white; display: inline-block; width: 48px; height: 48px; line-height: 48px; border-radius: 10px; margin-bottom: 12px; }
        .header h1 { font-size: 20px; font-weight: 750; margin: 0; tracking: 0.5px; }
        .header p { font-size: 12px; color: #94a3b8; margin: 6px 0 0; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .body { padding: 32px 30px; color: #334155; }
        .greeting { font-size: 16px; font-weight: 600; color: #0f172a; margin-top: 0; }
        .intro { font-size: 14px; line-height: 1.6; color: #475569; }
        .otp-container { background-color: #f1f5f9; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 24px; text-align: center; margin: 28px 0; }
        .otp-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .otp-code { font-family: 'Courier New', Courier, monospace; font-size: 36px; font-weight: 800; color: #0f172a; letter-spacing: 6px; margin: 0; }
        .warning-box { background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 16px; border-radius: 0 8px 8px 0; margin-bottom: 24px; }
        .warning-title { font-size: 13px; font-weight: 700; color: #991b1b; margin: 0 0 4px; }
        .warning-text { font-size: 12px; color: #7f1d1d; margin: 0; line-height: 1.5; }
        .info-bullets { font-size: 13px; color: #475569; line-height: 1.6; padding-left: 20px; margin-bottom: 28px; }
        .footer { background-color: #f8fafc; padding: 20px; border-top: 1px solid #f1f5f9; text-align: center; font-size: 11px; color: #94a3b8; }
        .footer p { margin: 4px 0; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div class="logo-emblem">印</div>
        <h1>Aadhaar e-KYC Verification Service</h1>
        <p>Unique Identification Authority of India (UIDAI)</p>
    </div>
    <div class="body">
        <p class="greeting">Dear {{ $userName }},</p>
        <p class="intro">
            A request was initiated for Aadhaar e-KYC verification in connection with your registration or profile update on the **VacciCare Online Vaccine Management System**.
        </p>
        <p class="intro">
            Please use the following One-Time Password (OTP) code to complete your verification. This code is valid for exactly **5 minutes**.
        </p>

        <div class="otp-container">
            <div class="otp-label">One-Time Password (OTP)</div>
            <div class="otp-code">{{ $otp }}</div>
        </div>

        <div class="warning-box">
            <h4 class="warning-title">⚠️ Security Warning / सुरक्षा चेतावनी</h4>
            <p class="warning-text">
                Do not share this OTP with anyone. UIDAI, government representatives, or VacciCare team members will never ask for your Aadhaar OTP via phone calls, email, or messages.
            </p>
        </div>

        <ul class="info-bullets">
            <li>This e-KYC request was generated on {{ now()->format('d M Y, h:i A') }} IST.</li>
            <li>If you did not initiate this request, please secure your profile immediately or contact support.</li>
        </ul>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} Unique Identification Authority of India. All rights reserved.</p>
        <p>Government of India (GoI) · Department of Electronics & Information Technology</p>
        <p style="margin-top:10px;font-size:10px;">This is an automated notification. Please do not reply directly to this message.</p>
    </div>
</div>
</body>
</html>
