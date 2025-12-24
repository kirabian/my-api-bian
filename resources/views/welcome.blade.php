<!DOCTYPE html>
<html lang="id">
<head>
    <title>Bian API - Documentation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .code-block { background: #1e1e1e; color: #d4d4d4; padding: 15px; border-radius: 10px; font-family: monospace; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <nav class="bg-white border-b px-6 py-4 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-2">
            <div class="bg-pink-500 p-2 rounded-lg text-white"><i class="fas fa-rocket"></i></div>
            <span class="text-xl font-bold text-gray-800">BIAN <span class="text-pink-500">API</span></span>
        </div>
        <div class="flex gap-4">
            @if($user)
                <a href="/dashboard" class="text-gray-600 font-medium py-2">Dashboard</a>
                <a href="/v1/logout" class="bg-red-500 text-white px-5 py-2 rounded-full font-bold">Logout</a>
            @else
                <a href="/v1/login-page" class="text-pink-500 border border-pink-500 px-5 py-2 rounded-full font-bold hover:bg-pink-50">Login</a>
                <a href="/v1/register-page" class="bg-pink-500 text-white px-5 py-2 rounded-full font-bold hover:bg-pink-600 shadow-lg shadow-pink-200">Register</a>
            @endif
        </div>
    </nav>

    <div class="flex flex-1">
        <aside class="w-64 bg-white border-r p-6 hidden md:block">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Endpoints</h3>
            <ul class="space-y-2">
                <li><a href="#users" class="flex items-center gap-3 p-2 bg-pink-50 text-pink-600 rounded-lg font-medium"><i class="fas fa-users"></i> Get Users</a></li>
                <li class="opacity-50 italic text-sm p-2"><i class="fas fa-lock"></i> More coming soon...</li>
            </ul>
        </aside>

        <main class="flex-1 p-8">
            <section id="users">
                <div class="flex items-center gap-3 mb-4">
                    <span class="bg-green-500 text-white px-3 py-1 rounded text-sm font-bold">GET</span>
                    <h2 class="text-2xl font-bold text-gray-800">Fetch Users List</h2>
                </div>
                <p class="text-gray-600 mb-6">Endpoint ini digunakan untuk mengambil data seluruh user yang terdaftar dalam sistem.</p>
                
                <div class="bg-gray-200 p-3 rounded-lg font-mono text-sm mb-6 flex justify-between items-center">
                    <span>https://my-api-bian.absenps.com/v1/users</span>
                    <span class="text-xs bg-gray-300 px-2 py-1 rounded">Rate Limit: 5/min (Public)</span>
                </div>

                <h3 class="font-bold mb-3 text-gray-700">Example Usage</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-2">JavaScript (Fetch)</p>
                        <div class="code-block text-xs">
                            fetch('https://my-api-bian.absenps.com/v1/users')<br>
                            .then(res => res.json())<br>
                            .then(data => console.log(data));
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-2">Python (Requests)</p>
                        <div class="code-block text-xs">
                            import requests<br>
                            response = requests.get('https://my-api-bian.absenps.com/v1/users')<br>
                            print(response.json())
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

</body>
</html>