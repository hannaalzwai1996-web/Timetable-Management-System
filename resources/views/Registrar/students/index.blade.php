@extends('Registrar.layout.app')

@section('title','قائمة الطلبة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="main-title mb-0">قائمة الطلبة</div>

    <a href="{{ route('registrar.students.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-plus-lg"></i> إضافة طالب
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success card-soft p-3 mb-3">
        {{ session('success') }}
    </div>
@endif

@if($students->count() == 0)
    <div class="alert alert-warning card-soft p-3">
        لا يوجد طلبة مضافين حتى الآن.
    </div>
@else
<div class="card card-soft p-3">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width:60px">#</th>
                    <th>اسم الطالب</th>
                    <th>البريد</th>
                    <th>الصف</th>
                    <th>الشعبة</th>
                    <th style="width:180px">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $student->full_name }}</td>

                        {{-- ✅ البريد من جدول users --}}
                        <td>{{ optional($student->user)->email ?? '-' }}</td>

                        <td>{{ $student->grade->name ?? '-' }}</td>
                        <td>{{ $student->section->name ?? '-' }}</td>

                        <td>
                            <div class="d-flex gap-2">

                                {{-- تعديل --}}
                                <a class="btn btn-sm btn-outline-secondary"
                                   href="{{ route('registrar.students.edit', $student->id) }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                {{-- ترحيل --}}
                                <a class="btn btn-sm btn-outline-primary"
                                   href="{{ route('registrar.students.transfer.form', $student->id) }}">
                                    <i class="bi bi-arrow-left-right"></i>
                                </a>

                                {{-- حذف --}}
                                <form action="{{ route('registrar.students.destroy', $student->id) }}" method="POST"
                                      onsubmit="return confirm('هل أنت متأكد من حذف الطالب؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection