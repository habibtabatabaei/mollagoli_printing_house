// public/assets/js/calculator.js

document.addEventListener('DOMContentLoaded', function() {
    // دریافت المان‌های فرم
    const form = document.getElementById('orderForm');
    const inputs = form.querySelectorAll('input, select');
    const priceDisplay = document.getElementById('totalPrice');
    const messageBox = document.getElementById('formMessage');

    // تابع فرمت‌بندی اعداد (جدا کردن سه‌رقم سه‌رقم)
    const formatNumber = (num) => {
        return new Intl.NumberFormat('fa-IR').format(Math.round(num));
    };

    // تابع اصلی محاسبه قیمت در فرانت‌اند
    const calculatePrice = () => {
        const length = parseFloat(document.getElementById('length').value) || 0;
        const width = parseFloat(document.getElementById('width').value) || 0;
        const height = parseFloat(document.getElementById('height').value) || 0;
        const quantity = parseInt(document.getElementById('quantity').value) || 0;
        
        const materialSelect = document.getElementById('material_id');
        const selectedOption = materialSelect.options[materialSelect.selectedIndex];
        const materialBasePrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;

        // محاسبه مساحت (فرمول ساده گسترده جعبه به متر مربع)
        const area = (2 * ((length * width) + (length * height) + (width * height))) / 10000;
        
        // محاسبه قیمت پایه (مساحت * قیمت جنس)
        let unitPrice = area * materialBasePrice;

        // محاسبه هزینه خدمات پس از چاپ
        const checkboxes = document.querySelectorAll('input[name="finishes[]"]:checked');
        let finishCostPerUnit = 0;
        checkboxes.forEach(cb => {
            finishCostPerUnit += parseFloat(cb.getAttribute('data-price'));
        });

        unitPrice += finishCostPerUnit;

        // قیمت کل بر اساس تیراژ + هزینه ثابت (زینک/قالب)
        const setupFee = 500000;
        const totalPrice = (unitPrice * quantity) + setupFee;

        // نمایش قیمت (اگر مقادیر معتبر باشند)
        if (length > 0 && width > 0 && height > 0 && quantity >= 500) {
            priceDisplay.textContent = formatNumber(totalPrice);
        } else {
            priceDisplay.textContent = '0';
        }
    };

    // اضافه کردن رویداد به همه ورودی‌ها برای محاسبه زنده
    inputs.forEach(input => {
        input.addEventListener('input', calculatePrice);
        input.addEventListener('change', calculatePrice);
    });

    // محاسبه اولیه هنگام لود صفحه
    calculatePrice();

    // مدیریت ارسال فرم به بک‌اند (بدون رفرش صفحه)
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // جمع‌آوری خدمات انتخاب شده
        const selectedFinishes = Array.from(document.querySelectorAll('input[name="finishes[]"]:checked'))
                                     .map(cb => parseInt(cb.value));

        const orderData = {
            length: document.getElementById('length').value,
            width: document.getElementById('width').value,
            height: document.getElementById('height').value,
            quantity: document.getElementById('quantity').value,
            material_id: document.getElementById('material_id').value,
            finishes: selectedFinishes
        };

        // تغییر متن دکمه
        const submitBtn = form.querySelector('.btn-submit');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'در حال پردازش...';
        submitBtn.disabled = true;
        messageBox.className = 'message-box';

        // ارسال دیتا به API که در مرحله قبل ساختیم
        fetch('/api/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                messageBox.classList.add('success');
                messageBox.innerHTML = `✅ ${data.message}<br><strong>کد پیگیری شما: ${data.tracking_code}</strong>`;
                form.reset(); // پاک کردن فرم پس از موفقیت
                calculatePrice(); // صفر کردن قیمت
            } else {
                messageBox.classList.add('error');
                messageBox.textContent = `❌ ${data.message}`;
            }
        })
        .catch(error => {
            messageBox.classList.add('error');
            messageBox.textContent = '❌ خطای ارتباط با سرور. لطفا دوباره تلاش کنید.';
        })
        .finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
});