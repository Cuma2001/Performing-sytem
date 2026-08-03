<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-red: #e5222b;
            --primary-teal: #1d6988;
            --text: #1e2f3f;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f7f9fc 0%, #eef3f7 100%);
            color: var(--text);
        }

        .page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            width: 100%;
            max-width: 520px;
            background: white;
            border-radius: 24px;
            padding: 36px 32px;
            box-shadow: 0 18px 45px rgba(15, 30, 44, 0.08);
            text-align: center;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 84px;
            height: 84px;
            border-radius: 999px;
            background: rgba(229, 34, 43, 0.08);
            color: var(--primary-red);
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 16px;
        }

        h1 {
            font-size: 1.9rem;
            margin: 0 0 10px;
            color: var(--primary-teal);
        }

        p {
            margin: 0 0 18px;
            line-height: 1.6;
            color: #5b6b79;
        }

        .btn {
            display: inline-block;
            padding: 12px 18px;
            border-radius: 999px;
            background: var(--primary-red);
            color: white;
            text-decoration: none;
            font-weight: 600;
        }

        .btn:hover {
            opacity: 0.92;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="card">
            <div class="badge">404</div>
            <h1>Page not found</h1>
            <p>The page you are trying to open does not exist or may have been moved. Please return to the dashboard and continue from there.</p>
            <a class="btn" href="/dashboard">Go to dashboard</a>
        </div>
    </div>
</body>
</html>
