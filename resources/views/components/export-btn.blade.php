@props(['url' => '#'])
@if (Auth::user()->role === 'manager')
<a href="{{$url}}"
    class=" leading-3 px-4 h-10 rounded-md bg-blue-200 text-blue-500 border border-transparent hover:border-blue-300 text-base font-medium flex items-center justify-center gap-1"><i
        class="ti ti-file-download"></i> Export</a>
@endif