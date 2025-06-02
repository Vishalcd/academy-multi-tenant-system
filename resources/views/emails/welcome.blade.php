<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Welcome to {{$data['academy_name']}}</title>
</head>

<body style="background-color: #f9fafb; padding: 20px; font-family: sans-serif;">

    <x-email-logo :data="$data" />

    <!-- Email Container -->
    <div
        style="background-color: #ffffff; border-radius: 8px; padding: 24px; max-width: 600px; margin: auto; box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);">

        <!-- Header -->
        <h2 style="font-size: 24px; color: #1f2937; margin-bottom: 24px;">Welcome to {{$data['academy_name']}} Academy!
        </h2>

        <!-- User Info -->
        <p style="color: #374151; font-size: 16px;">Dear <strong>{{ $data['name'] }}</strong>,</p>

        <p style="color: #374151; font-size: 16px; margin-top: 8px;">
            Your account as a <strong>{{ ucfirst($data['role']) }}</strong> has been successfully created.
        </p>

        <p style="color: #374151; font-size: 16px; margin-top: 8px;">
            You can now log in using your email address: <strong>{{ $data['email'] }}</strong>
        </p>

        <p style="color: #ef4444; font-size: 16px; margin-top: 8px;">
            <strong>Default Password: password</strong><br>
            For your security, please change your password immediately after logging in.
        </p>

        <!-- Login Button -->
        <div style="text-align: center; margin: 24px 0;">
            <a href="{{ route('login') }}"
                style="background-color: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600;display: inline-block;">
                Login to Your Account
            </a>
        </div>

        <!-- Footer -->
        <x-email-footer :data="$data" />
    </div>

</body>

</html>