<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PDAM Monitor</title>
    <style>
        /* CSS Variables (Sama dengan App) */
        :root {
            --bg-primary: #f4f7f6;
            --bg-secondary: #ffffff;
            --text-primary: #333333;
            --text-secondary: #666666;
            --primary-color: #007bff;
            --primary-hover: #0056b3;
            --border-color: #ddd;
            --error-color: #dc3545;
        }

        /* Dark Mode Support (Optional, jika sistem OS dark mode) */
        @media (prefers-color-scheme: dark) {
            :root {
                --bg-primary: #1a202c;
                --bg-secondary: #2d3748;
                --text-primary: #e2e8f0;
                --text-secondary: #a0aec0;
                --border-color: #4a5568;
            }
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            background-color: var(--bg-secondary);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            border: 1px solid var(--border-color);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
            display: inline-block;
        }
        
        .logo span { color: var(--text-primary); }

        h2 { margin: 0 0 20px; font-size: 1.5rem; color: var(--text-primary); }
        p { margin-bottom: 30px; color: var(--text-secondary); font-size: 0.9rem; }

        .form-group { margin-bottom: 20px; text-align: left; }
        label { display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; }
        
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-login:hover { background-color: var(--primary-hover); }

        .error-msg {
            color: var(--error-color);
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        .footer-links {
            margin-top: 20px;
            font-size: 0.9rem;
        }
        .footer-links a { color: var(--primary-color); text-decoration: none; }
        .footer-links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>