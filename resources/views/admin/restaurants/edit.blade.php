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
                        <li class="breadcrumb-item active"><a href="{{ route('admin.restaurants.show', $restaurant) }}">店舗詳細</a></li>
                        <li class="breadcrumb-item active" aria-current="page">店舗編集</li>
                    </ol>
                </nav>

                <h1 class="mb-4 text-center">店舗編集</h1>

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

                <form method="POST" action="{{ route('admin.restaurants.update', $restaurant) }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="form-group row mb-3">
                        <label for="name" class="col-md-5 col-form-label text-md-left fw-bold">店舗名</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $restaurant->name) }}">
                        </div>
                    </div>

                    <!-- 他のフォームグループも同様に編集 -->

                    <div class="form-group row mb-3">
                        <label class="col-md-5 col-form-label text-md-left fw-bold">定休日</label>
                        <div class="col-md-7">
                            @foreach ($regular_holidays as $index => $regular_holiday)
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="regularHoliday{{ $index }}" name="regular_holiday_id" value="{{ $regular_holiday->id }}"
                                    @if ($restaurant->regular_holidays()->where('regular_holiday_id', $regular_holiday->id)->exists())
                                        checked
                                    @endif
                                    >
                                    <label class="form-check-label" for="regularHoliday{{ $index }}">{{ $regular_holiday->day }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- 他のフォームグループも同様に編集 -->

                    <hr class="my-4">

                    <div class="form-group d-flex justify-content-center mb-4">
                        <button type="submit" class="btn text-white shadow-sm w-50 nagoyameshi-btn">更新</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
