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
        
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        code, pre { font-family: 'JetBrains Mono', monospace; }

        /* Syntax Highlighting Colors */
        pre { background: #0d1117; color: #c9d1d9; padding: 1.5rem; border-radius: 1rem; overflow-x: auto; position: relative; border: 1px solid rgba(255,255,255,0.1); }
        .code-keyword { color: #ff7b72; } /* Red */
        .code-string { color: #a5d6ff; }  /* Blue */
        .code-comment { color: #8b949e; } /* Gray */
        .code-variable { color: #d2a8ff; } /* Purple */
        .code-attr { color: #ffa657; }     /* Orange */
        .code-function { color: #79c0ff; } /* Light Blue */

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
        }
        .copy-btn:hover { background: rgba(255,255,255,0.1); color: #fff; border-color: rgba(255,255,255,0.3); }
        .copy-btn:active { transform: scale(0.95); }

        .sidebar-link.active { color: #2563eb; font-weight: 700; border-left: 3px solid #2563eb; padding-left: 1rem; background: linear-gradient(90deg, rgba(37,99,235,0.05) 0%, transparent 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(0,0,0,0.05); }
        
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

    <header class="fixed w-full bg-white/80 backdrop-blur-md border-b z-50 px-8 py-4 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold italic shadow-lg shadow-blue-500/30">B</div>
            <div class="font-extrabold text-xl tracking-tighter">BIAN <span class="text-blue-600 italic font-medium">API DOCS</span></div>
        </div>
        <div class="flex gap-4">
            <a href="/" class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition py-2">Home</a>
            @if($user)
                <a href="/dashboard" class="bg-blue-600 text-white px-6 py-2 rounded-xl text-sm font-bold shadow-xl shadow-blue-500/20 hover:bg-blue-700 hover:-translate-y-0.5 transition-all">Dashboard</a>
            @else
                <a href="/v1/login-page" class="border-2 border-slate-200 text-slate-700 px-6 py-2 rounded-xl text-sm font-bold hover:border-blue-600 hover:text-blue-600 transition-all">Login</a>
            @endif
        </div>
    </header>

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
                    <li><a href="#prayer" class="sidebar-link block text-slate-500 hover:text-blue-600 transition-all py-1">Global Prayer Times</a></li>
                </ul>
            </div>
        </nav>

        <main class="flex-1 lg:ml-72 p-8 lg:p-20 max-w-6xl">
            
            <section id="intro" class="mb-24">
                <div class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-[12px] font-bold mb-6">v1.0.0 Release</div>
                <h1 class="text-6xl font-extrabold mb-8 tracking-tighter text-slate-900">Bangun masa depan dengan <span class="text-blue-600">API Terpercaya.</span></h1>
                <p class="text-slate-500 leading-relaxed text-xl max-w-3xl">
                    Dokumentasi resmi untuk integrasi Bian API. Kami menyediakan layanan data yang cepat, aman, dan skalabel mulai dari data developer hingga jadwal ibadah global.
                </p>
            </section>

            <section id="rate-limit" class="mb-24 scroll-mt-28">
                <h2 class="text-2xl font-extrabold mb-8 flex items-center gap-3">
                    <span class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white text-sm"><i class="fas fa-bolt"></i></span>
                    Batas Penggunaan (Rate Limit)
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm transition-all hover:shadow-md">
                        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 mb-6"><i class="fas fa-globe text-xl"></i></div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Public Access</p>
                        <p class="text-4xl font-black text-slate-900 mb-2">5 <span class="text-sm font-medium text-slate-400">Req / Min</span></p>
                        <p class="text-xs text-slate-400 leading-relaxed italic">*Identifikasi otomatis melalui IP Address.</p>
                    </div>
                    <div class="bg-white p-8 rounded-[2rem] border-2 border-blue-600 shadow-xl shadow-blue-500/5 transition-all hover:-translate-y-1">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6"><i class="fas fa-crown text-xl"></i></div>
                        <p class="text-[11px] font-extrabold text-blue-600 uppercase tracking-widest mb-2">Member Access</p>
                        <p class="text-4xl font-black text-slate-900 mb-2">100 <span class="text-sm font-medium text-slate-400">Req / Min</span></p>
                        <p class="text-xs text-blue-500 leading-relaxed italic font-medium">*Wajib menyertakan Header X-BIAN-KEY.</p>
                    </div>
                </div>
            </section>

            <section id="how-to-use" class="mb-24 scroll-mt-28">
                <h2 class="text-2xl font-extrabold mb-8 flex items-center gap-3 text-slate-900">
                    <span class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white text-sm"><i class="fas fa-key"></i></span>
                    Integrasi API Key
                </h2>
                <p class="text-slate-500 mb-10 text-lg">Gunakan Custom Header untuk otentikasi aman dan akses limit premium.</p>

                <div class="space-y-12">
                    <div class="group">
                        <div class="flex items-center gap-3 mb-4">
                            <i class="fab fa-node-js text-2xl text-green-600"></i>
                            <p class="text-sm font-bold text-slate-700 uppercase tracking-widest">Node.js Integration</p>
                        </div>
                        <div class="relative">
                            <button class="copy-btn" onclick="copyCode(this)"><i class="far fa-copy"></i> Copy Code</button>
<pre><code><span class="code-keyword">const</span> axios = <span class="code-keyword">require</span>(<span class="code-string">'axios'</span>);

<span class="code-comment">//</span>
axios.<span class="code-function">get</span>(<span class="code-string">'https://my-api-bian.absenps.com/v1/prayer-times?city=Jakarta'</span>, {
    <span class="code-attr">headers</span>: {
        <span class="code-string">'X-BIAN-KEY'</span>: <span class="code-string">'YOUR_API_KEY_HERE'</span>
    }
})
.<span class="code-function">then</span>(response => {
    console.<span class="code-function">log</span>(response.data);
})
.<span class="code-function">catch</span>(error => {
    console.<span class="code-function">error</span>(error.response.data);
});</code></pre>
                        </div>
                    </div>

                    <div class="group">
                        <div class="flex items-center gap-3 mb-4">
                            <i class="fab fa-php text-2xl text-indigo-600"></i>
                            <p class="text-sm font-bold text-slate-700 uppercase tracking-widest">PHP cURL Implementation</p>
                        </div>
                        <div class="relative">
                            <button class="copy-btn" onclick="copyCode(this)"><i class="far fa-copy"></i> Copy Code</button>
<pre><code><span class="code-comment">//</span>
<span class="code-variable">$apiKey</span> = <span class="code-string">"YOUR_API_KEY_HERE"</span>;
<span class="code-variable">$ch</span> = <span class="code-function">curl_init</span>(<span class="code-string">"https://my-api-bian.absenps.com/v1/prayer-times?city=Jakarta"</span>);

<span class="code-function">curl_setopt</span>(<span class="code-variable">$ch</span>, CURLOPT_HTTPHEADER, [
    <span class="code-string">"X-BIAN-KEY: <span class="code-variable">$apiKey</span>"</span>,
    <span class="code-string">"Content-Type: application/json"</span>
]);
<span class="code-function">curl_setopt</span>(<span class="code-variable">$ch</span>, CURLOPT_RETURNTRANSFER, <span class="code-keyword">true</span>);

<span class="code-variable">$response</span> = <span class="code-function">curl_exec</span>(<span class="code-variable">$ch</span>);
<span class="code-function">curl_close</span>(<span class="code-variable">$ch</span>);

<span class="code-function">echo</span> <span class="code-variable">$response</span>;</code></pre>
                        </div>
                    </div>
                </div>
            </section>

            <hr class="mb-24 border-slate-200">

            <section id="prayer" class="mb-24 scroll-mt-28 p-12 bg-white rounded-[3rem] border border-slate-200 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                
                <div class="flex items-center gap-4 mb-8">
                    <span class="bg-emerald-500 text-white px-4 py-1.5 rounded-xl text-[12px] font-black tracking-widest">GET</span>
                    <h2 class="text-3xl font-extrabold tracking-tight">Global Prayer Times</h2>
                </div>
                
                <p class="text-slate-500 mb-10 text-lg leading-relaxed">Dapatkan waktu ibadah akurat untuk ribuan kota di seluruh dunia dengan integrasi database Muslim World League.</p>

                <div class="bg-slate-900 p-6 rounded-2xl mb-12 flex items-center justify-between border border-white/5 group">
                    <code class="text-blue-400 font-medium text-sm">https://my-api-bian.absenps.com/v1/prayer-times?city=Samarinda&country=Indonesia</code>
                    <button onclick="copyRaw(this, 'https://my-api-bian.absenps.com/v1/prayer-times?city=Samarinda&country=Indonesia')" class="text-slate-500 hover:text-white transition"><i class="far fa-copy"></i></button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <div>
                        <h3 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-6">Response JSON</h3>
                        <div class="relative">
                            <button class="copy-btn" onclick="copyCode(this)"><i class="far fa-copy"></i> Copy</button>
<pre><code>{
  <span class="code-attr">"status"</span>: <span class="code-string">"success"</span>,
  <span class="code-attr">"location"</span>: {
    <span class="code-attr">"city"</span>: <span class="code-string">"Samarinda"</span>,
    <span class="code-attr">"country"</span>: <span class="code-string">"Indonesia"</span>
  },
  <span class="code-attr">"timings"</span>: {
    <span class="code-attr">"Fajr"</span>: <span class="code-string">"04:45"</span>,
    <span class="code-attr">"Dhuhr"</span>: <span class="code-string">"12:12"</span>,
    <span class="code-attr">"Maghrib"</span>: <span class="code-string">"18:18"</span>
  },
  <span class="code-attr">"limit_info"</span>: <span class="code-string">"100/min"</span>
}</code></pre>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-6">Parameters</h3>
                        <div class="space-y-4">
                            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                                <p class="text-sm font-bold text-blue-600 mb-1 font-mono">city <span class="text-red-500 text-[10px] ml-1">REQUIRED</span></p>
                                <p class="text-xs text-slate-500 leading-relaxed">Nama kota yang ingin dicari (Contoh: London, Jakarta, Samarinda).</p>
                            </div>
                            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                                <p class="text-sm font-bold text-blue-600 mb-1 font-mono">country <span class="text-slate-400 text-[10px] ml-1 font-normal">OPTIONAL</span></p>
                                <p class="text-xs text-slate-500 leading-relaxed">Nama negara (Contoh: UK, Indonesia). Default: Indonesia.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <footer class="bg-white border-t py-20 lg:ml-72">
        <div class="max-w-6xl mx-auto px-8 flex flex-col items-center">
            <div class="flex gap-8 mb-10">
                <a href="#" class="text-slate-400 hover:text-blue-600 transition text-2xl"><i class="fab fa-github"></i></a>
                <a href="#" class="text-slate-400 hover:text-blue-600 transition text-2xl"><i class="fab fa-discord"></i></a>
                <a href="#" class="text-slate-400 hover:text-blue-600 transition text-2xl"><i class="fab fa-instagram"></i></a>
            </div>
            <p class="text-slate-400 text-[11px] font-extrabold uppercase tracking-[0.3em]">&copy; 2024 BianDev Studio • Built with Laravel 10</p>
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
                btn.style.borderColor = '#10b981';
                
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.style.color = '#8b949e';
                    btn.style.borderColor = 'rgba(255,255,255,0.1)';
                }, 2000);

                Toast.fire({ icon: 'success', title: 'Code copied to clipboard!' });
            });
        }

        function copyRaw(btn, text) {
            navigator.clipboard.writeText(text).then(() => {
                Toast.fire({ icon: 'success', title: 'URL copied!' });
            });
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            background: '#0d1117',
            color: '#fff'
        });

        // Sidebar active link monitoring
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.sidebar-link');
            
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 150) {
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

        document.querySelectorAll('.sidebar-link').forEach(anchor => {
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