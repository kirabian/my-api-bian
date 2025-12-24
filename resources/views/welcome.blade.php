<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bian API - Most Active Open Source API</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #0b0e14; color: #ffffff; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .gradient-text { background: linear-gradient(90deg, #ff69b4, #00d2ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="font-sans flex flex-col min-h-screen">

    <nav class="flex justify-between items-center px-8 py-6 max-w-7xl mx-auto w-full">
        <div class="text-2xl font-bold tracking-tighter">
            BIAN <span class="text-blue-500 italic">API</span>
        </div>
        <div class="hidden md:flex space-x-8 text-sm font-medium text-gray-400">
            <a href="/docs/v1" class="hover:text-white transition">Documentation <span class="text-xs align-top">v1</span></a>
            <a href="#" class="hover:text-white transition">Features</a>
            <a href="#" class="hover:text-white transition">Support</a>
        </div>
        <div class="flex items-center space-x-4">
            @if($user)
                <a href="/dashboard" class="text-sm font-bold border border-white/20 px-6 py-2 rounded-lg hover:bg-white hover:text-black transition">DASHBOARD</a>
            @else
                <a href="/v1/login-page" class="text-sm font-medium hover:text-pink-500 transition">Login</a>
                <a href="/v1/register-page" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-bold shadow-lg shadow-blue-900/50 transition">Get Started</a>
            @endif
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-8 py-20 flex flex-col lg:flex-row items-center gap-12 flex-grow">
        
        <div class="flex-1 space-y-8">
            <div class="inline-block px-4 py-1 rounded-full border border-pink-500/30 text-pink-500 text-xs font-bold bg-pink-500/10">
                🚀 WE RELY ON YOUR SUPPORT
            </div>
            
            <h1 class="text-7xl font-black tracking-tighter leading-none">
                Bian <span class="gradient-text">API</span>
            </h1>
            
            <p class="text-gray-400 text-lg max-w-lg leading-relaxed">
                Penyedia data terbuka untuk komunitas pengembang. Cepat, aman, dan tanpa biaya berlangganan.
            </p>

            <div class="flex flex-wrap gap-3">
                <span class="glass px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2">
                    <i class="fas fa-bolt text-yellow-400"></i> AUTH-LESS
                </span>
                <span class="glass px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 text-pink-400">
                    5 REQ/MIN PUBLIC
                </span>
                <span class="glass px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 text-blue-400">
                    100 REQ/MIN MEMBER
                </span>
            </div>

            <div class="flex gap-4 pt-4">
                <a href="/docs/v1" class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-xl font-bold transition">Learn more</a>
                <a href="/docs/v1" class="border border-white/20 hover:bg-white/5 px-8 py-3 rounded-xl font-bold transition flex items-center gap-2">
                    Get started <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>

        <div class="flex-1 w-full lg:max-w-xl">
            <div class="glass rounded-3xl overflow-hidden shadow-2xl">
                <div class="bg-white/5 px-6 py-4 flex items-center justify-between border-b border-white/10 text-xs">
                    <div class="flex items-center gap-2 text-green-400">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> GET
                        <span class="text-gray-400">/v1/users</span>
                    </div>
                </div>
                <div class="p-6 font-mono text-xs text-blue-300 leading-relaxed">
                    <pre>
{
  "status": "success",
  "data": [
    { "id": 1, "username": "bian", "role": "admin" },
    { "id": 2, "username": "user1", "role": "user" }
  ]
}</pre>
                </div>
            </div>
        </div>
    </main>

    <footer class="mt-auto border-t border-white/5 py-8 bg-black/20">
        <div class="max-w-7xl mx-auto px-8 flex flex-wrap justify-between items-center text-[10px] uppercase tracking-widest text-gray-500 font-bold">
            <div class="flex space-x-8">
                <span>SUPPORTERS</span>
                <span class="text-white">BIAN</span>
                <span class="text-white">AARON</span>
            </div>
            <div>
                POWERED BY <span class="text-pink-500">LARAVEL v10</span>
            </div>
        </div>
    </footer>

</body>
</html>