<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin:0;background:#f5f5f4;font-family:Arial,Helvetica,sans-serif;color:#1c1917;">
    <div style="max-width:560px;margin:0 auto;padding:24px;">
        <div style="background:#EB1C22;border-radius:16px 16px 0 0;padding:24px;text-align:center;">
            <h1 style="margin:0;color:#fff;font-size:22px;">Нова поръчка</h1>
        </div>

        <div style="background:#fff;border-radius:0 0 16px 16px;padding:24px;">
            <h2 style="margin-top:0;font-size:18px;">Поръчка {{ $order->order_number }}</h2>
            <p style="color:#57534e;">Получена на {{ $order->created_at->format('d.m.Y H:i') }} ч.</p>

            @include('emails.partials.order-details', ['order' => $order])
        </div>
    </div>
</body>
</html>
