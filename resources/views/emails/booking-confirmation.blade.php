<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation - VacciCare</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; margin: 0; padding: 0; background: 
        .wrapper { max-width: 600px; margin: 30px auto; background: 
        .header { background: linear-gradient(135deg, 
        .header h1 { color: 
        .header p { color: rgba(255,255,255,0.85); margin: 8px 0 0; font-size: 14px; }
        .body { padding: 32px 30px; }
        .greeting { font-size: 18px; font-weight: 600; color: 
        .sub { font-size: 14px; color: 
        .card { background: 
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid 
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 13px; color: 
        .detail-value { font-size: 13px; color: 
        .badge { display: inline-block; background: 
        .cta { text-align: center; margin: 24px 0; }
        .btn { display: inline-block; background: linear-gradient(135deg, 
        .footer { background: 
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
