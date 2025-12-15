@extends('layout.app')

@section('title','عرض الجدول الدراسي')

@section('content')

<h1 class="main-title">الجدول الدراسي</h1>

{{-- معلومات الجدول --}}
<div class="card card-soft mb-3">
    <div class="card-body">
        <strong>الصف:</strong> {{ $timetable->grade->name }} |
        <strong>الفصل:</strong> {{ $timetable->section->name }} |
        <strong>السنة الدراسية:</strong> {{ $timetable->year }}
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <strong>خطأ:</strong> {{ $errors->first() }}
    </div>
@endif

@php
    $days = ['sat'=>'السبت','sun'=>'الأحد','mon'=>'الإثنين','tue'=>'الثلاثاء','wed'=>'الأربعاء','thu'=>'الخميس'];
@endphp

{{-- جدول شبكي كامل --}}
<div class="card card-soft">
    <div class="card-body">

        <table class="table table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th>اليوم</th>
                    @foreach($timeslots->groupBy('day')->first() as $slot)
                        <th>
                            {{ substr($slot->start_time,0,5) }} - {{ substr($slot->end_time,0,5) }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
            @foreach($days as $dayKey => $dayName)
                <tr>
                    <td><strong>{{ $dayName }}</strong></td>

                    @php 
                        $daySlots = $timeslots->where('day', $dayKey);
                    @endphp

                    @foreach($daySlots as $slot)

                        @php
                            $session = $sessions->firstWhere('timeslot_id', $slot->id);
                        @endphp

                        <td style="min-height: 80px">

                            @if($session)
                                {{-- عرض الحصة --}}
                                <div class="p-2 text-start">

                                    <strong>{{ $session->subject->name }}</strong><br>
                                    <span class="text-muted">{{ $session->teacher->full_name }}</span><br>
                                    <span class="badge bg-secondary">{{ $session->section->name }}</span>

                                    {{-- أدوات تعديل وحذف --}}
                                    <div class="mt-2 d-flex gap-2">

                                        {{-- تعديل --}}
                                        <a href="{{ route('timetable.sessions.edit', [$timetable->id, $session->id]) }}"
                                           class="action-icon">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        {{-- حذف --}}
                                        <form method="POST"
                                              action="{{ route('timetable.sessions.destroy', [$timetable->id, $session->id]) }}"
                                              onsubmit="return confirm('هل تريد حذف هذه الحصة؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-link p-0 action-icon">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            @else
                                {{-- زر إضافة حصة داخل الخانة --}}
                                <a href="{{ route('timetable.sessions.create', $timetable->id) }}?slot={{ $slot->id }}"
                                   class="btn btn-sm btn-primary-custom">
                                    إضافة
                                </a>
                            @endif

                        </td>

                    @endforeach
                </tr>
            @endforeach
            </tbody>

        </table>

    </div>
</div>

@endsection
