@extends('Registrar.layout.app')

@section('title','تعديل طالب')

@section('content')
<div class="main-title">تعديل بيانات الطالب</div>

<form action="{{ route('registrar.students.update', $student->id) }}" method="POST" class="card card-soft p-4">
    @csrf
    @method('PUT')

    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label">اسم المستخدم</label>
            <input type="text" name="name"
                   class="form-control"
                   value="{{ old('name', $student->user->name ?? '') }}"
                   required>
        </div>

        <div class="col-md-6">
            <label class="form-label">البريد الإلكتروني</label>
            <input type="email" name="email"
                   class="form-control"
                   value="{{ old('email', $student->user->email ?? '') }}"
                   required>
        </div>

        <div class="col-md-6">
            <label class="form-label">الاسم الكامل للطالب</label>
            <input type="text" name="full_name"
                   class="form-control"
                   value="{{ old('full_name', $student->full_name) }}"
                   required>
        </div>

        <div class="col-md-6">
            <label class="form-label">الفصل</label>
            <select name="grade_id" id="grade_id" class="form-control" required>
                <option value="">اختر الفصل</option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}"
                        {{ (string)old('grade_id', $student->grade_id) === (string)$grade->id ? 'selected' : '' }}>
                        {{ $grade->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">الشعبة</label>
            <select name="section_id" id="section_id" class="form-control" required>
                <option value="">اختر الشعبة</option>
                {{-- سيتم تعبئتها بالجافاسكربت حسب الفصل --}}
            </select>
        </div>

    </div>

    <div class="mt-4 d-flex gap-2">
        <button class="btn btn-primary-custom">💾 تحديث</button>
        <a href="{{ route('registrar.students.index') }}" class="btn btn-secondary">رجوع</a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const gradeSelect = document.getElementById('grade_id');
    const sectionSelect = document.getElementById('section_id');

    async function loadSections(gradeId, selectedSectionId = null) {
        sectionSelect.innerHTML = `<option value="">جاري التحميل...</option>`;

        if (!gradeId) {
            sectionSelect.innerHTML = `<option value="">اختر الشعبة</option>`;
            return;
        }

        const res = await fetch(`/registrar/sections/by-grade/${gradeId}`);
        const data = await res.json();

        sectionSelect.innerHTML = `<option value="">اختر الشعبة</option>`;
        data.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.id;
            opt.textContent = s.name;

            if (selectedSectionId && String(selectedSectionId) === String(s.id)) {
                opt.selected = true;
            }

            sectionSelect.appendChild(opt);
        });
    }

    // عند تغيير الفصل
    gradeSelect.addEventListener('change', function () {
        loadSections(this.value);
    });

    // تحميل أول مرة (الفصل الحالي + الشعبة الحالية)
    const initialGrade = "{{ old('grade_id', $student->grade_id) }}";
    const initialSection = "{{ old('section_id', $student->section_id) }}";
    if (initialGrade) {
        loadSections(initialGrade, initialSection);
    }
});
</script>
@endsection