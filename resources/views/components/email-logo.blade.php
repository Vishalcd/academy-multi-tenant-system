@props(['data'])

<!-- Logo -->
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{route('home').'/'.$data['academy_logo']}}" alt="{{$data['academy_name']}} Logo" style="height: 60px;">
</div>