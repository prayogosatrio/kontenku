{{-- Halaman mandiri (tidak memakai layouts.app): navbar hanya relevan untuk user yang sudah login --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - KontenKu</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 0; background: #eef2ff; display: flex; align-items: center; justify-content: center; min-height: 100vh; color: #1f2937; }
        .login-box { background: #fff; padding: 32px; border-radius: 10px; box-shadow: 0 4px 16px rgba(49, 46, 129, .12); width: 100%; max-width: 380px; }
        .login-box h1 { font-size: 22px; margin: 0; text-align: center; color: #312e81; }
        .login-box p.tagline { text-align: center; color: #6b7280; font-size: 14px; margin: 4px 0 20px; }
        label { display: block; margin-top: 12px; font-weight: bold; font-size: 14px; }
        input[type="email"], input[type="password"] { width: 100%; padding: 9px; margin-top: 4px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
        .error { color: #b91c1c; font-size: 13px; margin-top: 4px; }
        .alert-success { background: #d1fae5; color: #065f46; padding: 10px 14px; border-radius: 6px; margin-bottom: 12px; font-size: 14px; }
        .checkbox-row { display: flex; align-items: center; gap: 6px; margin-top: 14px; }
        .checkbox-row label { display: inline; margin-top: 0; font-weight: normal; }
        .btn { margin-top: 20px; width: 100%; padding: 10px; background: #4f46e5; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; }
        .btn:hover { background: #4338ca; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>🗓️ KontenKu</h1>
        <p class="tagline">Sistem Manajemen Kalender Konten Media Sosial</p>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" autofocus>
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror

            <div class="checkbox-row">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>
