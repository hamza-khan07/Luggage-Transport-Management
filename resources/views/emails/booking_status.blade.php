<!DOCTYPE html>
<html>
<head>
    <title>Booking Status Update</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 5px;">
        <h2 style="color: #4A90E2;">Booking Status Update</h2>
        <p>Dear {{ $booking->user->name }},</p>
        
        <p>We are writing to inform you that the status of your booking (Tracking Number: <strong>{{ $booking->tracking_number }}</strong>) has been updated.</p>
        
        <p><strong>New Status:</strong> <span style="text-transform: capitalize; color: #d9534f;">{{ ucfirst(str_replace('_', ' ', $status)) }}</span></p>
        
        <p>You can view the details of your booking by logging into your account.</p>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #777;">
            <p>Thank you for choosing Luggage Transport Management.</p>
        </div>
    </div>
</body>
</html>
