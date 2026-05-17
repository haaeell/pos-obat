<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Pos Kios Tani')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('logo.jpeg') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.jpeg') }}">

    <link href="https://fonts.bunny.net/css?family=Inter:300,400,500,600,700,800" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }

        table.dataTable thead th {
            background-color: #ecfdf5;
            color: #065f46;
            font-weight: 600;
        }

        .select2-container .select2-selection--single {
            height: 44px;
            border-radius: 0.5rem;
            border: 1.5px solid #d1fae5;
            padding: 8px 14px;
            display: flex;
            align-items: center;
        }

        .select2-selection__rendered {
            padding-left: 0 !important;
        }

        .select2-selection__arrow {
            height: 100%;
        }

        .select2-container--default .select2-selection--single:focus {
            border-color: #10b981;
            outline: none;
        }

        .menu-item {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .menu-item:hover {
            transform: translateX(4px);
        }

        .menu-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .modal-blur-overlay {
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }
    </style>

    @stack('styles')
</head>

<body class="antialiased">

    <div class="flex min-h-screen">

        <div id="overlay-mobile"
            class="fixed inset-0 bg-gray-900/60 z-30 opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden">
        </div>

        @php
            function menuActive($pattern)
            {
                return request()->is($pattern) ? 'menu-active' : 'text-gray-700 hover:bg-emerald-50';
            }
        @endphp

        <aside id="nav-sidebar" class="fixed lg:static inset-y-0 left-0 z-40 w-72
        bg-white/95 backdrop-blur-sm border-r border-emerald-100
        shadow-xl transform -translate-x-full lg:translate-x-0
        transition-transform duration-300 flex flex-col">

            <div class="h-20 flex items-center gap-3 px-6 border-b ">
                @php $toko = App\Models\PengaturanToko::instance(); @endphp

                <div class="w-12 h-12 rounded-xl overflow-hidden shadow-lg flex-shrink-0">
                    @if ($toko->logo)
                        <img src="{{ asset('storage/' . $toko->logo) }}" alt="Logo" class="w-full h-full object-cover">
                    @else
                        <div
                            class="w-full h-full bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center">
                            <i class="fa-solid fa-leaf text-white text-xl"></i>
                        </div>
                    @endif
                </div>

                <div>
                    <div class="font-bold text-lg text-gray-800">{{ $toko->nama_toko ?? 'Pos Kios Tani' }}</div>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">

                <a href="/home"
                    class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg font-medium {{ menuActive('home') }}">
                    <i class="fa-solid fa-house w-5"></i>
                    Dashboard
                </a>

                <div class="mt-8 mb-3 px-4 text-[11px] font-bold uppercase tracking-widest text-emerald-700">
                    Master Data
                </div>

                <a href="/produk"
                    class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg font-medium {{ menuActive('produk*') }}">
                    <i class="fa-solid fa-seedling w-5"></i>
                    Produk
                </a>

                <a href="/kategori"
                    class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg font-medium {{ menuActive('kategori*') }}">
                    <i class="fa-solid fa-list w-5"></i>
                    Kategori
                </a>

                <a href="/supplier"
                    class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg font-medium {{ menuActive('supplier*') }}">
                    <i class="fa-solid fa-truck-field w-5"></i>
                    Supplier
                </a>

                <div class="mt-8 mb-3 px-4 text-[11px] font-bold uppercase tracking-widest text-emerald-700">
                    Inventory
                </div>

                <a href="/stock-in"
                    class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg font-medium {{ menuActive('stock-in*') }}">
                    <i class="fa-solid fa-clipboard-list w-5"></i>
                    Barang Masuk
                </a>

                <div class="mt-8 mb-3 px-4 text-[11px] font-bold uppercase tracking-widest text-emerald-700">
                    Transaksi
                </div>

                <a href="/transactions/pos"
                    class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg font-medium {{ menuActive('sales') }}">
                    <i class="fa-solid fa-cart-shopping w-5"></i>
                    Tambah Transaksi
                </a>

                <a href="/transactions"
                    class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg font-medium {{ menuActive('sales') }}">
                    <i class="fa-solid fa-history w-5"></i>
                    Riwayat Transaksi
                </a>

                <div class="mt-8 mb-3 px-4 text-[11px] font-bold uppercase tracking-widest text-emerald-700">
                    Keuangan
                </div>

                <a href="/modals"
                    class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg font-medium {{ menuActive('modals*') }}">
                    <i class="fa-solid fa-hand-holding-dollar w-5"></i>
                    Modal & Hutang Toko
                </a>

                <a href="/piutang"
                    class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg font-medium {{ menuActive('piutang*') }}">
                    <i class="fa-solid fa-money-bill-transfer w-5"></i>
                    Kelola Piutang
                </a>

                <div class="mt-8 mb-3 px-4 text-[11px] font-bold uppercase tracking-widest text-emerald-700">
                    Laporan
                </div>

                <a href="/laporan"
                    class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg font-medium {{ menuActive('laporan*') }}">
                    <i class="fa-solid fa-chart-bar w-5"></i>
                    Laporan
                </a>

                <div class="mt-8 mb-3 px-4 text-[11px] font-bold uppercase tracking-widest text-emerald-700">
                    Pengaturan
                </div>

                <a href="/settings"
                    class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg font-medium {{ menuActive('settings*') }}">
                    <i class="fa-solid fa-sliders w-5"></i>
                    Konfigurasi Toko
                </a>
                {{--
                <a href="/users"
                    class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg font-medium {{ menuActive('users*') }}">
                    <i class="fa-solid fa-users-gear w-5"></i>
                    User & Preferensi
                </a> --}}

            </nav>

            <div class="p-4 border-t border-emerald-100 bg-gradient-to-r from-emerald-50 to-green-50">
                <div class="text-xs text-center text-emerald-700 font-medium">
                    © 2025 Pos Kios Tani
                </div>
            </div>

        </aside>

        <div class="flex-1 flex flex-col min-h-screen">

            <header
                class="h-20 bg-white/90 backdrop-blur-md border-b border-emerald-100 shadow-sm flex items-center justify-between px-6 sticky top-0 z-20">

                <button id="toggleMenu"
                    class="p-2.5 rounded-lg hover:bg-emerald-100 text-emerald-700 lg:hidden transition">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>

                <div class="hidden lg:block">
                    <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                </div>

                <div class="relative ml-auto" id="userDropdown">
                    <button type="button"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-emerald-50 transition focus:outline-none"
                        id="userToggle">

                        <div class="text-right hidden sm:block">
                            <div class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-emerald-600">Administrator</div>
                        </div>

                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center text-white font-bold shadow-md">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </button>

                    <div id="userMenu" class="absolute right-0 mt-3 w-52 bg-white border border-emerald-100 rounded-xl shadow-2xl
                               opacity-0 invisible transition-all z-50 overflow-hidden">

                        {{-- <a href="#"
                            class="flex items-center gap-3 px-5 py-3 text-sm text-gray-700 hover:bg-emerald-50 transition">
                            <i class="fa-solid fa-user-circle text-emerald-600"></i>
                            Profil Saya
                        </a> --}}

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-3 px-5 py-3 text-sm
                                       text-red-600 hover:bg-red-50 transition border-t border-emerald-100">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>

            </header>

            <main class="p-6 flex-1">
                <div class="bg-white/80 rounded-2xl shadow-lg border border-emerald-100 p-8">
                    @yield('content')
                </div>
            </main>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function () {
            const navSidebar = $('#nav-sidebar')
            const overlayMobile = $('#overlay-mobile')

            $('#toggleMenu').on('click', function () {
                navSidebar.toggleClass('-translate-x-full')
                overlayMobile.toggleClass('opacity-0 pointer-events-none')
            })

            overlayMobile.on('click', function () {
                navSidebar.addClass('-translate-x-full')
                overlayMobile.addClass('opacity-0 pointer-events-none')
            })

            $('.datatable').DataTable({
                responsive: true,
                pageLength: 10,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            })

            $('#userToggle').on('click', function (e) {
                e.stopPropagation()
                $('#userMenu').toggleClass('opacity-0 invisible')
            })

            $(document).on('click', function () {
                $('#userMenu').addClass('opacity-0 invisible')
            })
        })
    </script>

    @stack('scripts')

    @if ($errors->any())
        <script>
            let errorMessages = '';
            @foreach ($errors->all() as $error)
                errorMessages += "{{ $error }}\n";
            @endforeach

            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: errorMessages,
                confirmButtonColor: '#10b981'
            });
        </script>
    @endif

    @if (session('success') || session('error'))
        <script>
            $(document).ready(function () {
                var successMessage = "{{ session('success') }}";
                var errorMessage = "{{ session('error') }}";

                if (successMessage) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: successMessage,
                        confirmButtonColor: '#10b981'
                    });
                }

                if (errorMessage) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: errorMessage,
                        confirmButtonColor: '#10b981'
                    });
                }
            });
        </script>
    @endif

</body>

</html>