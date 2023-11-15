<div>

    @if (!empty($getState()) && count($getState()) > 0)
    @foreach ($getState() as $record)
    <x-filament::badge icon="tabler-settings-search" color="info">
        @if(array_key_exists('category',$record))
        {{$record['category'] .' : '.$record['value']}}
        @else
        -
        @endif


    </x-filament::badge>
    @endforeach
    @else
    -
    @endif

</div>
