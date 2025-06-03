@props(['coach' ])

<x-table-row grid='coach-grid'>
    <x-number-container>#{{$coach->id}}</x-number-container>
    <x-user img="{{$coach->user->photo}}" alt_text="{{$coach->user->name}}" description_text="{{$coach->user->email}}">
        {{$coach->user->name}}
    </x-user>
    <x-make-call>{{$coach->user->phone}}</x-make-call>
    <span class=" font-semibold text-sm text-green-800">{{$coach->sport->sport_title}}</span>
    <address class=" text-sm text-wrap leading-4 w-3/5">{{$coach->user->address}}</address>
    <x-number-container>{{$coach->user->created_at->format('d M, Y')}}</x-number-container>

    <x-button-link url="{{route('employees.show', $coach->id)}}" />
</x-table-row>