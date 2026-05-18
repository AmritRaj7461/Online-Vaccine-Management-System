<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation - VacciCare</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; margin: 0; padding: 0; background: #f1f5f9; }
        .wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #38bdf8, #2563eb); padding: 40px 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; font-weight: 700; }
        .header p { color: rgba(255,255,255,0.85); margin: 8px 0 0; font-size: 14px; }
        .body { padding: 32px 30px; }
        .greeting { font-size: 18px; font-weight: 600; color: #1e293b; margin-bottom: 8px; }
        .sub { font-size: 14px; color: #64748b; margin-bottom: 24px; }
        .card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 24px; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 13px; color: #94a3b8; font-weight: 500; }
        .detail-value { font-size: 13px; color: #1e293b; font-weight: 600; }
        .badge { display: inline-block; background: #fef3c7; color: #d97706; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .cta { text-align: center; margin: 24px 0; }
        .btn { display: inline-block; background: linear-gradient(135deg, #38bdf8, #2563eb); color: #fff; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-weight: 600; font-size: 14px; }
        .footer { background: #f8fafc; padding: 20px 30px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div style="font-size:36px;margin-bottom:8px;">💉</div>
        <h1>Appointment Confirmed!</h1>
        <p>VacciCare - Online Vaccine Management System</p>
    </div>
    <div class="body">
        <p class="greeting">Hello, {{ $appointment->user->name }}!</p>
        <p class="sub">Your vaccination appointment has been successfully booked. Here are your details:</p>

        <div class="card">
            <div class="detail-row">
                <span class="detail-label">Vaccine</span>
                <span class="detail-value">{{ $appointment->vaccine->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Manufacturer</span>
                <span class="detail-value">{{ $appointment->vaccine->manufacturer }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Center</span>
                <span class="detail-value">{{ $appointment->center->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Location</span>
                <span class="detail-value">{{ $appointment->center->address }}, {{ $appointment->center->city }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-value">{{ $appointment->appointment_date->format('l, d F Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Dose</span>
                <span class="detail-value">Dose {{ $appointment->dose_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value"><span class="badge">{{ ucfirst($appointment->status) }}</span></span>
            </div>
        </div>

        <p style="font-size:13px;color:#64748b;">Please carry a valid ID proof and arrive 10 minutes early at the center.</p>

        <div class="cta">
            <a href="{{ url('/') }}" class="btn">View My Appointments</a>
        </div>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} VacciCare. All rights reserved.</p>
        <p>This is an automated email. Please do not reply.</p>
    </div>
</div>
</body>
</html>
