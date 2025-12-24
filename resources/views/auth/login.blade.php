<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bian API</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #0b0e14; color: #ffffff; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <a href="/" class="text-3xl font-bold tracking-tighter">
                BIAN <span class="text-blue-500 italic">API</span>
            </a>
            <p class="text-gray-400 mt-2">Masuk untuk akses Dashboard Developer</p>
        </div>

        <div class="glass p-8 rounded-3xl shadow-2xl">
            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/50 text-red-500 p-3 rounded-xl text-sm mb-6 flex items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <form action="/v1/login" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Username</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="text" name="username" required 
                            class="w-full bg-white/5 border border-white/10 rounded-xl py-3 pl-12 pr-4 focus:outline-none focus:border-blue-500 transition text-sm"
                            placeholder="Masukkan username">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="password" name="password" required 
                            class="w-full bg-white/5 border border-white/10 rounded-xl py-3 pl-12 pr-4 focus:outline-none focus:border-blue-500 transition text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-900/40 transition flex items-center justify-center gap-2">
                    Sign In <i class="fas fa-sign-in-alt text-xs"></i>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-white/5 text-center">
                <p class="text-sm text-gray-400">
                    Belum punya akun? 
                    <a href="/v1/register-page" class="text-blue-400 hover:text-blue-300 font-bold">Daftar Sekarang</a>
                </p>
            </div>
        </div>
        
        <p class="text-center text-gray-600 text-[10px] mt-8 uppercase font-bold tracking-widest">
            &copy; 2024 BianDev Studio. All Rights Reserved.
        </p>
    </div>

</body>
</html>