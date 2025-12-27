@extends('Registrar.layout.app')

@section('title','إضافة طالب جديد')

@section('content')
<div class="main-title">إضافة طالب جديد</div>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('registrar.students.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label class="form-label">اسم الطالب</label>
        <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">البريد الإلكتروني</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">كلمة المرور</label>
        <input type="password" name="password" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">الصف</label>
        <select name="grade_id" id="grade_id" class="form-control">
    <option value="">اختر الصف</option>
    @foreach($grades as $grade)
        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
    @endforeach
</select>

    </div>

    <div class="mb-3">
        <label class="form-label">الشعبة</label>
        <select name="section_id" id="section_id" class="form-control" disabled>
       <option value="">اختر الشعبة</option>
     </select>
    </div>

    <div class="mb-3">
        <label class="form-label">تاريخ الميلاد</label>
        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
    </div>

    <button type="submit" class="btn btn-primary-custom">حفظ</button>
    <a href="{{ route('registrar.students.index') }}" class="btn btn-secondary">رجوع</a>
</form>

{{-- ✅ سكربت تعبئة الشعبة حسب الصف + يمنع التكرار --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const gradeSelect = document.getElementById('grade_id');
    const sectionSelect = document.getElementById('section_id');

    function resetSections() {
        sectionSelect.innerHTML = '<option value="">اختر الشعبة</option>';
        sectionSelect.disabled = true;
    }

    resetSections();

    gradeSelect.addEventListener('change', function () {
        const gradeId = this.value;

        // يمسح القديم ويمنع التكرار
        resetSections();

        if (!gradeId) return;

        fetch(`/registrar/sections/by-grade/${gradeId}`)
            .then(res => res.json())
            .then(data => {
                // لو رجع فاضي
                if (!data || data.length === 0) {
                    sectionSelect.innerHTML = '<option value="">لا توجد شعب لهذا الصف</option>';
                    sectionSelect.disabled = true;
                    return;
                }

                // يعبي الخيارات
                data.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.name;
                    sectionSelect.appendChild(option);
                });

                sectionSelect.disabled = false;
            })
            .catch(() => {
                sectionSelect.innerHTML = '<option value="">حدث خطأ أثناء جلب الشعب</option>';
                sectionSelect.disabled = true;
            });
    });
});
</script>
@endsection