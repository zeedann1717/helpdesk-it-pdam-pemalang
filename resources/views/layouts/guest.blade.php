<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Help Desk IT') - PDAM Tirta Mulia Pemalang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #0d6efd 0%, #084298 100%);
        }
        .guest-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 45px rgba(0,0,0,.25);
        }
        .brand-logo {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #0d6efd;
            margin: 0 auto 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-9 col-md-7 col-lg-5">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
