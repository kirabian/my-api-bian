<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#FFD1DC] min-h-screen p-4 md:p-10">
    <div class="max-w-4xl mx-auto bg-white rounded-[30px] shadow-2xl overflow-hidden">
        <div class="bg-[#FF69B4] p-8 text-white flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">Halo, {{ $user->username }}! 💕</h1>
                <p class="opacity-80">Selamat datang di API Dashboard</p>
            </div>
            <a href="/v1/logout" class="bg-white text-[#FF69B4] px-6 py-2 rounded-full font-bold hover:bg-pink-50 transition">Logout</a>
        </div>
        
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-pink-50 border-2 border-pink-200 p-6 rounded-2xl">
                <h3 class="text-[#FF69B4] font-bold mb-2"><i class="fas fa-key mr-2"></i> API KEY ANDA</h3>
                <code class="block bg-white p-3 rounded-lg border border-pink-100 text-sm break-all">{{ $user->api_key }}</code>
            </div>

            <div class="bg-pink-50 border-2 border-pink-200 p-6 rounded-2xl">
                <h3 class="text-[#FF69B4] font-bold mb-2"><i class="fas fa-chart-line mr-2"></i> PENGGUNAAN LIMIT</h3>
                <div class="flex justify-between items-end">
                    <span class="text-4xl font-black text-gray-700">{{ $user->request_count }}</span>
                    <span class="text-gray-500">/ {{ $user->daily_limit }} Hits</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>