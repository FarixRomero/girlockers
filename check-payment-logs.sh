#!/bin/bash

echo "🔍 Revisando logs de pagos en producción"
echo "========================================="
echo ""

ssh -i ssh-mb-api.pem ubuntu@34.197.80.87 << 'ENDSSH'
cd /home/ubuntu/proyectos/girlockers

echo "📋 Últimos logs de Izipay (últimos 50 registros):"
echo "=================================================="
tail -50 storage/logs/laravel.log | grep -A 5 -B 2 "Izipay" || echo "No se encontraron logs de Izipay"

echo ""
echo ""
echo "📋 Últimos errores de pago (últimos 30 registros):"
echo "==================================================="
tail -50 storage/logs/laravel.log | grep -A 3 -B 2 -i "payment.*error\|pago.*error\|CreatePaymentWithToken" || echo "No se encontraron errores de pago"

echo ""
echo ""
echo "📋 Estado de últimos pagos en la base de datos:"
echo "================================================"
php artisan tinker --execute="
\$payments = \App\Models\MembershipPayment::with('user')
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get(['id', 'user_id', 'order_id', 'status', 'amount', 'membership_type', 'created_at']);

foreach (\$payments as \$payment) {
    echo \"ID: {\$payment->id} | Order: {\$payment->order_id} | Status: {\$payment->status} | Amount: {\$payment->amount} | Type: {\$payment->membership_type} | User: {\$payment->user->email} | Date: {\$payment->created_at}\n\";
}
"

echo ""
echo ""
echo "🔧 Configuración actual de Izipay:"
echo "===================================="
php artisan tinker --execute="
echo 'Mode: ' . config('izipay.mode') . \"\n\";
echo 'API URL: ' . config('izipay.' . config('izipay.mode') . '.api_url') . \"\n\";
echo 'Username: ' . config('izipay.' . config('izipay.mode') . '.username') . \"\n\";
echo 'Public Key configured: ' . (config('izipay.' . config('izipay.mode') . '.public_key') ? 'Yes' : 'No') . \"\n\";
"

ENDSSH

echo ""
echo "✓ Revisión completada"
