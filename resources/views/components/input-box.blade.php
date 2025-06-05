@props(['lable' => null, 'name', 'icon' => 'user', 'type' => 'text', 'id',
'placeholder' => null,'value'=> null, 'row' => true, 'disabled' => null])

<div class="flex  {{$row === true ? 'items-center' : 'flex-col'}} gap-1.5 w-full">
    @if ($lable)
    <label class="font-medium text-sm flex items-center gap-1.5 min-w-40 text-slate-600" for="{{$id}}">
        <span class="text-lg"><i class="ti ti-{{$icon}}"></i></span>
        {{$lable}}
    </label>
    @endif

    <div class="w-full">
        <div class="relative">
            @if ($type === 'password')
            <button id="toggle-password" type="button"
                class="absolute top-1/2 right-2 -translate-y-1/2 w-6 rounded-sm aspect-square flex items-center justify-center"><i
                    class="ti ti-eye-closed pointer-events-none"></i></button>
            @endif
            <input
                class="@error($name) !border-red-300    
                    @enderror text-base font-medium placeholder:text-sm border px-2.5 h-10 w-full py-1.5  border-slate-200 rounded-md placeholder:text-slate-400 file:self-center file:bg-blue-200 file:px-2 file:h-full file:rounded-sm file:cursor-pointer file:font-semibold file:text-blue-500 file:mr-2 "
                type="{{$type}}" id="{{$id}}" name="{{$name}}" min="0" placeholder="{{$placeholder}}"
                value="{{old($name, $value)}}" {{$disabled}} />
        </div>
        @error($name)
        <p class="  mt-1 text-sm text-red-500">{{$message}}</p>
        @enderror
    </div>

</div>