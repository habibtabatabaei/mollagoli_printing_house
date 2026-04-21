// public/index.php
<?php
session_start(); // این خط حتما باید اولین دستور اجرایی باشد


// فعال‌سازی نمایش خطاها (در زمان توسعه)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// بارگذاری کلاس‌های پایه
require_once '../app/Core/Database.php';
require_once '../app/Core/Router.php';

// ایجاد نمونه از روتر
$router = new Router();

// ==========================================
// تعریف مسیرهای سایت (Routes)
// ==========================================

// مسیرهای بخش کاربری (Front)
$router->get('/', 'PageController', 'home'); // صفحه اصلی
$router->get('/calculator', 'PageController', 'calculator'); // صفحه ماشین‌حساب

// مسیرهای API (ارتباط با جاوا اسکریپت)
$router->post('/api/calculate', 'OrderController', 'calculatePrice'); // محاسبه زنده قیمت
$router->post('/api/checkout', 'OrderController', 'submitOrder'); // ثبت نهایی سفارش

// مسیرهای پنل مدیریت (Admin)
$router->get('/admin/dashboard', 'AdminController', 'index');

// ==========================================
// اجرای روتر
// ==========================================

// دریافت آدرس درخواستی مرورگر (مثلاً /calculator)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// اگر پروژه در یک ساب‌فولدر است، نام پوشه را از آدرس حذف می‌کنیم (در سرور واقعی نیاز نیست)
$baseFolder = '/mollagoli-print/public'; 
$uri = str_replace($baseFolder, '', $uri);
if ($uri == '') $uri = '/';

// دریافت نوع درخواست (GET یا POST)
$method = $_SERVER['REQUEST_METHOD'];

// هدایت درخواست
$router->dispatch($uri, $method);

// مسیرهای پنل مدیریت (Admin)
$router->get('/admin/login', 'AdminController', 'login');         // فرم لاگین
$router->post('/admin/authenticate', 'AdminController', 'authenticate'); // پردازش فرم
$router->get('/admin/logout', 'AdminController', 'logout');       // خروج
$router->get('/admin/dashboard', 'AdminController', 'index');     // پیشخوان اصلی