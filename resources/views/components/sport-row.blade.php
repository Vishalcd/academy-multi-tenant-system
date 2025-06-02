@props(['sport'])

<x-table-row grid='sport-grid'>
    <x-number-container>#{{$sport->id}}</x-number-container>
    <div class="flex items-center gap-2">
        <img class="w-12 object-cover aspect-square rounded-md overflow-hidden" src="{{$sport->photo}}"
            onerror="this.onerror=null;this.src='/img/sports_default.jpg';" alt="{{$sport->sport_title}}">
        <p class=" font-semibold ">{{$sport->sport_title}}</p>
    </div>
    <div class="flex items-center gap-2">
        <img class=" w-8 object-contain aspect-square" src="{{$sport->academy->favicon}}"
            onerror="this.onerror=null;this.src='/img/favicon_default.png';" alt="{{$sport->academy->name}}">
        <span class=" font-bold tracking-tight text-sm text-salte-600">{{$sport->academy->name}}</span>
    </div>
    <x-number-container>{{formatCurrency($sport->sport_fees)}}</x-number-container>
    <x-number-container>{{$sport->created_at->format('d M, Y')}}</x-number-container>
    <x-button-link url="{{route('sports.show', $sport->id)}}" />
</x-table-row>