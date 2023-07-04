<?php


namespace App\Service;


class PhoneNumberFilter
{
    private const ALLOWED_COUNTRIES = [
        "ru" => ["length" => 11, "codes" => ["|^89|", "|^79|",]],
    ];

    public function filter($number): bool
    {
        $phone = preg_replace('/[^0-9]/', '', $number);
        foreach (self::ALLOWED_COUNTRIES as $item) {
            if (strlen($phone) == $item["length"]) {
                foreach ($item["codes"] as $code) {
                    if (preg_match($code, $phone)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}