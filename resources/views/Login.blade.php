<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Inventaris Barang</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f4f9; }
        .login-card { background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 350px; }
        .login-card h2 { margin-bottom: 20px; text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #666; font-size: 14px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        button { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; transition: background 0.3s; }
        button:hover { background-color: #218838; }
        #message { margin-top: 15px; font-size: 14px; text-align: center; min-height: 20px; }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Login Inventaris</h2>
    <form id="loginForm">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" placeholder="Masukkan email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" placeholder="Masukkan password" required>
        </div>
        <button type="submit" id="submitBtn">Masuk</button>
    </form>
    <div id="message"></div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const messageDiv = document.getElementById('message');
        const submitBtn = document.getElementById('submitBtn');

        messageDiv.style.color = '#333';
        messageDiv.innerText = 'Memproses...';
        submitBtn.disabled = true;

        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (response.ok) {
                messageDiv.style.color = 'green';
                messageDiv.innerText = data.message;
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1000);
            } else {
                messageDiv.style.color = 'red';
                messageDiv.innerText = data.message || 'Login gagal!';
                submitBtn.disabled = false;
            }
        } catch (error) {
            messageDiv.style.color = 'red';
            messageDiv.innerText = 'Terjadi kesalahan koneksi server.';
            submitBtn.disabled = false;
        }
    });
</script>

</body>
</html>