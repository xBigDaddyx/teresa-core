<div>
    <x-filament::fieldset wire:poll="deleteAction">
        <x-slot name="label">
            <x-tabler-message-2 />
        </x-slot>
        @foreach ($request->comments as $comment)
        <div class="flex p-2" wire.key="{{$comment->id}}">
            <x-filament::section aside class="p-2">
                <x-slot name="heading">
                    {{$comment->user->name}}
                </x-slot>

                <x-slot name="description">
                    {{\Carbon\Carbon::parse($comment->created_at)->format('d M Y H:i:s')}}
                </x-slot>
                <x-slot name="headerEnd">
                    <x-filament::avatar src="{{$comment->user->getFilamentAvatarUrl() }}" />

                </x-slot>
                {{$comment->body}}

            </x-filament::section>
            <div class="ms-auto flex items-center gap-x-3">
                {{($this->deleteAction)(['comment' => $comment->id])}}

            </div>


        </div>

        @endforeach


    </x-filament::fieldset>
    <form wire:submit="create" class="py-4">
        {{ $this->form }}
        <div class="py-4">
            <x-filament::button type="submit" size="sm" icon="tabler-plus" tooltip="Add a comment">
                Submit
            </x-filament::button>
        </div>

    </form>
</div>
