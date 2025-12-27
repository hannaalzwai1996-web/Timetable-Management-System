@extends('Registrar.layout.app')

@section('title','Dashboard شؤون الطلبة')

@section('content')
<div class="main-title">داشبورد شؤون الطلبة</div>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card card-soft p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted">عدد الطلبة</div>
                    <div class="fs-3 fw-bold">{{ $studentsCount }}</div>
                </div>
                <i class="bi bi-people fs-1" style="color: var(--primary-color)"></i>
            </div>
        </div>
    </div>
</div>
@endsection
