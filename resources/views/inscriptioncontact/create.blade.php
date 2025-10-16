@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg p-4 rounded-4">
        <h2 class="text-center mb-4 text-primary fw-bold">
            ONFP - PARTNERSHIP ENGAGEMENT DAY
        </h2>
        <h5 class="text-center text-secondary mb-4">
            Confirmez votre participation
        </h5>

        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mx-auto" style="max-width: 600px;">
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection
