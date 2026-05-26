@props([])

@php
    $slotContent = $slot;
@endphp

@include('layouts.auth', ['slot' => $slotContent])
