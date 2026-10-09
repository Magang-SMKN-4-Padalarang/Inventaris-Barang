<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Inventaris Barang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f9;
        }

        .login-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            max-width: 100%;
        }

        .login-card h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .form-label {
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Login Inventaris</h2>

    <form action="{{ route('login.process') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="username" class="form-label text-secondary">
                Username
            </label>

            <input
                type="text"
                class="form-control @error('username') is-invalid @enderror"
                id="username"
                name="username"
                placeholder="Masukkan username"
                value="{{ old('username') }}"
                required
                autofocus
            >

            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label text-secondary">
                Password
            </label>

            <input
                type="password"
                class="form-control @error('password') is-invalid @enderror"
                id="password"
                name="password"
                placeholder="Masukkan password"
                required
            >

            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        @if (session('login_error'))
            <div class="alert alert-danger py-2 small" role="alert">
                {{ session('login_error') }}
            </div>
        @endif

        <button type="submit" class="btn btn-success w-100">
            Masuk
        </button>
    </form>
</div>

</body>
</html>