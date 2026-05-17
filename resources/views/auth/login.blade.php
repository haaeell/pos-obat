<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Login - Pos </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.bunny.net/css?family=Plus+Jakarta+Sans:300,400,500,600,700,800" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background:
                radial-gradient(circle at 30% 20%, rgba(34, 197, 94, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 70% 80%, rgba(16, 185, 129, 0.1) 0%, transparent 50%);
            animation: bgMove 15s ease-in-out infinite;
        }

        @keyframes bgMove {

            0%,
            100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(-50px, -50px);
            }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }

        .login-illustration {
            background: linear-gradient(135deg, #22c55e 0%, #10b981 50%, #059669 100%);
            position: relative;
            overflow: hidden;
        }

        .login-illustration::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.3;
            }
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .shine {
            position: relative;
            overflow: hidden;
        }

        .shine::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: 0.5s;
        }

        .shine:hover::after {
            left: 100%;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6 relative">

    <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
        <div class="absolute top-20 left-20 w-2 h-2 bg-green-400 rounded-full animate-ping"></div>
        <div class="absolute top-40 right-32 w-1 h-1 bg-emerald-400 rounded-full animate-ping"
            style="animation-delay: 0.5s"></div>
        <div class="absolute bottom-32 left-40 w-1.5 h-1.5 bg-green-300 rounded-full animate-ping"
            style="animation-delay: 1s"></div>
    </div>

    <div class="w-full max-w-5xl relative z-10">

        <div class="grid lg:grid-cols-[0.95fr_1.05fr] glass-card rounded-2xl overflow-hidden shadow-xl">

            <div class="p-7 lg:p-9 bg-white/95 backdrop-blur-sm order-2 lg:order-1">

                <div class="mb-8">
                    <div class="inline-block px-4 py-2 bg-green-50 rounded-full mb-4">
                        <span class="text-green-700 text-xs font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-shield-halved mr-1"></i>Portal Admin
                        </span>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900 mb-1">Masuk Akun</h2>
                    <p class="text-gray-600 font-medium">Silakan login untuk melanjutkan</p>
                </div>

                @if ($errors->any() || session('error'))
                    <div class="mb-6 rounded-2xl bg-red-50 border-l-4 border-red-500 p-5" role="alert">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-exclamation text-white text-sm"></i>
                            </div>

                            <div class="flex-1">
                                <h3 class="text-red-900 font-bold text-sm mb-1">Login Gagal</h3>
                                <div class="text-red-700 text-sm">
                                    @if ($errors->any())
                                        @foreach ($errors->all() as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    @else
                                        <p>{{ session('error') }}</p>
                                    @endif
                                </div>
                            </div>

                            <button type="button" onclick="this.closest('div[role=alert]').remove()"
                                class="text-red-400 hover:text-red-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                        <div class="relative group">
                            <div
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-green-600 transition">
                                <i class="fa-solid fa-at"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="input-glow w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:bg-white transition-all outline-none text-gray-900 font-medium placeholder:text-gray-400"
                                placeholder="admin@gmail.com">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                        <div class="relative group">
                            <div
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-green-600 transition">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="input-glow w-full pl-12 pr-12 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:bg-white transition-all outline-none text-gray-900 font-medium placeholder:text-gray-400"
                                placeholder="Masukkan password">
                            <button type="button" id="togglePassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600 transition">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="loginBtn"
                        class="shine w-full mt-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 transition-all duration-300 active:scale-95">
                        <span id="btnText">
                            <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i>
                            Login Sekarang
                        </span>
                        <i id="btnLoader" class="hidden fa-solid fa-circle-notch fa-spin"></i>
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                    <p class="text-xs text-gray-500 font-medium">
                        © 2025 Pos. All rights reserved.
                    </p>
                </div>
            </div>

            <div
                class="login-illustration p-12 lg:p-16 flex flex-col justify-center items-center text-white relative order-1 lg:order-2 min-h-[400px] lg:min-h-full">

                <div class="relative z-10 text-center">

                    <div class="mb-8 floating">
                        <div
                            class="w-32 h-32 mx-auto bg-white/20 rounded-3xl backdrop-blur-md flex items-center justify-center border-4 border-white/30 shadow-2xl rotate-6">
                            <i class="fa-solid fa-seedling text-7xl drop-shadow-lg"></i>
                        </div>
                    </div>



                </div>

                <div class="absolute bottom-8 left-8 right-8 flex justify-center gap-2 z-10">
                    <div class="w-2 h-2 bg-white rounded-full"></div>
                    <div class="w-8 h-2 bg-white rounded-full"></div>
                    <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                </div>
            </div>

        </div>

        <div class="mt-6 text-center">
            <p class="text-gray-400 text-sm font-medium">
                <i class="fa-solid fa-headset mr-2"></i>
                Butuh bantuan? Hubungi support kami
            </p>
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

        form.addEventListener("submit", function (e) {
            btn.disabled = true;
            btnText.classList.add("hidden");
            btnLoader.classList.remove("hidden");
        });
    </script>

</body>

</html>