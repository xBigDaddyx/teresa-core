@extends('accuracy.layouts.app')

@section('content')
<div class="max-w-screen px-4 py-2 sm:px-6 lg:px-8 lg:py-4 mx-auto ">

    @livewire('pages.validating-polybag', ['carton'=>$carton] )

</div>


@endsection
