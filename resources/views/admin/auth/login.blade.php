<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Staff Authentication | Hasnain Al Ifan</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: radial-gradient(circle at 50% 20%, #172554 0%, #0B1120 70%);
            padding: 20px;
        }
        .login-box {
            width: 100%;
            max-width: 420px;
            background: rgba(17, 24, 39, 0.9);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 36px 32px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.6);
            backdrop-filter: blur(20px);
        }
        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .login-brand {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }
        .login-subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        .login-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 0.78rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="login-header">
            <div style="display:flex; justify-content:center; margin-bottom:12px;">
                <span class="brand-avatar-frame" style="width:48px; height:48px; border:2px solid var(--accent-blue); border-radius:50%; overflow:hidden; display:inline-block;">
                    <img src="{{ asset('images/profile.jpg') }}" alt="Hasnain Al Ifan" style="width:100%; height:100%; object-fit:cover;">
                </span>
            </div>
            <div class="login-brand">IFAN<span style="color:var(--accent-blue);">.</span> PORTAL</div>
            <p class="login-subtitle">Secure administrative authentication</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success" style="font-size:0.85rem; padding:10px 14px;">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="font-size:0.85rem; padding:10px 14px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Authorized Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="admin@example.com">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password" placeholder="••••••••••••">
            </div>

            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; font-size:0.85rem;">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; color:var(--text-secondary);">
                    <input type="checkbox" name="remember" value="1" style="accent-color:var(--accent-blue);">
                    <span>Remember this session</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">
                <span>Sign In Securely</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </button>
        </form>

        <div class="login-footer">
            <a href="{{ route('home') }}" style="color:var(--text-muted); text-decoration:none;">&larr; Return to Public Portfolio</a>
        </div>
    </div>
</body>
</html>
