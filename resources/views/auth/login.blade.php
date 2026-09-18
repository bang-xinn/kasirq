<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — KasirQ</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0f1117;
            color: #f0f2ff;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
            position: relative; overflow: hidden;
        }
        body::before {
            content: ''; position: fixed; top: -200px; left: -200px;
            width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,0.15), transparent 70%);
            pointer-events: none;
        }
        body::after {
            content: ''; position: fixed; bottom: -200px; right: -200px;
            width: 500px; height: 500px; border-radius: 50%;
            background: radial-gradient(circle, rgba(139,92,246,0.1), transparent 70%);
            pointer-events: none;
        }
        .login-card {
            background: #1a1d27;
            border: 1px solid #2d3150;
            border-radius: 24px; padding: 40px;
            width: 100%; max-width: 420px;
            position: relative; z-index: 1;
            box-shadow: 0 25px 60px rgba(0,0,0,.5);
        }
        .brand {
            text-align: center; margin-bottom: 32px;
        }
        .brand-icon {
            width: 56px; height: 56px; border-radius: 16px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; font-weight: 800; color: #fff;
            margin: 0 auto 16px;
            box-shadow: 0 8px 24px rgba(99,102,241,0.4);
        }
        .brand h1 {
            font-size: 24px; font-weight: 800;
            background: linear-gradient(135deg, #e0e0ff, #a5b4fc);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .brand p { font-size: 13px; color: #8b92b8; margin-top: 4px; }

        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 500; color: #8b92b8; margin-bottom: 6px; }
        .form-control {
            width: 100%; padding: 12px 16px; border-radius: 12px;
            background: #252840; border: 1px solid #2d3150;
            color: #f0f2ff; font-size: 14px; font-family: inherit;
            outline: none; transition: all .2s;
        }
        .form-control:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.2); }
        .form-control::placeholder { color: #555e8a; }

        .remember-row {
            display: flex; align-items: center; gap: 8px; margin-bottom: 24px;
        }
        .remember-row input { accent-color: #6366f1; width: 16px; height: 16px; cursor: pointer; }
        .remember-row label { font-size: 13px; color: #8b92b8; cursor: pointer; }

        .btn-login {
            width: 100%; padding: 13px; border-radius: 12px; border: none;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff; font-size: 15px; font-weight: 600; font-family: inherit;
            cursor: pointer; transition: all .2s;
            box-shadow: 0 4px 15px rgba(99,102,241,0.4);
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(99,102,241,0.5);
        }
        .btn-login:active { transform: translateY(0); }

        .error-message {
            background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.2);
            color: #f87171; padding: 10px 14px; border-radius: 10px;
            font-size: 13px; margin-bottom: 18px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <div class="brand-icon">K</div>
            <h1>KasirQ</h1>
            <p>Masuk untuk melanjutkan</p>
        </div>

        @if($errors->any())
            <div class="error-message">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="admin@kasirq.com" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="remember-row">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Ingat saya</label>
            </div>
            <button type="submit" class="btn-login">Masuk →</button>
        </form>
    </div>
</body>
</html>
