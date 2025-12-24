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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap');
        
        body { font-family: 'Plus Jakarta Sans', sans-serif; scroll-behavior: smooth; }
        code, pre { font-family: 'JetBrains Mono', monospace; }

        /* Syntax Highlighting */
        pre { background: #0d1117; color: #c9d1d9; padding: 1.5rem; border-radius: 1rem; overflow-x: auto; position: relative; border: 1px solid rgba(255,255,255,0.1); }
        .code-keyword { color: #ff7b72; } 
        .code-string { color: #a5d6ff; }  
        .code-comment { color: #8b949e; } 
        .code-variable { color: #d2a8ff; } 
        .code-attr { color: #ffa657; }     
        .code-function { color: #79c0ff; } 

        .copy-btn { 
            position: absolute; right: 12px; top: 12px; 
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); 
            color: #8b949e; padding: 6px 12px; border-radius: 8px; 
            font-size: 11px; font-weight: 600; cursor: pointer; 
            transition: all 0.2s; z-index: 10;
        }

        /* Sidebar Styling */
        #sidebar { transition: all 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .sidebar-visible { transform: translateX(0); }
        .overlay { background: rgba(0,0,0,0.5); position: fixed; inset: 0; z-index: 80; display: none; }
        
        .sidebar-link { transition: all 0.2s; border-left: 3px solid transparent; }
        .sidebar-link.active { 
            color: #2563eb !important; 
            font-weight: 800; 
            border-left: 3px solid #2563eb; 
            padding-left: 1rem; 
            background: linear-gradient(90deg, rgba(37,99,235,0.05) 0%, transparent 100%); 
        }
        
        @media (max-width: 1024px) {
            #sidebar { width: 280px; z-index: 100; position: fixed; height: 100vh; top: 0; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

    <div id="sidebar-overlay" class="overlay lg:hidden"></div>

    <header class="fixed w-full bg-white/80 backdrop-blur-md border-b z-[90] px-4 md:px-8 py-4 flex justify-between items-center text-slate-900">
        <div class="flex items-center gap-2">
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-600 p-2"><i class="fas fa-bars"></i></button>
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold italic shadow-lg shadow-blue-500/30">B</div>
            <div class="font-extrabold text-xl tracking-tighter text-slate-900">BIAN <span class="text-blue-600 italic font-medium">API DOCS</span></div>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="/" class="flex items-center gap-2 border-2 border-slate-200 text-slate-700 px-4 py-2 rounded-xl text-xs md:text-sm font-bold transition hover:border-blue-600 hover:text-blue-600">
                <i class="fas fa-home"></i> Home
            </a>
            @if($user)
                <a href="/dashboard" class="bg-blue-600 text-white px-5 py-2 rounded-xl text-xs md:text-sm font-bold shadow-xl shadow-blue-500/20 transition hover:bg-blue-700">
                    Dashboard
                </a>
            @else
                <a href="/v1/login-page" class="bg-blue-600 text-white px-5 py-2 rounded-xl text-xs md:text-sm font-bold shadow-xl shadow-blue-500/20 transition hover:bg-blue-700">
                    Login
                </a>
            @endif
        </div>
    </header>

    <div class="flex pt-20">
        <nav id="sidebar" class="sidebar-hidden lg:sidebar-visible w-72 fixed lg:sticky lg:top-20 h-[calc(100vh-80px)] bg-white border-r p-8 overflow-y-auto">
            <div class="mb-10">
                <h3 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-5">Dokumentasi</h3>
                <ul class="space-y-4 text-sm font-medium nav-list">
                    <li><a href="#intro" class="sidebar-link block text-slate-500 py-1">Introduction</a></li>
                    <li><a href="#rate-limit" class="sidebar-link block text-slate-500 py-1">Rate Limiting</a></li>
                    <li><a href="#how-to-use" class="sidebar-link block text-slate-500 py-1">Integrasi Key</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-5">Endpoints v1</h3>
                <ul class="space-y-4 text-sm font-medium nav-list">
                    <li><a href="#users" class="sidebar-link block text-slate-500 py-1">Get Users List</a></li>
                    <li><a href="#prayer" class="sidebar-link block text-slate-500 py-1">Global Prayer Times</a></li>
                    <li><a href="#gempa" class="sidebar-link block text-slate-500 py-1">Info Gempa BMKG</a></li>
                    <li><a href="#tools" class="sidebar-link block text-slate-500 py-1">Utility Tools</a></li>
                </ul>
            </div>
        </nav>

        <main class="flex-1 p-6 md:p-12 lg:p-20 max-w-full overflow-hidden">
            
            <section id="intro" class="mb-20 scroll-mt-24">
                <h1 class="text-4xl md:text-6xl font-extrabold mb-8 tracking-tighter text-slate-900">Solusi Data <span class="text-blue-600">Developer.</span></h1>
                <p class="text-slate-500 text-base md:text-xl max-w-3xl leading-relaxed">Dokumentasi resmi integrasi Bian API. Mendukung berbagai bahasa pemrograman untuk kemudahan skalabilitas.</p>
            </section>

            <section id="rate-limit" class="mb-20 scroll-mt-24">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-2">Public Access</p>
                        <p class="text-3xl font-black text-slate-900">10 <span class="text-xs font-normal text-slate-400">Req/Min</span></p>
                    </div>
                    <div class="bg-white p-8 rounded-[2rem] border-2 border-blue-600 shadow-xl shadow-blue-500/5">
                        <p class="text-[10px] font-bold text-blue-600 uppercase mb-2">Member Access</p>
                        <p class="text-4xl font-black text-slate-900 mb-2">100 <span class="text-sm font-medium text-slate-400">Req / Min</span></p>
                    </div>
                </div>
            </section>

            <section id="how-to-use" class="mb-24 scroll-mt-24">
                <h2 class="text-2xl font-extrabold mb-10 flex items-center gap-3 text-slate-900"><i class="fas fa-code text-blue-600"></i> Integrasi Kode</h2>
                
                <div class="space-y-12">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fab fa-node-js text-green-600 text-lg"></i> Node.js (Axios)
                        </p>
                        <div class="relative group">
                            <button class="copy-btn" onclick="copyCode(this)"><i class="far fa-copy"></i> Copy</button>
<pre><code><span class="code-keyword">const</span> axios = <span class="code-keyword">require</span>(<span class="code-string">'axios'</span>);

axios.<span class="code-function">get</span>(<span class="code-string">'https://my-api-bian.absenps.com/v1/info/gempa'</span>, {
    <span class="code-attr">headers</span>: { <span class="code-string">'X-BIAN-KEY'</span>: <span class="code-string">'YOUR_API_KEY'</span> }
})
.<span class="code-function">then</span>(res => console.<span class="code-function">log</span>(res.data));</code></pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="users" class="mb-24 scroll-mt-24 p-8 md:p-12 bg-white rounded-[3rem] border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <span class="bg-emerald-500 text-white px-4 py-1.5 rounded-xl text-[10px] font-black tracking-widest uppercase">Get</span>
                    <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Get Users List</h2>
                </div>
                <p class="text-slate-500 mb-8 leading-relaxed">Mengambil daftar pengembang terdaftar pada sistem Bian API.</p>
                <div class="bg-slate-900 p-5 rounded-2xl mb-8 border border-white/5 overflow-x-auto">
                    <code class="text-blue-400 font-medium text-xs md:text-sm whitespace-nowrap">https://my-api-bian.absenps.com/v1/users</code>
                </div>
                <div class="relative group">
                    <button class="copy-btn" onclick="copyCode(this)"><i class="far fa-copy"></i> Copy JSON</button>
<pre><code>{
  <span class="code-attr">"status"</span>: <span class="code-string">"success"</span>,
  <span class="code-attr">"data"</span>: [
    { <span class="code-attr">"id"</span>: <span class="code-keyword">1</span>, <span class="code-attr">"username"</span>: <span class="code-string">"bian"</span>, <span class="code-attr">"role"</span>: <span class="code-string">"admin"</span> }
  ]
}</code></pre>
                </div>
            </section>

            <section id="prayer" class="mb-24 scroll-mt-28 p-12 bg-white rounded-[3rem] border border-slate-200 relative overflow-hidden">
                <div class="flex items-center gap-3 mb-6 text-slate-900">
                    <span class="bg-emerald-500 text-white px-4 py-1.5 rounded-xl text-[10px] font-black tracking-widest uppercase">Get</span>
                    <h2 class="text-2xl font-extrabold tracking-tight">Global Prayer Times</h2>
                </div>
                <div class="bg-slate-900 p-5 rounded-2xl mb-8 flex items-center justify-between overflow-x-auto border border-white/5 group">
                    <code class="text-blue-400 font-medium text-xs md:text-sm whitespace-nowrap">https://my-api-bian.absenps.com/v1/prayer-times?city=Samarinda&country=Indonesia</code>
                </div>
                <div class="relative group">
                    <button class="copy-btn" onclick="copyCode(this)"><i class="far fa-copy"></i> Copy JSON</button>
<pre><code>{
  <span class="code-attr">"status"</span>: <span class="code-keyword">200</span>,
  <span class="code-attr">"creator"</span>: <span class="code-string">"BIAN STUDIO"</span>,
  <span class="code-attr">"result"</span>: {
    <span class="code-attr">"jadwal"</span>: { 
      <span class="code-attr">"subuh"</span>: <span class="code-string">"04:12"</span>, 
      <span class="code-attr">"dzuhur"</span>: <span class="code-string">"11:51"</span>,
      <span class="code-attr">"maghrib"</span>: <span class="code-string">"18:05"</span>
    }
  }
}</code></pre>
                </div>
            </section>

            <section id="gempa" class="mb-24 scroll-mt-28 p-12 bg-white rounded-[3rem] border border-slate-200 relative overflow-hidden">
                <div class="flex items-center gap-3 mb-6 text-slate-900">
                    <span class="bg-emerald-500 text-white px-4 py-1.5 rounded-xl text-[10px] font-black tracking-widest uppercase">Get</span>
                    <h2 class="text-2xl font-extrabold tracking-tight">Info Gempa BMKG</h2>
                </div>
                <div class="bg-slate-900 p-5 rounded-2xl mb-8 flex items-center justify-between overflow-x-auto border border-white/5">
                    <code class="text-blue-400 font-medium text-xs md:text-sm whitespace-nowrap">https://my-api-bian.absenps.com/v1/info/gempa</code>
                </div>
                <div class="relative group">
                    <button class="copy-btn" onclick="copyCode(this)"><i class="far fa-copy"></i> Copy JSON</button>
<pre><code>{
  <span class="code-attr">"status"</span>: <span class="code-keyword">200</span>,
  <span class="code-attr">"creator"</span>: <span class="code-string">"BIAN DEVELOPER STUDIO"</span>,
  <span class="code-attr">"result"</span>: {
    <span class="code-attr">"waktu_kejadian"</span>: <span class="code-string">"24 Dec 2025 - 17:41:00 WIB"</span>,
    <span class="code-attr">"skala_magnitudo"</span>: <span class="code-string">"5.2 SR"</span>,
    <span class="code-attr">"titik_lokasi"</span>: <span class="code-string">"Pusat gempa 77 km BaratDaya KAB-TASIKMALAYA"</span>,
    <span class="code-attr">"peringatan"</span>: <span class="code-string">"Tidak berpotensi tsunami"</span>,
    <span class="code-attr">"peta_visual"</span>: <span class="code-string">"https://my-api-bian.absenps.com/v1/info/gempa/map.jpg"</span>
  }
}</code></pre>
                </div>
            </section>

            <section id="tools" class="mb-24 scroll-mt-28 p-12 bg-white rounded-[3rem] border border-slate-200 relative overflow-hidden">
                <div class="flex items-center gap-3 mb-6 text-slate-900">
                    <span class="bg-blue-600 text-white px-4 py-1.5 rounded-xl text-[10px] font-black tracking-widest uppercase text-xs">Get</span>
                    <h2 class="text-2xl font-extrabold tracking-tight">Utility & Productivity Tools</h2>
                </div>
                
                <div class="mb-14">
                    <h3 class="text-sm font-bold text-slate-700 mb-3 uppercase tracking-wider">1. URL Shortener</h3>
                    <p class="text-slate-500 text-sm mb-4">Mengubah tautan panjang menjadi link pendek resmi Bian API.</p>
                    <div class="bg-slate-900 p-5 rounded-2xl mb-4 border border-white/5 font-mono text-blue-400 text-sm whitespace-nowrap overflow-x-auto">
                        https://my-api-bian.absenps.com/v1/tools/shorten?url=https://google.com
                    </div>
                    <div class="relative group">
                        <button class="copy-btn" onclick="copyCode(this)"><i class="far fa-copy"></i> Copy JSON</button>
<pre><code>{
  <span class="code-attr">"status"</span>: <span class="code-keyword">200</span>,
  <span class="code-attr">"result"</span>: {
    <span class="code-attr">"original"</span>: <span class="code-string">"https://google.com"</span>,
    <span class="code-attr">"short"</span>: <span class="code-string">"https://my-api-bian.absenps.com/go/xyz123"</span>
  }
}</code></pre>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-slate-700 mb-3 uppercase tracking-wider">2. Web Screenshot (Secure Proxy)</h3>
                    <p class="text-slate-500 text-sm mb-4">Mengambil gambar pratinjau website menggunakan engine proxy Bian API.</p>
                    <div class="bg-slate-900 p-5 rounded-2xl mb-4 border border-white/5 font-mono text-blue-400 text-sm whitespace-nowrap overflow-x-auto">
                        https://my-api-bian.absenps.com/v1/tools/ssweb?url=https://laravel.com
                    </div>
                    <div class="relative group">
                        <button class="copy-btn" onclick="copyCode(this)"><i class="far fa-copy"></i> Copy JSON</button>
<pre><code>{
  <span class="code-attr">"status"</span>: <span class="code-keyword">200</span>,
  <span class="code-attr">"result"</span>: {
    <span class="code-attr">"target"</span>: <span class="code-string">"https://laravel.com"</span>,
    <span class="code-attr">"image_url"</span>: <span class="code-string">"https://my-api-bian.absenps.com/v1/tools/ssweb/view/aHR0cHM6Ly9sYXJhdmVsLmNvbQ==/image.jpg"</span>
  }
}</code></pre>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        function toggleSidebar() {
            sidebar.classList.toggle('sidebar-hidden');
            sidebar.classList.toggle('sidebar-visible');
            overlay.style.display = sidebar.classList.contains('sidebar-visible') ? 'block' : 'none';
        }

        overlay.addEventListener('click', toggleSidebar);

        // Sidebar active monitoring
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.sidebar-link');
            
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 120) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('active');
                }
            });
        });

        // Copy Code Function
        function copyCode(btn) {
            const code = btn.parentElement.querySelector('code').innerText;
            navigator.clipboard.writeText(code).then(() => {
                const original = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                btn.style.color = '#10b981';
                setTimeout(() => {
                    btn.innerHTML = original;
                    btn.style.color = '#8b949e';
                }, 2000);
            });
        }
    </script>
</body>
</html>