<?php

namespace App\Helpers;

class CountryHelper
{
    private static $countries = null;

    private static function loadCountries()
    {
        if (self::$countries !== null) {
            return self::$countries;
        }

        $jsonPath = resource_path('data/country_code.json');
        
        if (!file_exists($jsonPath)) {
            self::$countries = [];
            return self::$countries;
        }

        $jsonContent = file_get_contents($jsonPath);
        self::$countries = json_decode($jsonContent, true) ?: [];
        
        return self::$countries;
    }

    public static function getAllCountries()
    {
        return self::loadCountries();
    }

    public static function extractCountryFromPhone($phone)
    {
        if (empty($phone)) {
            return 'US';
        }

        $countries = self::loadCountries();
        
        $phone = preg_replace('/[^+0-9]/', '', $phone);
        
       
        usort($countries, function($a, $b) {
            return strlen($b['dial_code']) - strlen($a['dial_code']);
        });
        
        foreach ($countries as $country) {
            $dialCode = $country['dial_code'];
            $dialCode = str_replace(' ', '', $dialCode);
            
            if (str_starts_with($phone, $dialCode)) {
                return $country['code'];
            }
        }

        return 'US';
    }

    public static function findCountryByCode($code)
    {
        $countries = self::loadCountries();
        
        foreach ($countries as $country) {
            if (strtoupper($country['code']) === strtoupper($code)) {
                return $country;
            }
        }
        
        return null;
    }

    public static function findCountryByDialCode($dialCode)
    {
        $countries = self::loadCountries();
        
        $dialCode = str_replace(' ', '', $dialCode);
        
        foreach ($countries as $country) {
            $countryDialCode = str_replace(' ', '', $country['dial_code']);
            if ($countryDialCode === $dialCode) {
                return $country;
            }
        }
        
        return null;
    }

    public static function findCountryByName($name)
    {
        $countries = self::loadCountries();
        
        foreach ($countries as $country) {
            if (strcasecmp($country['name'], $name) === 0) {
                return $country;
            }
        }
        
        return null;
    }

    public static function getCountryName($code)
    {
        $country = self::findCountryByCode($code);
        return $country ? $country['name'] : $code;
    }

    public static function getDialCode($code)
    {
        $country = self::findCountryByCode($code);
        return $country ? $country['dial_code'] : null;
    }

    public static function isValidCountryCode($code)
    {
        return self::findCountryByCode($code) !== null;
    }
}
