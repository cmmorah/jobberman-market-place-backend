<!DOCTYPE html>
<html>
<head>
    <title>Laravel 9 Send Email Example</title>
</head>
<body>

<span style="font-size:18px;">Please use the 4 digit code to complete your action on DigiPos.<br>
    This OTP is only valid for 5 minutes.<br>
    Do not disclose your OTP to anyone or staff.<br><br>
    <b>OTP:</b><br><br>
    <span style="background-color: #ffffff; padding: 20px; letter-spacing: 10px; border-radius: 20px;"><b> {{$mailData['otp']}} </b></span>
</span>

</body>
</html>
