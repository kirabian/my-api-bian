<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bian API Documentation v1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #ffffff; color: #1f2937; }
        /* Syntax Highlighting Colors */
        pre { background: #1e1e1e; color: #d4d4d4; padding: 1.25rem; border-radius: 0.75rem; overflow-x: auto; position: relative; }
        .code-keyword { color: #569cd6; } /* Blue */
        .code-string { color: #ce9178; }  /* Orange/Brown */
        .code-comment { color: #6a9955; } /* Green */
        .code-variable { color: #9cdcfe; } /* Light Blue */
        .code-attr { color: #d19a66; }     /* Gold */
        
        .copy-btn { position: absolute; right: 10px; top: 10px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 4px 8px; border-radius: 6px; font-size: 10px; cursor: pointer; transition: all 0.2s; }
        .copy-btn:hover { background: rgba(255,255,255,0.2); }
        .sidebar-link.active { color: #2563eb; font-weight: 700; border-left: 2px solid #2563eb; padding-left: 0.75rem; }
    </style>
</head>
<body class="font-sans">

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
                <p class="text-gray-600 leading-relaxed text-lg">Integrasi cepat menggunakan sistem Header Key. Salin kode di bawah untuk memulai proyek Anda.</p>
            </section>

            <section id="how-to-use" class="mb-20 scroll-mt-24">
                <h2 class="text-3xl font-black mb-8 tracking-tight">Tutorial Integrasi</h2>
                
                <div class="space-y-12">
                    <div>
                        <div class="flex justify-between items-end mb-3">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Node.js (Axios)</p>
                        </div>
                        <div class="relative group">
                            <button class="copy-btn" onclick="copyCode(this)"><i class="fas fa-copy mr-1"></i> Copy</button>
<pre><code><span class="code-keyword">const</span> axios = <span class="code-keyword">require</span>(<span class="code-string">'axios'</span>);

axios.<span class="code-keyword">get</span>(<span class="code-string">'https://my-api-bian.absenps.com/v1/prayer-times?city=Jakarta'</span>, {
    <span class="code-attr">headers</span>: {
        <span class="code-string">'X-BIAN-KEY'</span>: <span class="code-string">'MASUKKAN_API_KEY_ANDA'</span> <span class="code-comment">//</span>
    }
})
.<span class="code-keyword">then</span>(response => {
    console.<span class="code-keyword">log</span>(response.data);
})
.<span class="code-keyword">catch</span>(error => {
    console.<span class="code-keyword">error</span>(error);
});</code></pre>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-400 mb-3 uppercase tracking-widest">PHP (cURL)</p>
                        <div class="relative group">
                            <button class="copy-btn" onclick="copyCode(this)"><i class="fas fa-copy mr-1"></i> Copy</button>
<pre><code><span class="code-comment">//</span>
<span class="code-variable">$apiKey</span> = <span class="code-string">"YOUR_KEY"</span>;
<span class="code-variable">$ch</span> = curl_init(<span class="code-string">"https://my-api-bian.absenps.com/v1/prayer-times"</span>);

curl_setopt(<span class="code-variable">$ch</span>, CURLOPT_HTTPHEADER, [
    <span class="code-string">"X-BIAN-KEY: <span class="code-variable">$apiKey</span>"</span>
]);
curl_setopt(<span class="code-variable">$ch</span>, CURLOPT_RETURNTRANSFER, <span class="code-keyword">true</span>);

<span class="code-variable">$response</span> = curl_exec(<span class="code-variable">$ch</span>);
echo <span class="code-variable">$response</span>;</code></pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="prayer" class="mb-20 scroll-mt-24">
                <h2 class="text-2xl font-bold mb-6">Prayer Times Response</h2>
                <div class="relative group">
                    <button class="copy-btn" onclick="copyCode(this)"><i class="fas fa-copy mr-1"></i> Copy</button>
<pre><code>{
  <span class="code-attr">"status"</span>: <span class="code-string">"success"</span>,
  <span class="code-attr">"timings"</span>: {
    <span class="code-attr">"Fajr"</span>: <span class="code-string">"04:45"</span>,
    <span class="code-attr">"Dhuhr"</span>: <span class="code-string">"12:12"</span>,
    <span class="code-attr">"Maghrib"</span>: <span class="code-string">"18:18"</span>
  },
  <span class="code-attr">"limit_info"</span>: <span class="code-string">"100/min"</span> <span class="code-comment">//</span>
}</code></pre>
                </div>
            </section>
        </main>
    </div>

    <script>
        function copyCode(btn) {
            const code = btn.parentElement.querySelector('code').innerText;
            navigator.clipboard.writeText(code).then(() => {
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check mr-1"></i> Copied!';
                btn.style.background = '#10b981';
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = 'rgba(255,255,255,0.1)';
                }, 2000);
                
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Berhasil disalin!',
                    showConfirmButton: false,
                    timer: 1500,
                    background: '#1e1e1e',
                    color: '#fff'
                });
            });
        }
    </script>
</body>
</html>