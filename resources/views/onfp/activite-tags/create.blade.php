@extends('layout.user-layout')

@section('title', 'Nouveau tag')

@section('space-work')
    <div class="container-fluid py-4">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('onfp.activite-tags.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h4 class="mb-0">Nouveau tag</h4>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('onfp.activite-tags.store') }}">
                    @include('onfp.activite-tags._form')
                </form>
            </div>
        </div>

    </div>
@endsection
