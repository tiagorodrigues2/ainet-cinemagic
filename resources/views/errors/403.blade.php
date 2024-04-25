@extends('layouts.main')

@section('content')
    <div class="flex items-center justify-center h-screen">
        <x-toast type="error" :message="__('You are not authorized to access this page.')" />
    </div>
@endsection
