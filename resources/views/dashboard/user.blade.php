<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Bian API</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #0b0e14; color: #ffffff; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .gradient-pink { background: linear-gradient(90deg, #ff69b4, #da22ff); }
    </style>
</head>
<body class="font-sans min-h-screen">

    <nav class="flex justify-between items-center px-8 py-6 max-w-7xl mx-auto w-full">
        <div class="text-2xl font-bold tracking-tighter">
            BIAN <span class="text-blue-500 italic">API</span>
        </div>
        <div class="flex items-center gap-6">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Developer Mode</span>
            <a href="/v1/logout" class="bg-red-500/10 text-red-500 border border-red-500/20 px-6 py-2 rounded-xl text-sm font-bold hover:bg-red-500 hover:text-white transition">Logout</a>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-8 py-10">
        <div class="mb-12">
            <h1 class="text-4xl font-black tracking-tight mb-2">Welcome back, <span class="text-pink-500">{{ $user->username }}</span>! 🚀</h1>
            <p class="text-gray-500">Kelola API Key dan pantau penggunaan limit Anda di sini.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 glass p-8 rounded-[32px] relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <i class="fas fa-key text-8xl"></i>
                </div>
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400">Your API Secret Key</h3>
                    <button onclick="confirmRevoke()" class="text-pink-500 hover:text-pink-400 transition flex items-center gap-2 text-xs font-bold">
                        <i class="fas fa-sync-alt"></i> REVOKE KEY
                    </button>
                </div>

                <div class="bg-black/40 border border-white/5 p-6 rounded-2xl flex items-center justify-between group">
                    <code id="api-key-display" class="font-mono text-blue-400 text-lg break-all">{{ $user->api_key }}</code>
                    <button onclick="copyKey()" class="ml-4 text-gray-600 hover:text-white transition">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <p class="mt-4 text-[11px] text-gray-500 italic">Gunakan key ini di setiap request header Anda. Jangan bagikan key ini kepada siapapun.</p>
            </div>

            <div class="glass p-8 rounded-[32px] border-l-4 border-l-pink-500">
                <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-6">Limit Usage</h3>
                
                <div class="flex justify-between items-end mb-4">
                    <div class="text-5xl font-black text-white">{{ $user->request_count }}</div>
                    <div class="text-gray-500 font-bold mb-1">/ {{ $user->daily_limit }}</div>
                </div>

                <div class="w-full bg-white/5 h-3 rounded-full overflow-hidden mb-4">
                    @php 
                        $percent = ($user->request_count / $user->daily_limit) * 100;
                    @endphp
                    <div id="progress-bar" class="gradient-pink h-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                </div>
                
                <p class="text-[11px] text-gray-500 leading-relaxed">
                    Limit akan di-reset setiap 24 jam. Revoke key tidak akan mereset hitungan penggunaan Anda.
                </p>
            </div>

        </div>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="/docs/v1" class="glass p-6 rounded-2xl flex items-center gap-4 hover:bg-white/5 transition group">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500 group-hover:scale-110 transition">
                    <i class="fas fa-book"></i>
                </div>
                <div>
                    <h4 class="font-bold text-white">API Documentation</h4>
                    <p class="text-xs text-gray-500">Lihat cara integrasi endpoint kami.</p>
                </div>
            </a>
            <div class="glass p-6 rounded-2xl flex items-center gap-4 opacity-50 cursor-not-allowed">
                <div class="w-12 h-12 rounded-xl bg-gray-500/10 flex items-center justify-center text-gray-500">
                    <i class="fas fa-history"></i>
                </div>
                <div>
                    <h4 class="font-bold text-white">Request History</h4>
                    <p class="text-xs text-gray-500">Coming Soon: Lacak penggunaan Anda.</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        function copyKey() {
            const key = document.getElementById('api-key-display').innerText;
            navigator.clipboard.writeText(key);
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Key copied!',
                showConfirmButton: false,
                timer: 1500,
                background: '#1a1d23',
                color: '#fff'
            });
        }

        async function confirmRevoke() {
            const { value: confirmed } = await Swal.fire({
                title: 'Ganti API Key?',
                text: "Pemakaian limit tetap lanjut, tapi key lama akan mati!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff69b4',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Ganti!',
                background: '#1a1d23',
                color: '#fff'
            });

            if (confirmed) {
                const res = await fetch('/v1/revoke-key', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await res.json();
                if(data.status === 'success') {
                    document.getElementById('api-key-display').innerText = data.new_key;
                    Swal.fire({
                        icon: 'success',
                        title: 'Key Updated!',
                        background: '#1a1d23',
                        color: '#fff'
                    });
                }
            }
        }
    </script>
</body>
</html>