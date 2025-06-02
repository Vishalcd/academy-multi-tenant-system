@props(['manager' ])

<x-table-row grid='manager-grid'>
    <x-number-container>#{{$manager->id}}</x-number-container>
    <x-user img="{{$manager->photo}}" alt_text="{{$manager->name}}" description_text="{{$manager->email}}">
        {{$manager->name}}
    </x-user>
    <x-make-call>{{$manager->phone}}</x-make-call>
    <address class=" text-sm text-wrap leading-4 w-3/5">{{$manager->address}}</address>
    <x-number-container>{{$manager->created_at->format('d M, Y')}}</x-number-container>
    {{--
    <x-button-link url="{{route('managers.show', $manager->id)}}" /> --}}
</x-table-row>