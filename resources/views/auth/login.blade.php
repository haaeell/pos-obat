<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Login - Pos Kios Tani</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.bunny.net/css?family=Inter:300,400,500,600,700,800" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 50%, #a7f3d0 100%);
            background-image:
                radial-gradient(circle at 20% 50%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(5, 150, 105, 0.1) 0%, transparent 50%),
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2310b981' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .leaf-float {
            animation: leafFloat 20s ease-in-out infinite;
        }

        @keyframes leafFloat {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <div
        class="w-full max-w-5xl bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-emerald-200 overflow-hidden flex flex-col lg:flex-row min-h-[600px]">

        <div
            class="w-full lg:w-[45%] bg-gradient-to-br from-emerald-600 via-green-600 to-teal-700 p-12 flex flex-col justify-center items-center text-center text-white relative overflow-hidden">

            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <div class="absolute top-10 left-10 w-32 h-32 bg-white/20 rounded-full blur-3xl"></div>
                <div class="absolute bottom-10 right-10 w-40 h-40 bg-white/20 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10">
                <div
                    class="bg-white/25 p-8 rounded-3xl backdrop-blur-md mb-6 ring-2 ring-white/40 shadow-2xl leaf-float inline-block">
                    <i class="fa-solid fa-leaf text-7xl text-white drop-shadow-lg"></i>
                </div>

                <h1 class="text-4xl font-extrabold tracking-tight mb-3 drop-shadow-md">
                    Pos Kios Tani
                </h1>

                <div class="w-20 h-1 bg-white/60 mx-auto rounded-full mb-4"></div>

                <p class="text-emerald-50 text-sm max-w-[300px] mx-auto leading-relaxed font-medium">
                    @php
                        $description = App\Models\Setting::where('key', 'deskripsi')->first();
                    @endphp
                    {{ $description->value ?? 'Sistem Manajemen Agribisnis Terpadu untuk Kemudahan Pengelolaan Usaha Tani Anda' }}
                </p>

                <div class="mt-14 flex flex-wrap justify-center gap-3 text-[10px] uppercase font-bold tracking-widest">
                    <div class="bg-white/15 px-5 py-2.5 rounded-full border-2 border-white/30 backdrop-blur-sm">
                        <i class="fa-solid fa-shield-halved mr-1.5"></i>Terpercaya
                    </div>
                    <div class="bg-white/15 px-5 py-2.5 rounded-full border-2 border-white/30 backdrop-blur-sm">
                        <i class="fa-solid fa-bolt mr-1.5"></i>Efisien
                    </div>
                    <div class="bg-white/15 px-5 py-2.5 rounded-full border-2 border-white/30 backdrop-blur-sm">
                        <i class="fa-solid fa-chart-line mr-1.5"></i>Akurat
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-[55%] p-8 lg:p-14 flex flex-col justify-center">
            <div class="mb-10">
                <h2 class="text-3xl font-extrabold gradient-text mb-2">Selamat Datang</h2>
                <p class="text-gray-600 text-sm font-medium">Masuk menggunakan akun terdaftar untuk mengakses sistem</p>
            </div>

            @if ($errors->any() || session('error'))
                <div class="mb-7 overflow-hidden rounded-2xl border-2 border-red-200 bg-gradient-to-r from-red-50 to-rose-50 shadow-sm flex"
                    role="alert">
                    <div class="w-2 bg-gradient-to-b from-red-500 to-rose-600"></div>

                    <div class="p-5 flex flex-grow items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
                            </div>
                        </div>

                        <div class="ml-4 flex-grow">
                            <h3 class="text-sm font-bold text-red-900 uppercase tracking-wide">
                                Gagal Login
                            </h3>

                            <div class="mt-2 text-sm text-red-700 leading-relaxed">
                                @if ($errors->any())
                                    <ul class="list-disc list-inside space-y-1.5">
                                        @foreach ($errors->all() as $error)
                                            <li class="font-medium">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="font-medium">{{ session('error') }}</p>
                                @endif
                            </div>
                        </div>

                        <button type="button" onclick="this.parentElement.parentElement.remove()"
                            class="ml-auto text-red-400 hover:text-red-600 transition-colors p-1">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="text-xs font-extrabold uppercase tracking-wider text-emerald-800 ml-1 mb-2 block">
                        <i class="fa-regular fa-envelope mr-1"></i>Alamat Email
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-5 flex items-center text-emerald-500">
                            <i class="fa-regular fa-user text-lg"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full pl-14 pr-5 py-4 rounded-xl border-2 border-emerald-200 focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 transition-all outline-none bg-white/80 font-medium @error('email') border-red-400 @enderror"
                            placeholder="masukkan@email.com">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-extrabold uppercase tracking-wider text-emerald-800 ml-1 mb-2 block">
                        <i class="fa-solid fa-lock mr-1"></i>Kata Sandi
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-5 flex items-center text-emerald-500">
                            <i class="fa-solid fa-key text-lg"></i>
                        </span>
                        <input type="password" id="password" name="password" required
                            class="w-full pl-14 pr-14 py-4 rounded-xl border-2 border-emerald-200 focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 transition-all outline-none bg-white/80 font-medium @error('password') border-red-400 @enderror"
                            placeholder="••••••••••">

                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-5 flex items-center text-gray-400 hover:text-emerald-600 transition-colors">
                            <i class="fa-regular fa-eye text-lg"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" id="loginBtn"
                    class="w-full mt-4 py-4 rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white font-bold text-base shadow-xl shadow-emerald-200 active:scale-[0.98] transition-all flex items-center justify-center gap-3 group">
                    <span id="btnText">
                        <i class="fa-solid fa-right-to-bracket mr-2 group-hover:translate-x-1 transition-transform"></i>
                        Masuk ke Sistem
                    </span>
                    <svg id="btnLoader" class="hidden w-5 h-5 animate-spin text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                </button>
            </form>

            <div class="mt-10 pt-8 border-t border-emerald-100">
                <p class="text-center text-gray-500 text-xs font-semibold flex items-center justify-center gap-2">
                    <i class="fa-regular fa-copyright"></i>
                    2025 Pos Kios Tani - Sistem Agribisnis Digital
                </p>
            </div>
        </div>
    </div>

    <script>
        const form = document.querySelector("form");
        const btn = document.getElementById("loginBtn");
        const btnText = document.getElementById("btnText");
        const btnLoader = document.getElementById("btnLoader");
        const passwordInput = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");
        const toggleIcon = togglePassword.querySelector("i");

        togglePassword.addEventListener("click", () => {
            const isPassword = passwordInput.type === "password";
            passwordInput.type = isPassword ? "text" : "password";
            toggleIcon.classList.toggle("fa-eye");
            toggleIcon.classList.toggle("fa-eye-slash");
        });

        form.addEventListener("submit", function () {
            btn.disabled = true;
            btn.classList.add("opacity-80", "cursor-not-allowed");
            btnText.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Memproses Autentikasi...';
            btnLoader.classList.remove("hidden");
        });
    </script>

</body>

</html>