<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Bian API</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #0b0e14; color: #ffffff; scroll-behavior: smooth; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <a href="/" class="text-3xl font-bold tracking-tighter text-blue-500">
                BIAN <span class="text-white italic">API</span>
            </a>
            <h1 class="text-xl font-bold mt-2 text-white">Daftar Developer Baru</h1>
        </div>

        <div class="glass p-8 rounded-3xl shadow-2xl">
            <form id="registerForm" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Username</label>
                    <input type="text" name="username" required 
                        class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 focus:outline-none focus:border-blue-500 transition text-sm text-white"
                        placeholder="Pilih username unik">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Email</label>
                    <input type="email" name="email" required 
                        class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 focus:outline-none focus:border-blue-500 transition text-sm text-white"
                        placeholder="email@example.com">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Password</label>
                    <input type="password" name="password" required 
                        class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 focus:outline-none focus:border-blue-500 transition text-sm text-white"
                        placeholder="Minimal 8 karakter">
                </div>

                <div class="flex items-center gap-3 bg-blue-500/10 p-4 rounded-xl border border-blue-500/20">
                    <i class="fas fa-info-circle text-blue-400"></i>
                    <p class="text-[11px] text-gray-400 leading-tight">Dengan mendaftar, Anda menyetujui batas limit 100 req/min untuk member.</p>
                </div>

                <button type="submit" id="btnSubmit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-pink-900/40 transition flex items-center justify-center gap-2">
                    <span>Create My Account</span>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-white/5 text-center text-sm">
                <p class="text-gray-400">Sudah punya akun? <a href="/v1/login-page" class="text-pink-400 font-bold hover:underline">Login disini</a></p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('btnSubmit');
            const originalContent = btn.innerHTML;
            
            // Loading State
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            btn.disabled = true;

            const formData = new FormData(this);

            try {
                const response = await fetch('/v1/register', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (result.status === 'success') {
                    // Notifikasi sukses menggunakan SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Daftar!',
                        text: 'Akun Anda sudah aktif. Mengalihkan ke halaman login...',
                        background: '#1a1d24',
                        color: '#fff',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Redirect ke login page
                        window.location.href = '/v1/login-page';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Daftar',
                        text: result.message || 'Terjadi kesalahan pada sistem.',
                        background: '#1a1d24',
                        color: '#fff'
                    });
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                btn.innerHTML = originalContent;
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>