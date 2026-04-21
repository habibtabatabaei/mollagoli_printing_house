<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به مدیریت | خانه چاپ ملاگلی</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <style>
        body { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            background-color: #f1f3f5; 
        }
        .login-card {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-card h2 { margin-bottom: 30px; color: #2d3436; }
        .error-msg {
            background: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>ورود به پنل ادمین</h2>
        
        <?php if(isset($_SESSION['login_error'])): ?>
            <div class="error-msg"><?= $_SESSION['login_error'] ?></div>
            <?php unset($_SESSION['login_error']); // پاک کردن خطا بعد از نمایش ?>
        <?php endif; ?>

        <form action="/admin/authenticate" method="POST">
            <div class="form-group">
                <input type="text" name="username" placeholder="نام کاربری" required style="text-align: left; direction: ltr;">
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="رمز عبور" required style="text-align: left; direction: ltr;">
            </div>
            <button type="submit" class="btn-submit">ورود</button>
        </form>
    </div>

</body>
</html>