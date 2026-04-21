<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت | خانه چاپ ملاگلی</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <style>
        /* استایل‌های اختصاصی و مینیمال برای داشبورد ادمین */
        body { background-color: #f1f3f5; }
        .admin-header {
            background: #fff;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .admin-header h1 { font-size: 1.5rem; color: #2d3436; }
        .admin-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        
        .table-wrapper {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.02);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: right;
        }
        th { background: #f8f9fa; padding: 15px 20px; font-weight: 600; color: #636e72; border-bottom: 2px solid #dfe6e9;}
        td { padding: 15px 20px; border-bottom: 1px solid #f1f3f5; color: #2d3436; font-size: 0.95rem; }
        tr:hover { background-color: #fafbfc; }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .status-pending { background: #fff3e0; color: #e65c00; } /* نارنجی برای در انتظار */
        .status-printing { background: #e3f2fd; color: #1976d2; }
        .status-ready { background: #e8f5e9; color: #2e7d32; }
        
        .code-box { font-family: monospace; background: #f1f3f5; padding: 4px 8px; border-radius: 4px; letter-spacing: 1px; }
    </style>
</head>
<body>

    <header class="admin-header">
        <h1>پیشخوان مدیریت خانه چاپ</h1>
        <a href="/" style="color: var(--primary-color); text-decoration: none; font-weight: bold;">بازگشت به سایت</a>
    </header>

    <main class="admin-container">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>کد پیگیری</th>
                        <th>ابعاد (ط×ع×ا)</th>
                        <th>جنس متریال</th>
                        <th>تیراژ</th>
                        <th>مبلغ کل (تومان)</th>
                        <th>وضعیت</th>
                        <th>تاریخ ثبت</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($orders)): ?>
                        <tr><td colspan="7" style="text-align: center; padding: 40px;">هیچ سفارشی تاکنون ثبت نشده است.</td></tr>
                    <?php else: ?>
                        <?php foreach($orders as $order): ?>
                            <tr>
                                <td><span class="code-box"><?= htmlspecialchars($order['tracking_code']) ?></span></td>
                                <td dir="ltr" style="text-align: right;">
                                    <?= floatval($order['length']) ?> × <?= floatval($order['width']) ?> × <?= floatval($order['height']) ?>
                                </td>
                                <td><?= htmlspecialchars($order['material_name']) ?></td>
                                <td><?= number_format($order['quantity']) ?></td>
                                <td><strong style="color: var(--primary-color);"><?= number_format($order['total_price']) ?></strong></td>
                                <td>
                                    <?php 
                                        // نمایش وضعیت با رنگ‌های مختلف
                                        $statusClass = 'status-pending';
                                        $statusText = 'در انتظار تایید';
                                        if($order['status'] == 'printing') { $statusClass = 'status-printing'; $statusText = 'در حال چاپ'; }
                                        if($order['status'] == 'ready') { $statusClass = 'status-ready'; $statusText = 'آماده تحویل'; }
                                    ?>
                                    <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                                <td>
                                    <span style="font-size: 0.85rem; color: #636e72;">
                                        <?= date('Y-m-d H:i', strtotime($order['created_at'])) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>