<?php

namespace App\Services;

use App\Models\MembershipPayment;
use App\Models\MembershipPlan;
use App\Models\PaymentToken;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MembershipService
{
    /**
     * Get the price for a specific membership type
     */
    public function getMembershipPrice(string $type): float
    {
        return MembershipPlan::getPrice($type);
    }

    /**
     * Get all active membership plans
     */
    public function getActivePlans()
    {
        return MembershipPlan::where('is_active', true)->get();
    }

    /**
     * Create a pending payment for a user
     */
    public function createPendingPayment(User $user, string $membershipType): MembershipPayment
    {
        $amount = $this->getMembershipPrice($membershipType);

        if ($amount <= 0) {
            throw new \Exception("Plan de membresía '{$membershipType}' no encontrado o inactivo.");
        }

        $payment = MembershipPayment::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'currency' => config('izipay.currency', 'PEN'),
            'membership_type' => $membershipType,
            'payment_status' => 'pending',
        ]);

        Log::info('Pending payment created', [
            'payment_id' => $payment->id,
            'user_id' => $user->id,
            'amount' => $amount,
            'membership_type' => $membershipType,
        ]);

        return $payment;
    }

    /**
     * Process successful payment and grant/extend membership access
     */
    public function processSuccessfulPayment(MembershipPayment $payment, array $izipayResponse): void
    {
        DB::transaction(function () use ($payment, $izipayResponse) {
            // Marcar pago como completado
            $payment->markAsCompleted($izipayResponse);

            // Otorgar o extender acceso
            $user = $payment->user;

            if ($user->has_full_access && $user->membership_expires_at && $user->membership_expires_at->isFuture()) {
                // Usuario tiene membresía activa -> extender
                $user->extendMembership($payment->membership_type);
                $action = 'extended';
            } else {
                // Usuario sin membresía activa -> otorgar nueva
                $user->grantFullAccess($payment->membership_type);
                $action = 'granted';
            }

            Log::info('Membership access processed successfully', [
                'user_id' => $user->id,
                'payment_id' => $payment->id,
                'membership_type' => $payment->membership_type,
                'action' => $action,
                'expires_at' => $user->membership_expires_at,
            ]);
        });
    }

    /**
     * Save payment token (card) for future use
     */
    public function savePaymentToken(User $user, string $paymentMethodToken, array $cardDetails): PaymentToken
    {
        // Extraer detalles de la tarjeta
        $cardBrand = $cardDetails['effectiveBrand'] ?? null;
        $cardPan = $cardDetails['pan'] ?? '';
        $cardLast4 = substr($cardPan, -4);
        $cardExpiryMonth = $cardDetails['expiryMonth'] ?? null;
        $cardExpiryYear = $cardDetails['expiryYear'] ?? null;

        // Verificar si el token ya existe para este usuario
        $existingToken = PaymentToken::where('user_id', $user->id)
            ->where('payment_method_token', $paymentMethodToken)
            ->first();

        if ($existingToken) {
            Log::info('Payment token already exists', [
                'user_id' => $user->id,
                'token_id' => $existingToken->id,
            ]);
            return $existingToken;
        }

        // Crear nuevo token
        $paymentToken = PaymentToken::create([
            'user_id' => $user->id,
            'payment_method_token' => $paymentMethodToken,
            'card_brand' => $cardBrand,
            'card_last_four' => $cardLast4,
            'card_expiry_month' => $cardExpiryMonth,
            'card_expiry_year' => $cardExpiryYear,
            'is_default' => false,
            'is_active' => true,
            'metadata' => [
                'created_from_payment' => true,
                'card_details' => $cardDetails,
            ],
        ]);

        // Si es la primera tarjeta del usuario, marcarla como predeterminada
        $userTokensCount = PaymentToken::where('user_id', $user->id)
            ->where('is_active', true)
            ->count();

        if ($userTokensCount === 1) {
            $paymentToken->setAsDefault();
        }

        Log::info('Payment token saved successfully', [
            'user_id' => $user->id,
            'token_id' => $paymentToken->id,
            'card_brand' => $cardBrand,
            'card_last_four' => $cardLast4,
            'is_default' => $paymentToken->is_default,
        ]);

        return $paymentToken;
    }

    /**
     * Validate that a payment token belongs to a user and is valid
     */
    public function validatePaymentToken(PaymentToken $token, User $user): array
    {
        // Verificar propiedad
        if ($token->user_id !== $user->id) {
            return [
                'valid' => false,
                'error' => 'La tarjeta no pertenece al usuario.',
            ];
        }

        // Verificar estado activo
        if (!$token->is_active) {
            return [
                'valid' => false,
                'error' => 'La tarjeta no está activa.',
            ];
        }

        // Verificar expiración
        if ($token->isExpired()) {
            return [
                'valid' => false,
                'error' => 'La tarjeta ha expirado.',
            ];
        }

        return ['valid' => true];
    }

    /**
     * Get user's payment statistics
     */
    public function getUserPaymentStats(User $user): array
    {
        return [
            'total_payments' => $user->membershipPayments()->completed()->count(),
            'total_spent' => $user->membershipPayments()->completed()->sum('amount'),
            'active_membership' => $user->has_full_access,
            'membership_expires_at' => $user->membership_expires_at,
            'days_until_expiration' => $user->getDaysUntilExpiration(),
            'is_expiring_soon' => $user->isMembershipExpiringSoon(),
        ];
    }
}
