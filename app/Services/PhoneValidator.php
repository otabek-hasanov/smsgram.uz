<?php

namespace App\Services;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberUtil;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * SMS faqat O'zbekiston mobil operatorlari raqamlariga yuborilishi kerak
 * (giggsey/libphonenumber — Google'ning libphonenumber kutubxonasi PHP porti).
 */
final class PhoneValidator
{
    private static ?PhoneNumberUtil $util = null;

    private static function util(): PhoneNumberUtil
    {
        return self::$util ??= PhoneNumberUtil::getInstance();
    }

    /**
     * Raqamni tekshiradi. To'g'ri O'zbekiston mobil raqami bo'lsa,
     * plyussiz E.164 formatda (masalan "998901234567") qaytaradi,
     * aks holda null.
     */
    public static function normalizeUzbekMobile(string $raw): ?string
    {
        $raw = trim($raw);

        if ($raw === '') {
            return null;
        }

        // '+' belgisini saqlab qolamiz (xalqaro format bo'lsa), qolgan
        // formatlash belgilarini (bo'shliq, tire va h.k.) olib tashlaymiz.
        // "UZ" standart hudud sifatida beriladi — shunda 9 xonali mahalliy
        // format ("901234567") va to'liq format ("998901234567") ikkalasi
        // ham to'g'ri tanib olinadi.
        $candidate = preg_replace('/[^\d+]/', '', $raw);

        try {
            $number = self::util()->parse($candidate, 'UZ');
        } catch (NumberParseException) {
            return null;
        }

        if (!self::util()->isValidNumber($number)) {
            return null;
        }

        if (self::util()->getRegionCodeForNumber($number) !== 'UZ') {
            return null;
        }

        $type = self::util()->getNumberType($number);

        if (!in_array($type, [PhoneNumberType::MOBILE, PhoneNumberType::FIXED_LINE_OR_MOBILE], true)) {
            return null;
        }

        return ltrim(self::util()->format($number, PhoneNumberFormat::E164), '+');
    }

    /**
     * Bo'sh joy/vergul bilan ajratilgan matnni tekshirib, to'g'ri va
     * noto'g'ri raqamlarni alohida qaytaradi.
     *
     * @return array{valid: string[], invalid: string[]}
     */
    public static function parseAndValidateList(string $raw): array
    {
        $parts = preg_split('/[\s,;]+/', $raw, -1, PREG_SPLIT_NO_EMPTY);
        $valid = [];
        $invalid = [];

        foreach ($parts as $part) {
            $normalized = self::normalizeUzbekMobile($part);

            if ($normalized === null) {
                $invalid[] = $part;
            } elseif (!in_array($normalized, $valid, true)) {
                $valid[] = $normalized;
            }
        }

        return ['valid' => $valid, 'invalid' => $invalid];
    }

    /**
     * @param string[] $rawList
     * @return array{valid: string[], invalid: string[]}
     */
    public static function validateArray(array $rawList): array
    {
        $valid = [];
        $invalid = [];

        foreach ($rawList as $raw) {
            $normalized = self::normalizeUzbekMobile((string) $raw);

            if ($normalized === null) {
                $invalid[] = (string) $raw;
            } elseif (!in_array($normalized, $valid, true)) {
                $valid[] = $normalized;
            }
        }

        return ['valid' => $valid, 'invalid' => $invalid];
    }
}
