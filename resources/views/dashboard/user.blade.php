<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Bian API</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #0b0e14; color: #ffffff; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.08); }
    </style>
</head>
<body class="font-sans min-h-screen">

    <nav class="flex justify-between items-center px-8 py-6 max-w-7xl mx-auto w-full">
        <div class="text-2xl font-bold tracking-tighter">BIAN <span class="text-blue-500 italic">API</span></div>
        <a href="/v1/logout" class="bg-red-500/10 text-red-500 border border-red-500/20 px-6 py-2 rounded-xl text-sm font-bold">Logout</a>
    </nav>

    <main class="max-w-6xl mx-auto px-8 py-10">
        <div class="mb-12">
            <h1 class="text-4xl font-black mb-2">Developer <span class="text-pink-500">Console</span></h1>
            <p class="text-gray-500">Keamanan akun Anda adalah prioritas kami.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 glass p-8 rounded-[32px]">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400">API Secret Key</h3>
                    <button onclick="handleSecurity('revoke')" class="text-pink-500 hover:text-pink-400 text-xs font-bold flex items-center gap-2">
                        <i class="fas fa-sync-alt"></i> REVOKE
                    </button>
                </div>

                <div class="bg-black/40 border border-white/5 p-6 rounded-2xl flex items-center justify-between">
                    <code id="api-key-display" class="font-mono text-gray-600 text-lg tracking-widest">********************************</code>
                    <div class="flex gap-4">
                        <button onclick="handleSecurity('show')" class="text-gray-400 hover:text-white" title="Lihat Key">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="handleSecurity('copy')" class="text-gray-400 hover:text-white" title="Salin Key">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="glass p-8 rounded-[32px] border-l-4 border-l-pink-500">
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-6">Usage Summary</h3>
                <div class="text-5xl font-black text-white mb-4">{{ $user->request_count }} <span class="text-sm font-normal text-gray-500">/ {{ $user->daily_limit }}</span></div>
                <div class="w-full bg-white/5 h-2 rounded-full overflow-hidden">
                    <div class="bg-pink-500 h-full" style="width: {{ ($user->request_count / $user->daily_limit) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </main>

    <script>
        async function handleSecurity(action) {
            const { value: password } = await Swal.fire({
                title: 'Konfirmasi Keamanan',
                input: 'password',
                inputLabel: 'Masukkan password Anda untuk melanjutkan',
                inputPlaceholder: 'Password Anda',
                background: '#0b0e14',
                color: '#fff',
                confirmButtonColor: '#ff69b4',
                showCancelButton: true,
                inputAttributes: { autocapitalize: 'off', autocorrect: 'off' }
            });

            if (password) {
                const res = await fetch('/v1/verify-action', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    },
                    body: JSON.stringify({ password, action })
                });

                const data = await res.json();
                if (data.status === 'success') {
                    if (action === 'copy') {
                        navigator.clipboard.writeText(data.key);
                        Swal.fire({ icon: 'success', title: 'Key Berhasil Disalin!', background: '#0b0e14', color: '#fff', showConfirmButton: false, timer: 1500 });
                    } else if (action === 'show' || action === 'revoke') {
                        document.getElementById('api-key-display').innerText = data.key;
                        document.getElementById('api-key-display').classList.remove('text-gray-600');
                        document.getElementById('api-key-display').classList.add('text-blue-400');
                        if (action === 'revoke') Swal.fire({ icon: 'success', title: 'Key Diperbarui!', background: '#0b0e14', color: '#fff' });
                    }
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message, background: '#0b0e14', color: '#fff' });
                }
            }
        }
    </script>
</body>
</html>