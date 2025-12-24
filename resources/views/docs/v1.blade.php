<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bian API Documentation v1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        pre { background: #1e1e1e; color: #d4d4d4; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; }
        .endpoint-card { border: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease; }
        .endpoint-card:hover { border-color: #2563eb; }
    </style>
</head>
<body class="bg-white font-sans text-gray-800">

    <header class="fixed w-full bg-white border-b z-50 px-6 py-3 flex justify-between items-center">
        <div class="font-bold text-xl tracking-tighter">BIAN <span class="text-blue-600 italic">API DOCS</span></div>
        <div class="flex gap-4">
            <a href="/" class="text-sm py-2 hover:text-blue-600">Home</a>
            @if($user)
                <a href="/dashboard" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold">Dashboard</a>
            @else
                <a href="/v1/login-page" class="border border-blue-600 text-blue-600 px-4 py-2 rounded-lg text-sm font-bold">Login</a>
            @endif
        </div>
    </header>

    <div class="flex pt-16">
        <nav class="w-64 fixed h-full border-r bg-gray-50 p-6 overflow-y-auto hidden md:block">
            <div class="mb-8">
                <h3 class="text-xs font-bold text-gray-400 uppercase mb-3 text-[10px] tracking-widest">Information</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#intro" class="hover:text-blue-600 block py-1">Introduction</a></li>
                    <li><a href="#rate-limit" class="hover:text-blue-600 block py-1">Rate Limiting</a></li>
                    <li><a href="#authentication" class="hover:text-blue-600 block py-1">Authentication</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase mb-3 text-[10px] tracking-widest">Endpoints</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#users" class="hover:text-blue-600 block py-1">Get Users</a></li>
                    <li><a href="#prayer" class="hover:text-blue-600 block py-1 font-bold text-blue-600">Prayer Times</a></li>
                </ul>
            </div>
        </nav>

        <main class="flex-1 md:ml-64 p-8 lg:p-12 max-w-5xl">
            
            <section id="intro" class="mb-16">
                <h1 class="text-4xl font-black mb-4 tracking-tight">Bian API (1.0.0)</h1>
                <p class="text-gray-600 leading-relaxed text-lg">Selamat datang di dokumentasi resmi Bian API. API ini bersifat open-source dan dapat digunakan secara gratis. Kami menyediakan data pengguna dan fitur jadwal sholat seluruh dunia yang akurat.</p>
            </section>

            <section id="rate-limit" class="mb-16 bg-blue-50 p-8 rounded-[32px] border border-blue-100">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-2"><i class="fas fa-clock text-blue-600"></i> Batasan Penggunaan (Rate Limit)</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <p class="text-gray-400 font-bold uppercase text-[10px] mb-2">Public (Tanpa API Key)</p>
                        <p class="text-3xl font-black text-gray-800">5 <span class="text-xs font-normal text-gray-500">Req / Menit</span></p>
                        <p class="mt-2 text-gray-500 text-xs italic">*Dibatasi berdasarkan alamat IP Anda.</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-blue-200">
                        <p class="text-blue-600 font-bold uppercase text-[10px] mb-2">Member (Pakai API Key)</p>
                        <p class="text-3xl font-black text-blue-600">100 <span class="text-xs font-normal text-gray-500">Req / Menit</span></p>
                        <p class="mt-2 text-gray-500 text-xs italic">*Wajib menyertakan header X-BIAN-KEY.</p>
                    </div>
                </div>
            </section>

            <section id="authentication" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">Authentication</h2>
                <p class="text-gray-600 mb-4">Untuk mendapatkan limit yang lebih tinggi, kirimkan API Key Anda melalui header HTTP pada setiap permintaan:</p>
                <div class="bg-gray-900 text-blue-300 p-4 rounded-xl font-mono text-sm">
                    X-BIAN-KEY: your_api_key_here
                </div>
            </section>

            <hr class="mb-16 border-gray-100">

            <section id="users" class="mb-20 scroll-mt-24">
                <div class="flex items-center gap-3 mb-6">
                    <span class="bg-green-500 text-white px-3 py-1 rounded-lg text-sm font-bold">GET</span>
                    <h2 class="text-2xl font-bold">Fetch Users List</h2>
                </div>
                <div class="bg-gray-900 text-gray-300 p-4 rounded-xl font-mono text-sm mb-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 bg-white/10 px-3 py-1 text-[10px] text-white">URL</div>
                    https://my-api-bian.absenps.com/v1/users
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-bold mb-3 text-gray-400 uppercase text-xs tracking-widest">Parameters</h3>
                        <p class="text-sm text-gray-400">Endpoint ini tidak memerlukan parameter khusus.</p>
                    </div>
                    <div>
                        <h3 class="font-bold mb-3 text-gray-400 uppercase text-xs tracking-widest">Response Example</h3>
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

            <section id="prayer" class="mb-20 scroll-mt-24 p-8 bg-gray-50 rounded-[32px] border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <span class="bg-green-500 text-white px-3 py-1 rounded-lg text-sm font-bold">GET</span>
                    <h2 class="text-2xl font-bold">Global Prayer Times</h2>
                </div>
                <p class="text-gray-600 mb-6">Mendapatkan jadwal sholat akurat untuk kota manapun di seluruh dunia menggunakan data dari Aladhan Global API.</p>
                
                <div class="bg-gray-900 text-gray-300 p-4 rounded-xl font-mono text-sm mb-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 bg-white/10 px-3 py-1 text-[10px] text-white">URL</div>
                    https://my-api-bian.absenps.com/v1/prayer-times?city=Jakarta&country=Indonesia
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-bold mb-4 text-gray-400 uppercase text-xs tracking-widest">Query Parameters</h3>
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="text-gray-400 border-b border-gray-200">
                                    <th class="py-2">Field</th>
                                    <th class="py-2">Type</th>
                                    <th class="py-2">Description</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600">
                                <tr class="border-b border-gray-100">
                                    <td class="py-3 font-mono text-blue-600">city</td>
                                    <td class="py-3 italic">string</td>
                                    <td class="py-3">Nama kota (ex: London)</td>
                                </tr>
                                <tr class="border-b border-gray-100">
                                    <td class="py-3 font-mono text-blue-600">country</td>
                                    <td class="py-3 italic">string</td>
                                    <td class="py-3">Nama negara (ex: UK)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <h3 class="font-bold mb-3 text-gray-400 uppercase text-xs tracking-widest">Response Example</h3>
<pre>{
  "status": "success",
  "location": {
    "city": "Jakarta",
    "country": "Indonesia"
  },
  "timings": {
    "Fajr": "04:12",
    "Dhuhr": "11:51",
    "Asr": "15:15",
    "Maghrib": "18:05",
    "Isha": "19:18"
  }
}</pre>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <footer class="bg-gray-50 border-t py-12 ml-0 md:ml-64">
        <div class="max-w-5xl mx-auto px-8 text-center">
            <p class="text-gray-400 text-sm">&copy; 2024 Bian API Development. Powered by Laravel & Aladhan API.</p>
        </div>
    </footer>

    <script>
        // Smooth scrolling for sidebar links
        document.querySelectorAll('nav a').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>