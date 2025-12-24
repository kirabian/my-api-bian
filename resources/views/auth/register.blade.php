<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - API Dashboard 💕</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FFD1DC] flex items-center justify-center min-h-screen">
    <div class="bg-white p-10 rounded-[40px] shadow-2xl w-full max-w-sm text-center">
        <h2 class="text-[#FF69B4] text-3xl font-black mb-6">Register 💕</h2>
        <div id="message" class="hidden mb-4 p-3 rounded-xl text-sm"></div>
        
        <form id="regForm" class="space-y-4">
            <input type="text" name="username" placeholder="Username Baru" required 
                class="w-full p-4 rounded-2xl border-2 border-pink-100 outline-none focus:border-[#FF69B4] transition">
            <input type="password" name="password" placeholder="Password Baru" required 
                class="w-full p-4 rounded-2xl border-2 border-pink-100 outline-none focus:border-[#FF69B4] transition">
            <button type="submit" 
                class="w-full p-4 bg-[#FF69B4] text-white font-bold rounded-2xl hover:bg-[#F6418C] shadow-lg shadow-pink-200 transition">
                DAFTAR SEKARANG
            </button>
        </form>
        <p class="mt-6 text-gray-400 text-sm">Sudah punya akun? <a href="/v1/login-page" class="text-[#FF69B4] font-bold">Login</a></p>
    </div>

    <script>
        document.getElementById('regForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const msgDiv = document.getElementById('message');
            
            const response = await fetch('/v1/register', {
                method: 'POST',
                body: JSON.stringify(Object.fromEntries(formData)),
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
            });

            const result = await response.json();
            if (response.ok) {
                msgDiv.className = "mb-4 p-3 rounded-xl text-sm bg-green-100 text-green-600 block";
                msgDiv.innerText = "Pendaftaran berhasil! Silakan login.";
            } else {
                msgDiv.className = "mb-4 p-3 rounded-xl text-sm bg-red-100 text-red-600 block";
                msgDiv.innerText = result.message || "Username sudah terdaftar.";
            }
        });
    </script>
</body>
</html>