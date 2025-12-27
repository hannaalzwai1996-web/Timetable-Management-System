@extends('Registrar.layout.app')

@section('title','ترحيل طالب')

@section('content')
<div class="main-title">ترحيل الطالب: {{ $student->full_name }}</div>

@if(session('success'))
    <div class="alert alert-success card-soft p-3 mb-3">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger card-soft p-3 mb-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card card-soft p-4">
    <form action="{{ route('registrar.students.transfer.store', $student->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">الصف</label>
            <select name="grade_id" id="grade_id" class="form-control" required>
                <option value="">اختر الصف</option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}" {{ $student->grade_id == $grade->id ? 'selected' : '' }}>
                        {{ $grade->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">الشعبة</label>
            <select name="section_id" id="section_id" class="form-control" required>
                <option value="">اختر الشعبة</option>
                @foreach($sections as $section)
                    <option value="{{ $section->id }}" {{ $student->section_id == $section->id ? 'selected' : '' }}>
                        {{ $section->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary-custom">ترحيل</button>
            <a href="{{ route('registrar.students.index') }}" class="btn btn-secondary">رجوع</a>
        </div>
    </form>
</div>

<script>
document.getElementById('grade_id').addEventListener('change', function () {
    let gradeId = this.value;
    let sectionSelect = document.getElementById('section_id');

    sectionSelect.innerHTML = '<option value="">اختر الشعبة</option>';
    if (!gradeId) return;

    fetch(`/registrar/sections/by-grade/${gradeId}`)
        .then(r => r.json())
        .then(data => {
            data.forEach(section => {
                let option = document.createElement('option');
                option.value = section.id;
                option.textContent = section.name;
                sectionSelect.appendChild(option);
            });
        });
});
</script>
@endsection