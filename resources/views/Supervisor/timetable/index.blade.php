@extends('layout.app')

@section('title','الجداول الدراسية')

@section('content')

    <h1 class="main-title">الجداول الدراسية</h1>

    {{-- شريط علوي: بحث بسيط + إضافة جدول --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <form class="d-flex search-input" method="GET" action="{{ route('timetable.index') }}">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="بحث عن جدول (صف / فصل / سنة)..."
                   value="{{ request('search') }}">
        </form>

        <a href="{{ route('timetable.create') }}" class="btn btn-primary-custom">
            <i class="bi bi-plus-lg ms-1"></i> إضافة جدول جديد
        </a>
    </div>

    {{-- رسائل نجاح --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card card-soft">
        <div class="card-body p-0">
            <table class="table mb-0 align-middle text-center">
                <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>الصف</th>
                    <th>الفصل</th>
                    <th>السنة الدراسية</th>
                    <th style="width: 200px;">إجراءات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($timetables as $tb)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $tb->grade->name ?? '-' }}</td>
                        <td>{{ $tb->section->name ?? '-' }}</td>
                        <td>{{ $tb->year }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">

                                {{-- عرض --}}
                                <a href="{{ route('timetable.show', $tb->id) }}"
                                   class="btn btn-link p-0 action-icon" title="عرض الجدول">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- تعديل --}}
                                <a href="{{ route('timetable.edit', $tb->id) }}"
                                   class="btn btn-link p-0 action-icon" title="تعديل">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                {{-- حذف --}}
                                <form action="{{ route('timetable.destroy', $tb->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الجدول؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-link p-0 action-icon"
                                            title="حذف">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4">لا توجد جداول دراسية مسجلة حالياً.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
