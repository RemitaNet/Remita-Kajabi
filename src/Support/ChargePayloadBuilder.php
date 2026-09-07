<?php

declare(strict_types=1);

namespace PaymentEngine\Kajabi\Support;

final class ChargePayloadBuilder
{
    public static function fromRequest(
        array $request,
        string $paymentIdentifier,
        string $returnUrl
    ): array {

        $offerTitle = trim(
            (string) ($request['offerTitle'] ?? '')
        );

        return [
            'firstName' => trim((string) ($request['firstName'] ?? '')),
            'lastName' => trim((string) ($request['lastName'] ?? '')),
            'email' => trim((string) ($request['email'] ?? '')),
            'phoneNumber' => trim((string) ($request['phoneNumber'] ?? '')),
            'paymentIdentifier' => $paymentIdentifier,
            'currency' => trim((string) ($request['currency'] ?? 'NGN')),
            'narration' => $offerTitle !== ''
                ? $offerTitle
                : 'Kajabi Course Purchase',
            'amount' => AmountNormalizer::toKobo(
                (string) ($request['amount'] ?? '0')
            ),
            'returnUrl' => $returnUrl,
        ];
    }
}