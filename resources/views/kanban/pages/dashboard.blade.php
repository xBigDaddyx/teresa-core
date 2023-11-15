@extends('kanban.layouts.dashboard')

@section('content')

@livewire('pages.kanban-dashboard',['company'=>$company])

@endsection
