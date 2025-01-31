@extends('layouts.main')
@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2 class="display-4 text-primary fw-bold ">مكتبتي</h2>
            <p class="text-muted mt-6">مجموعة الكتب الخاصة بك</p>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse($myBooks as $book)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset($book->cover_image) }}" class="card-img-top" alt="{{ $book->title }}"
                    style="height: 300px; object-fit: cover;">

                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $book->title }}</h5>
                    <p class="card-text text-muted mb-2">ISBN: {{ $book->isbn }}</p>
                    <p class="card-text" style="height: 100px; overflow: hidden;">
                        {{ Str::limit($book->description, 150) }}
                    </p>
                </div>

                <div class="card-footer bg-white border-top-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary">{{ $book->pivot->number_of_copies }} نسخة</span>
                        <span class="text-success fw-bold">{{ number_format($book->price, 2) }} $</span>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted"> تاريخ الشراء: {{
                            \Carbon\Carbon::parse($book->pivot->created_at)->format('M d, Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                <h4 class="alert-heading mb-3">لا توجد كتب</h4>
                <p class="mb-0">لم تقم بشراء أي كتب حتى الآن</p>
            </div>
        </div>
        @endforelse
    </div>

    <div class="row mt-4">
        <div class="col">
            <a href="{{ route('gallery.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> تصفح المزيد من الكتب

            </a>
        </div>
    </div>
</div>
@endsection