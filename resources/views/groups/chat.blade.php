@extends('layouts.app')

@section('content')
<div
    id="group-chat-root"
    data-group-id="{{ is_object($group) ? $group->id : $group }}"
    data-user-id="{{ auth()->id() }}"
    data-username="{{ auth()->user()->name }}"
></div>
@endsection 