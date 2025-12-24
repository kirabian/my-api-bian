<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bian API Documentation v1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                <h3 class="text-xs font-bold text-gray-400 uppercase mb-3">Information</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#intro" class="hover:text-blue-600">Introduction</a></li>
                    <li><a href="#rate-limit" class="hover:text-blue-600">Rate Limiting</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase mb-3">Endpoints</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#users" class="text-blue-600 font-bold border-l-2 border-blue-600 pl-3">Get Users</a></li>
                </ul>
            </div>
        </nav>

        <main class="flex-1 md:ml-64 p-8 lg:p-12 max-w-5xl">
            <section id="intro" class="mb-16">
                <h1 class="text-4xl font-black mb-4">Bian API (1.0.0)</h1>
                <p class="text-gray-600 leading-relaxed">Selamat datang di dokumentasi resmi Bian API. API ini bersifat open-source dan dapat digunakan secara gratis dengan batasan tertentu.</p>
            </section>

            <section id="rate-limit" class="mb-16 bg-blue-50 p-6 rounded-2xl border border-blue-100">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2"><i class="fas fa-clock text-blue-600"></i> Rate Limiting</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-white p-4 rounded-xl shadow-sm">
                        <p class="text-gray-400">Public (Tanpa Login)</p>
                        <p class="text-2xl font-black">5 <span class="text-xs font-normal">Req / Min</span></p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border-2 border-blue-200">
                        <p class="text-blue-600 font-bold text-xs uppercase">Registered User</p>
                        <p class="text-2xl font-black">100 <span class="text-xs font-normal">Req / Min</span></p>
                    </div>
                </div>
            </section>

            <section id="users">
                <div class="flex items-center gap-3 mb-6">
                    <span class="bg-green-500 text-white px-3 py-1 rounded text-sm font-bold">GET</span>
                    <h2 class="text-2xl font-bold">Fetch Users List</h2>
                </div>

                <div class="bg-gray-900 text-gray-300 p-4 rounded-xl font-mono text-sm mb-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 bg-white/10 px-3 py-1 text-[10px]">URL</div>
                    https://my-api-bian.absenps.com/v1/users
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-bold mb-3">Parameters</h3>
                        <p class="text-sm text-gray-400">Endpoint ini tidak memerlukan parameter khusus.</p>
                    </div>
                    <div>
                        <h3 class="font-bold mb-3">Response Example</h3>
                        <div class="bg-gray-100 p-4 rounded-xl text-xs font-mono">
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
                </div>
            </section>
        </main>
    </div>
</body>
</html>