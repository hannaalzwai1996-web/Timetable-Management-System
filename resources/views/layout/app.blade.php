<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>@yield('title','لوحة التحكم')</title>

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
        rel="stylesheet">

    <style>
        :root {
            --primary-color: #b04393;      /* البنفسجي للأيقونات والأزرار */
            --sidebar-bg: #e0e0e0;         /* خلفية السايدبار */
            --card-bg: #f2f2f2;            /* كروت رمادية فاتحة */
        }

        body {
            font-family: "Tajawal", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #ffffff;
        }

        .sidebar {
            background-color: var(--sidebar-bg);
            width: 260px;
        }

        .sidebar .profile-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #d0cfd4;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #6b6280;
        }

        .sidebar .nav-link {
            color: #333;
            font-size: 15px;
            padding: 10px 16px;
            border-radius: 999px;
            margin-bottom: 6px;
        }

        .sidebar .nav-link i {
            color: var(--primary-color);
            margin-left: 8px;
            font-size: 18px;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: #ffffff;
        }

        .main-title {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .card-soft {
            background-color: var(--card-bg);
            border: none;
            border-radius: 10px;
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary-custom:hover {
            background-color: #8e347b;
            border-color: #8e347b;
        }

        .table thead {
            background-color: #f8f8f8;
        }

        .table thead th {
            border-bottom-width: 0;
        }

        .action-icon {
            color: var(--primary-color);
            font-size: 18px;
        }

        .action-icon.btn-link {
            text-decoration: none;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-color);
        }

        .search-input {
            max-width: 260px;
        }

        /* استجابة للموبايل */
        @media (max-width: 768px) {
            .sidebar {
                width: 220px;
            }
            .main-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

{{-- نضبط الـ flex بحيث يكون السايدبار يمين والمحتوى يسار --}}
<div class="d-flex flex-row min-vh-100" style="direction: rtl;">

    {{-- Sidebar (يمين) --}}
    <aside class="sidebar d-flex flex-column justify-content-between p-3">
        <div>
            <div class="d-flex flex-column align-items-center mb-4 mt-2">
                <div class="profile-circle mb-2">
                    <i class="bi bi-person"></i>
                </div>
                <div class="fw-semibold">مشرف أكاديمي</div>
            </div>

            <nav class="nav flex-column">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                   href="#">
                    <i class="bi bi-house-door"></i> صفحة رئيسية
                </a>

                <a class="nav-link {{ request()->is('teachers*') ? 'active' : '' }}"
                   href="{{ route('teacher.index') }}">
                    <i class="bi bi-people"></i> معلمون
                </a>


                <a class="nav-link {{ request()->is('subject*') ? 'active' : '' }}"
   href="{{ route('subject.index') }}">
    <i class="bi bi-journal-bookmark"></i> مواد
</a>


                <a class="nav-link {{ request()->is('grade*') ? 'active' : '' }}"
                   href="{{ route('grade.index') }}">
                    <i class="bi bi-collection"></i> فصول
                </a>
                

                 <a class="nav-link {{ request()->is('section*') ? 'active' : '' }}"
                href="{{ route('timetable.index') }}">
                    <i class="bi bi-grid-3x3-gap"></i>  جدول الدراسي
                </a>
                <a class="nav-link {{ request()->is('section*') ? 'active' : '' }}"
                href="{{ route('section.index') }}">
                    <i class="bi bi-grid-3x3-gap"></i>  صفوف الدراسية
                </a>


                
            </nav>
        </div>

        <div class="mt-3">
            <a class="nav-link d-flex align-items-center" href="#">
                <i class="bi bi-gear"></i> الإعدادات
            </a>
            <a class="nav-link d-flex align-items-center" href="#">
                <i class="bi bi-box-arrow-left"></i> تسجيل الخروج
            </a>
        </div>
    </aside>

    {{-- Main content (يسار) --}}
    <main class="flex-grow-1 p-4">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
