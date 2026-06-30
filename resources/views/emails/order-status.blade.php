<!DOCTYPE html>
<html lang="bg">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body style="margin:0;background:#f5f5f4;font-family:Arial,Helvetica,sans-serif;color:#1c1917;">
    <div style="max-width:560px;margin:0 auto;padding:24px;">
        <div style="background:#EB1C22;border-radius:16px 16px 0 0;padding:24px;text-align:center;">
            <h1 style="margin:0;color:#fff;font-size:22px;">Allo! Pizza</h1>
        </div>
        <div style="background:#fff;border-radius:0 0 16px 16px;padding:24px;">
            <h2 style="margin-top:0;font-size:18px;">Промяна по поръчка {{ $order->order_number }}</h2>
            <p style="color:#57534e;">Новият статус на поръчката ви е:</p>
            <p style="display:inline-block;background:#faf8f2;border-radius:999px;padding:8px 16px;font-weight:bold;color:#EB1C22;">
                {{ $order->status->label() }}
            </p>
            <table style="width:100%;border-collapse:collapse;margin-top:20px;">
                <tr><td style="padding:4px 0;color:#57534e;">Сума</td><td style="padding:4px 0;text-align:right;font-weight:bold;">{{ money($order->total) }}</td></tr>
                <tr><td style="padding:4px 0;color:#57534e;">Получаване</td><td style="padding:4px 0;text-align:right;">{{ $order->delivery_type->label() }}</td></tr>
            </table>
            <p style="margin-top:24px;color:#a8a29e;font-size:12px;text-align:center;">
                Allo! Pizza · гр. Русе, ул. „Мария Луиза“, 22 · 0899 679 006 · allopizza@abv.bg
            </p>
        </div>
    </div>
</body>
</html>
