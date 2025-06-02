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
    <div class="flex items-center justify-center gap-1.5 text-lg">

        <a class=" w-6 aspect-square rounded-md flex items-center justify-center"
            href="{{route('employees.show', $coach->id)}}#edit-employee">
            <i class="ti ti-edit text-blue-500"></i>
        </a>

        <a class=" w-6 aspect-square rounded-md flex items-center justify-center"
            href="{{route('employees.show', $coach->id)}}#delete-employee">
            <i class=" ti ti-trash text-red-500"></i>
        </a>
    </div>
</x-table-row>