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
    </style>
</head>
<body class="font-sans min-h-screen">

    <nav class="flex justify-between items-center px-8 py-6 max-w-7xl mx-auto w-full">
        <div class="text-2xl font-bold tracking-tighter">BIAN <span class="text-blue-500 italic">API</span></div>
        <a href="/v1/logout" class="bg-red-500/10 text-red-500 border border-red-500/20 px-6 py-2 rounded-xl text-sm font-bold hover:bg-red-500 hover:text-white transition">Logout</a>
    </nav>

    <main class="max-w-6xl mx-auto px-8 py-10">
        <div class="mb-12">
            <h1 class="text-4xl font-black mb-2">Developer <span class="text-pink-500">Console</span></h1>
            <p class="text-gray-500 leading-relaxed">Keamanan akun Anda adalah prioritas kami. Masukkan password untuk mengakses data sensitif.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 glass p-8 rounded-[32px]">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400">API Secret Key</h3>
                    <button onclick="handleSecurity('revoke')" class="text-pink-500 hover:text-pink-400 text-xs font-bold flex items-center gap-2">
                        <i class="fas fa-sync-alt"></i> REVOKE KEY
                    </button>
                </div>

                <div class="bg-black/40 border border-white/5 p-6 rounded-2xl flex items-center justify-between group">
                    <code id="api-key-display" class="font-mono text-gray-600 text-lg tracking-widest overflow-hidden">********************************</code>
                    <div class="flex gap-4 ml-4">
                        <button onclick="handleSecurity('show')" class="text-gray-500 hover:text-white transition" title="Lihat Key">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="handleSecurity('copy')" class="text-gray-500 hover:text-white transition" title="Salin Key">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <p class="mt-4 text-[10px] text-gray-500 italic">*Setiap aksi melihat atau menyalin membutuhkan verifikasi password.</p>
            </div>

            <div class="glass p-8 rounded-[32px] border-l-4 border-l-pink-500">
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-6">Usage Summary</h3>
                <div class="text-5xl font-black text-white mb-4">
                    {{ $user->request_count }} 
                    <span class="text-sm font-normal text-gray-500">/ {{ $user->daily_limit }}</span>
                </div>
                <div class="w-full bg-white/5 h-2 rounded-full overflow-hidden mb-4">
                    @php 
                        $percent = ($user->request_count / $user->daily_limit) * 100;
                    @endphp
                    <div class="bg-pink-500 h-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                </div>
                <p class="text-[10px] text-gray-500">Revoke key tidak akan mereset hitungan penggunaan limit Anda.</p>
            </div>
        </div>
    </main>

    <script>
        async function handleSecurity(action) {
            // Pop-up Konfirmasi Password
            const { value: password } = await Swal.fire({
                title: 'Konfirmasi Keamanan',
                input: 'password',
                inputLabel: 'Masukkan password Anda untuk melanjutkan',
                inputPlaceholder: 'Password Anda',
                background: '#0b0e14',
                color: '#fff',
                confirmButtonColor: '#ff69b4',
                showCancelButton: true,
                cancelButtonColor: '#3085d6',
                inputAttributes: { autocapitalize: 'off', autocorrect: 'off' }
            });

            if (password) {
                try {
                    const res = await fetch('/v1/verify-action', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                        },
                        body: JSON.stringify({ password, action })
                    });

                    const data = await res.json();
                    
                    if (data.status === 'success') {
                        if (action === 'copy') {
                            // Salin ke clipboard secara asinkron
                            await navigator.clipboard.writeText(data.key);
                            Swal.fire({ 
                                icon: 'success', 
                                title: 'Key Berhasil Disalin!', 
                                background: '#0b0e14', 
                                color: '#fff', 
                                showConfirmButton: false, 
                                timer: 1500 
                            });
                        } else {
                            // Tampilkan key di layar
                            const display = document.getElementById('api-key-display');
                            display.innerText = data.key;
                            display.classList.remove('text-gray-600', 'tracking-widest');
                            display.classList.add('text-blue-400');
                            
                            if (action === 'revoke') {
                                Swal.fire({ 
                                    icon: 'success', 
                                    title: 'API Key Diperbarui!', 
                                    text: 'Gunakan key baru ini untuk request selanjutnya.',
                                    background: '#0b0e14', 
                                    color: '#fff' 
                                });
                            }
                        }
                    } else {
                        Swal.fire({ 
                            icon: 'error', 
                            title: 'Gagal!', 
                            text: data.message, 
                            background: '#0b0e14', 
                            color: '#fff' 
                        });
                    }
                } catch (err) {
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Error!', 
                        text: 'Terjadi kesalahan koneksi ke server.', 
                        background: '#0b0e14', 
                        color: '#fff' 
                    });
                }
            }
        }
    </script>
</body>
</html>