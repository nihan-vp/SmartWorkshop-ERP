<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline — Suhaim Soft</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .card {
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            max-width: 480px;
            border: 1px solid #e2e8f0;
            width: 100%;
        }
        .icon {
            background: #fee2e2;
            color: #ef4444;
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
        }
        h1 {
            font-size: 24px;
            margin: 0 0 10px 0;
            color: #0f172a;
        }
        p {
            font-size: 15px;
            color: #64748b;
            line-height: 1.6;
            margin: 0 0 24px 0;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #2563eb;
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            font-size: 15px;
            transition: background 0.2s;
            width: 100%;
            box-sizing: border-box;
        }
        .btn:hover {
            background: #1d4ed8;
        }
        .help-box {
            background: #f1f5f9;
            padding: 16px;
            border-radius: 12px;
            text-align: left;
            margin-bottom: 24px;
        }
        .help-box h3 {
            font-size: 13px;
            text-transform: uppercase;
            color: #475569;
            margin: 0 0 8px 0;
            letter-spacing: 0.05em;
        }
        .help-box ul {
            margin: 0;
            padding-left: 20px;
            color: #475569;
            font-size: 14px;
        }
        .help-box li {
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.163a1.5 1.5 0 112.121 2.121m-6.586-6.586l9 9M3 3l18 18"/>
            </svg>
        </div>
        <h1>You are Offline</h1>
        <p>Please check your internet connection and try again.</p>

        <a href="javascript:window.location.reload()" class="btn">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Retry Connection
        </a>
    </div>
</body>
</html>
<?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\errors\offline.blade.php ENDPATH**/ ?>