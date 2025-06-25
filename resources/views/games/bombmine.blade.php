@extends('layouts.app')

@section('content')
<style>
.tooltip-exclam {
    position: relative;
    display: inline-block;
    cursor: pointer;
    margin-bottom: 20px;
}
.tooltip-exclam .tooltip-text {
    visibility: hidden;
    width: 240px;
    background-color: #ffeeba;
    color: #856404;
    text-align: left;
    border-radius: 6px;
    padding: 8px;
    position: absolute;
    z-index: 1;
    top: 120%;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 0.95em;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.tooltip-exclam:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}
</style>
<div id="bombmine-root"></div>
@endsection 