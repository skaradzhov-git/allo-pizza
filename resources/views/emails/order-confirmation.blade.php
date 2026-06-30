<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin:0;background:#f5f5f4;font-family:Arial,Helvetica,sans-serif;color:#1c1917;">
    <div style="max-width:560px;margin:0 auto;padding:24px;">
        <div style="background:#EB1C22;border-radius:16px 16px 0 0;padding:24px;text-align:center;">
            <h1 style="margin:0;color:#fff;font-size:22px;">Allo! Pizza</h1>
        </div>

        <div style="background:#fff;border-radius:0 0 16px 16px;padding:24px;">
            <h2 style="margin-top:0;font-size:18px;">Благодарим за поръчката!</h2>
            <p style="color:#57534e;">Вашата поръчка <strong>{{ $order->order_number }}</strong> е приета успешно.</p>

            @include('emails.partials.order-details', ['order' => $order])

            <p style="margin-top:24px;color:#a8a29e;font-size:12px;text-align:center;">
                Allo! Pizza · гр. Русе, ул. „Мария Луиза“, 22<br>
                0899 679 006 · 0899 679 710 · allopizza@abv.bg
            </p>
        </div>
    </div>
</body>
</html>
