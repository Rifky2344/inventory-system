@props([])

@php
    $slotContent = $slot;
@endphp

@include('layouts.app', ['slot' => $slotContent])
