@props(['data'])

<div style="margin-top: 32px; font-size: 14px; color: #6b7280;">
    <p style="margin: 0;">We’re excited to have you with us,</p>
    <p style="margin: 0;">{{$data['academy_name']}} Administration</p>
    <p style="margin-top: 8px;">Address: {{$data['academy_address']}} | Email: {{$data['academy_email']}}</p>
</div>