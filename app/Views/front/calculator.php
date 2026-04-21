<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خانه چاپ ملاگلی | سفارش آنلاین بسته‌بندی</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>

    <header class="site-header">
        <div class="container">
            <h1 class="logo">خانه چاپ <span>ملاگلی</span></h1>
            <p>سیستم هوشمند محاسبه و ثبت سفارش</p>
        </div>
    </header>

    <main class="container">
        <section class="calculator-wrapper">
            <div class="calculator-card">
                <h2>استعلام قیمت و ثبت سفارش</h2>
                
                <form id="orderForm" class="order-form">
                    
                    <div class="form-group row">
                        <div class="col">
                            <label for="length">طول (سانتی‌متر)</label>
                            <input type="number" id="length" name="length" min="1" value="10" required>
                        </div>
                        <div class="col">
                            <label for="width">عرض (سانتی‌متر)</label>
                            <input type="number" id="width" name="width" min="1" value="10" required>
                        </div>
                        <div class="col">
                            <label for="height">ارتفاع (سانتی‌متر)</label>
                            <input type="number" id="height" name="height" min="1" value="5" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col">
                            <label for="quantity">تیراژ (حداقل ۵۰۰)</label>
                            <input type="number" id="quantity" name="quantity" min="500" value="1000" step="100" required>
                        </div>
                        <div class="col">
                            <label for="material_id">جنس مقوا/کاغذ</label>
                            <select id="material_id" name="material_id" required>
                                <?php foreach($papers as $paper): ?>
                                    <option value="<?= $paper['id'] ?>" data-price="<?= $paper['base_price'] ?>">
                                        <?= htmlspecialchars($paper['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>خدمات پس از چاپ (اختیاری)</label>
                        <div class="checkbox-group">
                            <?php foreach($finishes as $finish): ?>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="finishes[]" value="<?= $finish['id'] ?>" data-price="<?= $finish['base_price'] ?>">
                                    <?= htmlspecialchars($finish['name']) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="price-display">
                        <span class="price-label">مبلغ کل برآورد شده:</span>
                        <span id="totalPrice" class="price-amount">0</span>
                        <span class="currency">تومان</span>
                    </div>

                    <button type="submit" class="btn-submit">تایید و ثبت سفارش</button>
                    <div id="formMessage" class="message-box"></div>
                </form>
            </div>
        </section>
    </main>

    <script src="/assets/js/calculator.js"></script>
</body>
</html>