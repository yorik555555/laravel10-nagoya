@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('/js/preview.js') }}"></script>
@endpush

@section('content')
    <div class="col container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-9">
                <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.restaurants.index') }}">店舗一覧</a></li>
                        <li class="breadcrumb-item active" aria-current="page">店舗登録</li>
                    </ol>
                </nav>

                <h1 class="mb-4 text-center">店舗登録</h1>

                <hr class="mb-4">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.restaurants.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row mb-3">
                        <label for="name" class="col-md-5 col-form-label text-md-left fw-bold">店舗名</label>

                        <div cla
