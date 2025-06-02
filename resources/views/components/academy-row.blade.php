@props(['academy'])

<x-table-row grid='academy-grid'>
    <x-number-container>#{{$academy->id}}</x-number-container>
    <div class="flex items-center gap-2">
        <img class="w-12 object-cover aspect-square overflow-hidden" src="{{$academy->photo}}"
            onerror="this.onerror=null;this.src='/img/default_logo.png';" alt="{{$academy->name}}">
        <div class="flex flex-col gap-1">
            <p class="text-base font-bold tracking-tight leading-4 text-slate-800">
                {{$academy->name}}
            </p>
            <span class="leading-4 text-sm text-slate-600">{{$academy->email}}</span>
        </div>
    </div>
    <address class=" text-sm text-wrap leading-4 w-3/5">{{$academy->address}}</address>
    <img class=" w-8 aspect-square object-contain" src="{{$academy->favicon}}"
        onerror="this.onerror=null;this.src='/img/favicon_default.png';" alt="{{$academy->name}}">
    <x-number-container>{{$academy->created_at->format('d M, Y')}}</x-number-container>
    <x-button-link url="{{route('academies.show', $academy->id)}}" />
</x-table-row>