<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard - Bian API</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-[#FFD1DC] min-h-screen p-4 md:p-10 font-sans">
    <div class="max-w-4xl mx-auto bg-white rounded-[30px] shadow-2xl overflow-hidden">
        <div class="bg-[#FF69B4] p-8 text-white flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">Halo, {{ $user->username }}! 💕</h1>
                <p class="opacity-80">Selamat datang di API Dashboard</p>
            </div>
            <a href="/v1/logout" class="bg-white text-[#FF69B4] px-6 py-2 rounded-full font-bold hover:bg-pink-50 transition">Logout</a>
        </div>
        
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-pink-50 border-2 border-pink-200 p-6 rounded-2xl relative">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-[#FF69B4] font-bold"><i class="fas fa-key mr-2"></i> API KEY ANDA</h3>
                    <button onclick="confirmRevoke()" class="text-[#FF69B4] hover:text-pink-700 transition" title="Ganti Key Baru">
                        <i class="fas fa-sync-alt text-sm"></i>
                    </button>
                </div>
                <code id="api-key-display" class="block bg-white p-3 rounded-lg border border-pink-100 text-sm break-all font-mono text-pink-600">{{ $user->api_key }}</code>
                <p class="text-[10px] text-pink-400 mt-2 italic">*Hati-hati, jika di-revoke key lama tidak akan berfungsi.</p>
            </div>

            <div class="bg-pink-50 border-2 border-pink-200 p-6 rounded-2xl">
                <h3 class="text-[#FF69B4] font-bold mb-2"><i class="fas fa-chart-line mr-2"></i> PENGGUNAAN LIMIT</h3>
                <div class="flex justify-between items-end">
                    <span class="text-4xl font-black text-gray-700">{{ $user->request_count }}</span>
                    <span class="text-gray-500 text-sm">/ {{ $user->daily_limit }} Hits</span>
                </div>
                <div class="w-full bg-pink-200 h-2 rounded-full mt-4 overflow-hidden">
                    @php 
                        $percent = ($user->request_count / $user->daily_limit) * 100;
                    @endphp
                    <div class="bg-[#FF69B4] h-full" style="width: {{ $percent }}%"></div>
                </div>
            </div>
        </div>

        <div class="px-8 pb-8">
            <a href="/docs/v1" class="block w-full text-center bg-gray-50 border border-dashed border-pink-300 p-4 rounded-xl text-pink-500 font-bold hover:bg-pink-50 transition">
                <i class="fas fa-book mr-2"></i> Baca Dokumentasi API
            </a>
        </div>
    </div>

    <script>
        async function confirmRevoke() {
            const { value: confirmed } = await Swal.fire({
                title: 'Ganti API Key?',
                text: "Aplikasi yang menggunakan key lama akan error!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FF69B4',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ganti!',
                cancelButtonText: 'Batal'
            });

            if (confirmed) {
                try {
                    const response = await fetch('/v1/revoke-key', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await response.json();
                    
                    if(data.status === 'success') {
                        document.getElementById('api-key-display').innerText = data.new_key;
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'API Key Anda telah diperbarui.',
                            icon: 'success',
                            confirmButtonColor: '#FF69B4'
                        });
                    }
                } catch (error) {
                    Swal.fire('Error', 'Gagal memperbarui key', 'error');
                }
            }
        }
    </script>
</body>
</html>