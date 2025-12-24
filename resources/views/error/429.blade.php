<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Limit Exceeded - Bian API</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #0b0e14; color: #ffffff; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.08); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6 font-sans">

    <div class="max-w-md w-full text-center">
        <div class="mb-8 flex justify-center">
            <div class="w-24 h-24 bg-pink-500/10 rounded-full flex items-center justify-center border border-pink-500/20 animate-pulse">
                <i class="fas fa-hourglass-half text-4xl text-pink-500"></i>
            </div>
        </div>

        <h1 class="text-4xl font-black mb-4 tracking-tight">Whoops! <span class="text-pink-500">Slow Down.</span></h1>
        <p class="text-gray-500 mb-8 leading-relaxed">
            Anda telah mencapai batas permintaan API. Tunggu beberapa saat sebelum mencoba kembali atau login untuk mendapatkan limit lebih tinggi.
        </p>

        <div class="glass p-6 rounded-3xl mb-8">
            <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Current Limits</h3>
            <div class="flex justify-around items-center">
                <div class="text-center">
                    <p class="text-[10px] text-gray-500 mb-1 uppercase">Public</p>
                    <p class="font-bold text-white">5 <span class="text-[10px] font-normal">Req/Min</span></p>
                </div>
                <div class="h-8 w-[1px] bg-white/10"></div>
                <div class="text-center">
                    <p class="text-[10px] text-blue-500 mb-1 uppercase">Member</p>
                    <p class="font-bold text-white">100 <span class="text-[10px] font-normal">Req/Min</span></p>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            <a href="/v1/login-page" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 rounded-2xl transition shadow-lg shadow-pink-900/40">
                Login to Increase Limit
            </a>
            <a href="/" class="text-gray-500 hover:text-white transition text-sm font-medium">
                Back to Documentation
            </a>
        </div>
    </div>

</body>
</html>