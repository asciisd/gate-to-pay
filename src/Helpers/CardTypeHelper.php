<?php

namespace ASCIISD\GateToPay\Helpers;

class CardTypeHelper
{
    /**
     * Standard card type.
     */
    public const STANDARD = 'Standard';

    /**
     * Premium card type.
     */
    public const PREMIUM = 'Premium';

    /**
     * Gold card type.
     */
    public const GOLD = 'Gold';

    /**
     * Get all available card types.
     *
     * @return array
     */
    public static function all(): array
    {
        return [
            ['code' => self::STANDARD, 'ar' => 'قياسي', 'en' => 'Standard'],
            ['code' => self::PREMIUM, 'ar' => 'متميز', 'en' => 'Premium'],
            ['code' => self::GOLD, 'ar' => 'ذهبي', 'en' => 'Gold'],
        ];
    }

    /**
     * Get card type by code.
     *
     * @param string $code
     * @return array|null
     */
    public static function getByCode(string $code): ?array
    {
        foreach (self::all() as $cardType) {
            if ($cardType['code'] === $code) {
                return $cardType;
            }
        }

        return null;
    }

    /**
     * Get card type code by name.
     *
     * @param string $name Card type name in English or Arabic
     * @return string|null
     */
    public static function getCodeByName(string $name): ?string
    {
        foreach (self::all() as $cardType) {
            if (
                mb_strtolower($cardType['en']) === mb_strtolower($name) ||
                mb_strtolower($cardType['ar']) === mb_strtolower($name)
            ) {
                return $cardType['code'];
            }
        }

        return null;
    }
}
