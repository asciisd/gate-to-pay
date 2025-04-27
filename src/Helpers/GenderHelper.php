<?php

namespace ASCIISD\GateToPay\Helpers;

class GenderHelper
{
    /**
     * Gender code for Male.
     */
    public const MALE = 'M';

    /**
     * Gender code for Female.
     */
    public const FEMALE = 'F';

    /**
     * Get all available genders.
     *
     * @return array
     */
    public static function all(): array
    {
        return [
            ['code' => self::MALE, 'ar' => 'ذكر', 'en' => 'Male'],
            ['code' => self::FEMALE, 'ar' => 'أنثى', 'en' => 'Female'],
        ];
    }

    /**
     * Get gender by code.
     *
     * @param string $code
     * @return array|null
     */
    public static function getByCode(string $code): ?array
    {
        foreach (self::all() as $gender) {
            if ($gender['code'] === $code) {
                return $gender;
            }
        }

        return null;
    }

    /**
     * Get gender code by name.
     *
     * @param string $name Gender name in English or Arabic
     * @return string|null
     */
    public static function getCodeByName(string $name): ?string
    {
        foreach (self::all() as $gender) {
            if (
                mb_strtolower($gender['en']) === mb_strtolower($name) ||
                mb_strtolower($gender['ar']) === mb_strtolower($name)
            ) {
                return $gender['code'];
            }
        }

        return null;
    }
}
