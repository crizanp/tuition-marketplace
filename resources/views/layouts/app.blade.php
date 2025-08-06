<!DOCTYPE html>
<html>
<head>
    <title>Tuition Marketplace</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f5f5f5; }
        .container { max-width: 400px; margin: 0 auto 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
        button { background: #007bff; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; width: 100%; }
        button:hover { background: #0056b3; }
        .error { color: red; font-size: 12px; margin-top: 5px; }
        .links { text-align: center; margin-top: 20px; }
        .links a { color: #007bff; text-decoration: none; }
        .dashboard { max-width: 800px; margin: 20px auto; background: white; padding: 30px; border-radius: 8px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .logout-btn { background: #dc3545; padding: 8px 16px; font-size: 14px; width: auto; }
        
        /* Main content wrapper */
        .main-content {
            padding: 20px;
        }
    </style>
</head>
<body>
    @include('partials.header')
    
    <div class="main-content">
        @yield('content')
    </div>
</body>
</html>