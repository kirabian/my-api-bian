<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bian API Documentation v1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        pre { background: #1e1e1e; color: #d4d4d4; padding: 1.25rem; border-radius: 0.75rem; overflow-x: auto; font-size: 0.85rem; line-height: 1.6; }
        .endpoint-card { border: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease; }
        .endpoint-card:hover { border-color: #2563eb; }
        .sidebar-link.active { color: #2563eb; font-weight: 700; border-left: 2px solid #2563eb; padding-left: 0.75rem; }
        code { color: #eb5757; background: rgba(235, 87, 87, 0.05); padding: 0.2rem 0.4rem; border-radius: 4px; font-family: monospace; }
    </style>
</head>
<body class="bg-white font-sans text-gray-800">

    <header class="fixed w-full bg-white border-b z-50 px-6 py-3 flex justify-between items-center">
        <div class="font-bold text-xl tracking-tighter italic">BIAN <span class="text-blue-600">API DOCS</span></div>
        <div class="flex gap-4">
            <a href="/" class="text-sm py-2 hover:text-blue-600 transition">Home</a>
            @if($user)
                <a href="/dashboard" class="bg-blue-600 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition">Dashboard</a>
            @else
                <a href="/v1/login-page" class="border border-blue-600 text-blue-600 px-5 py-2 rounded-xl text-sm font-bold hover:bg-blue-50 transition">Login</a>
            @endif
        </div>
    </header>

    <div class="flex pt-16">
        <nav class="w-64 fixed h-full border-r bg-gray-50 p-6 overflow-y-auto hidden md:block">
            <div class="mb-8">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4">Mulai</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="#intro" class="hover:text-blue-600 transition block">Introduction</a></li>
                    <li><a href="#rate-limit" class="hover:text-blue-600 transition block">Rate Limiting</a></li>
                    <li><a href="#how-to-use" class="hover:text-blue-600 transition font-bold text-blue-600 block">How to Use (Tutorial)</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4">Endpoints</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="#users" class="hover:text-blue-600 transition block">Get Users</a></li>
                    <li><a href="#prayer" class="hover:text-blue-600 transition block">Prayer Times</a></li>
                </ul>
            </div>
        </nav>

        <main class="flex-1 md:ml-64 p-8 lg:p-16 max-w-5xl">
            
            <section id="intro" class="mb-20">
                <h1 class="text-5xl font-black mb-6 tracking-tighter">Bian API <span class="text-blue-600 text-2xl font-normal ml-2">v1.0.0</span></h1>
                <p class="text-gray-600 leading-relaxed text-lg max-w-3xl">
                    Dokumentasi resmi untuk integrasi Bian API. Kami menyediakan layanan data yang cepat dan aman untuk kebutuhan pengembangan aplikasi Anda, mulai dari data pengguna hingga jadwal ibadah global.
                </p>
            </section>

            <section id="rate-limit" class="mb-20 bg-blue-50 p-8 rounded-[2rem] border border-blue-100 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 opacity-5 text-blue-600">
                    <i class="fas fa-bolt text-[12rem]"></i>
                </div>
                <h2 class="text-2xl font-bold mb-6 flex items-center gap-3">
                    <i class="fas fa-clock text-blue-600"></i> Batas Penggunaan
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Public Access</p>
                        <p class="text-3xl font-black">5 <span class="text-xs font-normal text-gray-500">Req / Min</span></p>
                        <p class="mt-2 text-xs text-gray-400 italic">*Identifikasi berdasarkan Alamat IP.</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-blue-200">
                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-2">Member Access</p>
                        <p class="text-3xl font-black text-blue-600">100 <span class="text-xs font-normal text-gray-500">Req / Min</span></p>
                        <p class="mt-2 text-xs text-blue-400 italic">*Wajib menggunakan X-BIAN-KEY.</p>
                    </div>
                </div>
            </section>

            <section id="how-to-use" class="mb-20 scroll-mt-24">
                <h2 class="text-3xl font-black mb-8 tracking-tight">Cara Menggunakan API Key</h2>
                <p class="text-gray-600 mb-8 leading-relaxed">
                    Untuk mendapatkan akses premium (100 req/min), Anda wajib menyertakan API Key di setiap request. Kami menggunakan sistem <code>Custom Header</code> dengan nama key <code>X-BIAN-KEY</code>.
                </p>

                <div class="mb-12">
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2 text-orange-600">
                        <i class="fas fa-rocket"></i> Via Postman
                    </h3>
                    <ol class="list-decimal list-inside space-y-3 text-sm text-gray-600 mb-4 ml-2">
                        <li>Buka aplikasi <b>Postman</b> dan buat request baru (GET).</li>
                        <li>Masukkan URL Endpoint (contoh: <code>https://my-api-bian.absenps.com/v1/users</code>).</li>
                        <li>Klik tab <b>Headers</b> (di bawah kolom URL).</li>
                        <li>Pada kolom <b>Key</b>, ketik: <code class="bg-gray-100 text-black">X-BIAN-KEY</code>.</li>
                        <li>Pada kolom <b>Value</b>, masukkan API Key Anda dari Dashboard.</li>
                        <li>Klik <b>Send</b>. Selesai!</li>
                    </ol>
                </div>

                <div class="space-y-8">
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2 text-blue-600">
                        <i class="fas fa-code"></i> Contoh Coding (Integrasi)
                    </h3>
                    
                    <div>
                        <p class="text-xs font-bold text-gray-400 mb-3 uppercase tracking-widest">Node.js (Axios)</p>
<pre>const axios = require('axios');

axios.get('https://my-api-bian.absenps.com/v1/prayer-times?city=Jakarta&country=Indonesia', {
    headers: {
        'X-BIAN-KEY': 'MASUKKAN_API_KEY_ANDA_DISINI'
    }
})
.then(response => {
    console.log(response.data);
})
.catch(error => {
    console.error('Gagal:', error.response.data);
});</pre>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-400 mb-3 uppercase tracking-widest">PHP (cURL)</p>
<pre>&lt;?php
$url = "https://my-api-bian.absenps.com/v1/prayer-times?city=Jakarta&country=Indonesia";
$apiKey = "MASUKKAN_API_KEY_ANDA_DISINI";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "X-BIAN-KEY: $apiKey",
    "Content-Type: application/json"
));

$response = curl_exec($ch);
curl_close($ch);

echo $response;</pre>
                    </div>
                </div>
            </section>

            <hr class="mb-20 border-gray-100">

            <section id="users" class="mb-20 scroll-mt-24">
                <div class="flex items-center gap-3 mb-6">
                    <span class="bg-green-500 text-white px-3 py-1 rounded-lg text-sm font-bold shadow-sm shadow-green-200">GET</span>
                    <h2 class="text-2xl font-bold tracking-tight">Fetch Users List</h2>
                </div>
                <div class="bg-gray-900 text-blue-400 p-4 rounded-xl font-mono text-sm mb-8 relative overflow-hidden border border-white/5">
                    <div class="absolute right-0 top-0 bg-white/10 px-3 py-1 text-[10px] text-white">URL</div>
                    https://my-api-bian.absenps.com/v1/users
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div>
                        <h3 class="font-bold mb-4 text-gray-400 uppercase text-[10px] tracking-widest">Description</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">Mengambil daftar semua pengembang yang terdaftar di platform kami.</p>
                    </div>
                    <div>
                        <h3 class="font-bold mb-4 text-gray-400 uppercase text-[10px] tracking-widest">Sample Response</h3>
<pre>{
  "status": "success",
  "data": [
    {
      "id": 1,
      "username": "bian",
      "role": "admin"
    }
  ]
}</pre>
                    </div>
                </div>
            </section>

            <section id="prayer" class="mb-20 scroll-mt-24 p-8 bg-gray-50 rounded-[2.5rem] border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <span class="bg-green-500 text-white px-3 py-1 rounded-lg text-sm font-bold shadow-sm shadow-green-200">GET</span>
                    <h2 class="text-2xl font-bold tracking-tight">Global Prayer Times</h2>
                </div>
                <p class="text-gray-600 mb-8 max-w-2xl">Mendapatkan jadwal sholat akurat untuk kota manapun di seluruh dunia menggunakan data resmi Muslim World League.</p>
                
                <div class="bg-gray-900 text-blue-400 p-4 rounded-xl font-mono text-sm mb-8 relative overflow-hidden border border-white/5">
                    <div class="absolute right-0 top-0 bg-white/10 px-3 py-1 text-[10px] text-white font-sans">URL</div>
                    https://my-api-bian.absenps.com/v1/prayer-times?city=Samarinda&country=Indonesia
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <div>
                        <h3 class="font-bold mb-4 text-gray-400 uppercase text-[10px] tracking-widest">Parameters</h3>
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="text-gray-400 border-b border-gray-100 uppercase text-[10px]">
                                    <th class="py-3">Field</th>
                                    <th class="py-3">Required</th>
                                    <th class="py-3">Description</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600">
                                <tr class="border-b border-gray-50">
                                    <td class="py-4 font-mono text-blue-600">city</td>
                                    <td class="py-4 text-xs font-bold text-red-400">Yes</td>
                                    <td class="py-4 text-xs italic">Nama kota (Jakarta, London, dll)</td>
                                </tr>
                                <tr class="border-b border-gray-50">
                                    <td class="py-4 font-mono text-blue-600">country</td>
                                    <td class="py-4 text-xs font-bold text-red-400">Yes</td>
                                    <td class="py-4 text-xs italic">Nama negara (Indonesia, UK, dll)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <h3 class="font-bold mb-4 text-gray-400 uppercase text-[10px] tracking-widest">Sample Response</h3>
<pre>{
  "status": "success",
  "location": {
    "city": "Samarinda",
    "country": "Indonesia"
  },
  "timings": {
    "Fajr": "04:45",
    "Dhuhr": "12:12",
    "Asr": "15:38",
    "Maghrib": "18:18",
    "Isha": "19:30"
  }
}</pre>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <footer class="bg-gray-50 border-t py-16 ml-0 md:ml-64">
        <div class="max-w-5xl mx-auto px-8 text-center">
            <div class="flex justify-center gap-6 mb-8 text-gray-400">
                <a href="#" class="hover:text-blue-600 transition"><i class="fab fa-github text-2xl"></i></a>
                <a href="#" class="hover:text-blue-600 transition"><i class="fab fa-instagram text-2xl"></i></a>
                <a href="#" class="hover:text-blue-600 transition"><i class="fab fa-discord text-2xl"></i></a>
            </div>
            <p class="text-gray-400 text-[11px] uppercase tracking-widest font-bold">&copy; 2024 Bian API Development • Built with Laravel & Tailwind</p>
        </div>
    </footer>

    <script>
        // Smooth scrolling for sidebar links
        document.querySelectorAll('nav a').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                document.querySelector(targetId).scrollIntoView({
                    behavior: 'smooth'
                });
                
                // Active link style
                document.querySelectorAll('nav a').forEach(a => a.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>