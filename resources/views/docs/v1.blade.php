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
        
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        code, pre { font-family: 'JetBrains Mono', monospace; }

        /* Syntax Highlighting Colors */
        pre { background: #0d1117; color: #c9d1d9; padding: 1.5rem; border-radius: 1rem; overflow-x: auto; position: relative; border: 1px solid rgba(255,255,255,0.1); }
        .code-keyword { color: #ff7b72; } 
        .code-string { color: #a5d6ff; }  
        .code-comment { color: #8b949e; } 
        .code-variable { color: #d2a8ff; } 
        .code-attr { color: #ffa657; }     
        .code-function { color: #79c0ff; } 

        /* Copy Button UI */
        .copy-btn { 
            position: absolute; 
            right: 12px; 
            top: 12px; 
            background: rgba(255,255,255,0.05); 
            border: 1px solid rgba(255,255,255,0.1); 
            color: #8b949e; 
            padding: 6px 12px; 
            border-radius: 8px; 
            font-size: 11px; 
            font-weight: 600;
            cursor: pointer; 
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 10;
        }
        .copy-btn:hover { background: rgba(255,255,255,0.1); color: #fff; border-color: rgba(255,255,255,0.3); }

        /* Responsive Sidebar */
        .sidebar-link.active { 
            color: #2563eb; 
            font-weight: 700; 
            border-left: 3px solid #2563eb; 
            padding-left: 1rem; 
            background: linear-gradient(90deg, rgba(37,99,235,0.05) 0%, transparent 100%); 
        }

        #mobile-menu { transition: transform 0.3s ease-in-out; }
        .menu-open #mobile-menu { transform: translateX(0); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

    <header class="fixed w-full bg-white/80 backdrop-blur-md border-b z-[100] px-4 md:px-8 py-4 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <button id="menu-toggle" class="lg:hidden text-slate-600 p-2"><i class="fas fa-bars"></i></button>
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold italic shadow-lg shadow-blue-500/30">B</div>
            <div class="font-extrabold text-xl tracking-tighter">BIAN <span class="text-blue-600 italic font-medium">API DOCS</span></div>
        </div>
        <div class="flex gap-2 md:gap-4">
            <a href="/" class="hidden sm:block text-sm font-semibold text-slate-500 hover:text-blue-600 transition py-2 px-2">Home</a>
            @if($user)
                <a href="/dashboard" class="bg-blue-600 text-white px-4 md:px-6 py-2 rounded-xl text-xs md:text-sm font-bold shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition-all">Dashboard</a>
            @else
                <a href="/v1/login-page" class="border-2 border-slate-200 text-slate-700 px-4 md:px-6 py-2 rounded-xl text-xs md:text-sm font-bold hover:border-blue-600 hover:text-blue-600 transition-all">Login</a>
            @endif
        </div>
    </header>

    <div id="mobile-menu" class="fixed inset-0 bg-white z-[90] transform -translateX-full lg:hidden pt-24 px-8 overflow-y-auto">
        <div class="mb-10">
            <h3 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-[0.2em] mb-5">Dokumentasi</h3>
            <ul class="space-y-4 text-sm font-medium mobile-nav">
                <li><a href="#intro" class="block text-slate-500 py-1">Introduction</a></li>
                <li><a href="#rate-limit" class="block text-slate-500 py-1">Rate Limiting</a></li>
                <li><a href="#how-to-use" class="block text-slate-500 py-1 text-blue-600 font-bold">Integrasi Key</a></li>
            </ul>
        </div>
        <div class="mb-10">
            <h3 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-[0.2em] mb-5">Endpoints v1</h3>
            <ul class="space-y-4 text-sm font-medium mobile-nav">
                <li><a href="#users" class="block text-slate-500 py-1">Get Users List</a></li>
                <li><a href="#prayer" class="block text-slate-500 py-1">Global Prayer Times</a></li>
            </ul>
        </div>
    </div>

    <div class="flex pt-20">
        <nav class="w-72 fixed h-[calc(100vh-80px)] border-r bg-white p-8 overflow-y-auto hidden lg:block">
            <div class="mb-10">
                <h3 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-[0.2em] mb-5">Dokumentasi</h3>
                <ul class="space-y-4 text-sm font-medium">
                    <li><a href="#intro" class="sidebar-link block text-slate-500 hover:text-blue-600 transition-all py-1">Introduction</a></li>
                    <li><a href="#rate-limit" class="sidebar-link block text-slate-500 hover:text-blue-600 transition-all py-1">Rate Limiting</a></li>
                    <li><a href="#how-to-use" class="sidebar-link block text-slate-500 hover:text-blue-600 transition-all py-1">Integrasi Key</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-[0.2em] mb-5">Endpoints v1</h3>
                <ul class="space-y-4 text-sm font-medium">
                    <li><a href="#users" class="sidebar-link block text-slate-500 hover:text-blue-600 transition-all py-1">Get Users List</a></li>
                    <li><a href="#prayer" class="sidebar-link block text-slate-500 hover:text-blue-600 transition-all py-1 font-bold text-blue-600 active">Global Prayer Times</a></li>
                </ul>
            </div>
        </nav>

        <main class="flex-1 lg:ml-72 p-4 md:p-12 lg:p-20 max-w-6xl overflow-hidden">
            
            <section id="intro" class="mb-16 md:mb-24">
                <div class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-[10px] md:text-[12px] font-bold mb-6">v1.0.0 Release</div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 md:mb-8 tracking-tighter text-slate-900 leading-tight">Bangun masa depan dengan <span class="text-blue-600">API Terpercaya.</span></h1>
                <p class="text-slate-500 leading-relaxed text-base md:text-xl max-w-3xl">
                    Dokumentasi resmi untuk integrasi Bian API. Kami menyediakan layanan data yang cepat, aman, dan skalabel mulai dari data developer hingga jadwal ibadah global yang telah disamarkan secara eksklusif.
                </p>
            </section>

            <section id="rate-limit" class="mb-16 md:mb-24 scroll-mt-28">
                <h2 class="text-xl md:text-2xl font-extrabold mb-8 flex items-center gap-3">
                    <span class="w-8 h-8 md:w-10 md:h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white text-sm"><i class="fas fa-bolt"></i></span>
                    Batas Penggunaan (Rate Limit)
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8">
                    <div class="bg-white p-6 md:p-8 rounded-[1.5rem] md:rounded-[2rem] border border-slate-200 shadow-sm">
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Public Access</p>
                        <p class="text-3xl md:text-4xl font-black text-slate-900 mb-2">5 <span class="text-xs font-medium text-slate-400">Req / Min</span></p>
                        <p class="text-[10px] md:text-xs text-slate-400 leading-relaxed italic">*IP Based Identification.</p>
                    </div>
                    <div class="bg-white p-6 md:p-8 rounded-[1.5rem] md:rounded-[2rem] border-2 border-blue-600 shadow-xl shadow-blue-500/5">
                        <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-widest mb-2">Member Access</p>
                        <p class="text-3xl md:text-4xl font-black text-slate-900 mb-2">100 <span class="text-sm font-medium text-slate-400">Req / Min</span></p>
                        <p class="text-[10px] md:text-xs text-blue-500 leading-relaxed italic font-medium">*Header X-BIAN-KEY Required.</p>
                    </div>
                </div>
            </section>

            <section id="how-to-use" class="mb-16 md:mb-24 scroll-mt-28">
                <h2 class="text-xl md:text-2xl font-extrabold mb-8 flex items-center gap-3 text-slate-900">
                    <span class="w-8 h-8 md:w-10 md:h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white text-sm"><i class="fas fa-key"></i></span>
                    Integrasi API Key
                </h2>
                <div class="space-y-10">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <i class="fab fa-node-js text-2xl text-green-600"></i>
                            <p class="text-xs font-bold text-slate-700 uppercase tracking-widest">Node.js Integration</p>
                        </div>
                        <div class="relative">
                            <button class="copy-btn" onclick="copyCode(this)"><i class="far fa-copy"></i> Copy</button>
<pre><code><span class="code-keyword">const</span> axios = <span class="code-keyword">require</span>(<span class="code-string">'axios'</span>);

axios.<span class="code-function">get</span>(<span class="code-string">'https://my-api-bian.absenps.com/v1/prayer-times?city=Jakarta'</span>, {
    <span class="code-attr">headers</span>: { <span class="code-string">'X-BIAN-KEY'</span>: <span class="code-string">'YOUR_KEY'</span> }
})
.<span class="code-function">then</span>(res => console.<span class="code-function">log</span>(res.data));</code></pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="prayer" class="mb-16 md:mb-24 scroll-mt-28 p-6 md:p-12 bg-white rounded-[1.5rem] md:rounded-[3rem] border border-slate-200 shadow-sm relative overflow-hidden">
                <div class="flex items-center gap-4 mb-6 md:mb-8">
                    <span class="bg-emerald-500 text-white px-3 py-1 md:px-4 md:py-1.5 rounded-xl text-[10px] md:text-[12px] font-black tracking-widest uppercase">Get</span>
                    <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Global Prayer Times</h2>
                </div>
                
                <p class="text-slate-500 mb-8 text-sm md:text-lg">Jadwal ibadah global yang telah ditransformasi khusus untuk pengguna BIAN API.</p>

                <div class="bg-slate-900 p-4 md:p-6 rounded-2xl mb-10 flex items-center justify-between border border-white/5 overflow-x-auto">
                    <code class="text-blue-400 font-medium text-xs md:text-sm whitespace-nowrap">/v1/prayer-times?city=Jakarta&country=Indonesia</code>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12">
                    <div>
                        <h3 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-6">Bian Format Response</h3>
                        <div class="relative">
                            <button class="copy-btn" onclick="copyCode(this)"><i class="far fa-copy"></i> Copy</button>
<pre><code>{
  <span class="code-attr">"status"</span>: <span class="code-keyword">200</span>,
  <span class="code-attr">"creator"</span>: <span class="code-string">"BIAN STUDIO"</span>,
  <span class="code-attr">"result"</span>: {
    <span class="code-attr">"info_lokasi"</span>: {
      <span class="code-attr">"nama_kota"</span>: <span class="code-string">"JAKARTA"</span>,
      <span class="code-attr">"wilayah"</span>: <span class="code-string">"Indonesia"</span>
    },
    <span class="code-attr">"jadwal"</span>: {
      <span class="code-attr">"subuh"</span>: <span class="code-string">"04:12"</span>,
      <span class="code-attr">"dzuhur"</span>: <span class="code-string">"11:51"</span>,
      <span class="code-attr">"ashar"</span>: <span class="code-string">"15:15"</span>,
      <span class="code-attr">"maghrib"</span>: <span class="code-string">"18:05"</span>,
      <span class="code-attr">"isya"</span>: <span class="code-string">"19:18"</span>
    }
  }
}</code></pre>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-6">Query Parameters</h3>
                        <div class="space-y-4">
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <p class="text-sm font-bold text-blue-600 mb-1 font-mono">city <span class="text-red-500 text-[10px] ml-1">REQUIRED</span></p>
                                <p class="text-[11px] md:text-xs text-slate-500 leading-relaxed">Nama kota di seluruh dunia (Contoh: London, Tokyo, Mecca).</p>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <p class="text-sm font-bold text-blue-600 mb-1 font-mono">country <span class="text-slate-400 text-[10px] ml-1 font-normal uppercase">Optional</span></p>
                                <p class="text-[11px] md:text-xs text-slate-500 leading-relaxed">Nama negara. Default: Indonesia.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <footer class="bg-white border-t py-12 md:py-20 lg:ml-72">
        <div class="max-w-6xl mx-auto px-8 flex flex-col items-center">
            <div class="flex gap-6 mb-8">
                <a href="#" class="text-slate-300 hover:text-blue-600 transition text-2xl"><i class="fab fa-github"></i></a>
                <a href="#" class="text-slate-300 hover:text-blue-600 transition text-2xl"><i class="fab fa-discord"></i></a>
            </div>
            <p class="text-slate-400 text-[10px] font-extrabold uppercase tracking-[0.3em] text-center">&copy; 2024 BianDev Studio • Jakarta, Indonesia</p>
        </div>
    </footer>

    <script>
        // Copy Code Function
        function copyCode(btn) {
            const code = btn.parentElement.querySelector('code').innerText;
            navigator.clipboard.writeText(code).then(() => {
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                btn.style.color = '#10b981';
                
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.style.color = '#8b949e';
                }, 2000);
                Toast.fire({ icon: 'success', title: 'Code copied!' });
            });
        }

        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 1500,
            timerProgressBar: true, background: '#0d1117', color: '#fff'
        });

        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menu-toggle');
        const body = document.body;
        menuToggle.addEventListener('click', () => {
            body.classList.toggle('menu-open');
            const icon = menuToggle.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        });

        // Close mobile menu on link click
        document.querySelectorAll('.mobile-nav a').forEach(link => {
            link.addEventListener('click', () => {
                body.classList.remove('menu-open');
                menuToggle.querySelector('i').classList.add('fa-bars');
                menuToggle.querySelector('i').classList.remove('fa-times');
            });
        });

        // Smooth Scroll Sidebar
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if(target) {
                    window.scrollTo({
                        top: target.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>