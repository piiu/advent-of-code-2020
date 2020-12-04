<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day04 extends BaseDay
{
    const MANDATORY_FIELDS = ['byr', 'iyr', 'eyr', 'hgt', 'hcl', 'ecl', 'pid'];
    const BIRTH_YEAR_RANGE = [1920, 2002];
    const ISSUE_YEAR_RANGE = [2010, 2020];
    const EXPIRATION_YEAR_RANGE = [2020, 2030];
    const HEIGHT_PATTERN = '/([0-9]+)(cm|in)/';
    const HEIGHT_CM_RANGE = [150, 193];
    const HEIGHT_IN_RANGE = [59, 76];
    const HAIR_COLOR_PATTERN = '/#[0-9a-f]{6}/';
    const VALID_EYE_COLORS = ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth'];
    const ID_LENGTH = 9;

    public function execute()
    {
        $passports = $this->getFormattedPassports();
        foreach ($passports as $passport) {
            if (empty(array_diff(self::MANDATORY_FIELDS, array_keys($passport)))) {
                $this->part1++;
                if ($this->validatePassport($passport)) {
                    $this->part2++;
                }
            }
        }
    }

    private function validatePassport(array $passport) : bool
    {
        return $this->isBetween($passport['byr'], self::BIRTH_YEAR_RANGE)
        && $this->isBetween($passport['iyr'], self::ISSUE_YEAR_RANGE)
        && $this->isBetween($passport['eyr'], self::EXPIRATION_YEAR_RANGE)
        && $this->validateHgt($passport['hgt'])
        && preg_match(self::HAIR_COLOR_PATTERN, $passport['hcl'])
        && in_array($passport['ecl'], self::VALID_EYE_COLORS)
        && strlen($passport['pid']) === self::ID_LENGTH;
    }

    private function isBetween(int $value, array $minMax) : bool
    {
        return $value >= $minMax[0] && $value <= $minMax[1];
    }

    private function validateHgt($value) : bool
    {
        if (!preg_match(self::HEIGHT_PATTERN, $value, $matches)) {
            return false;
        }
        return ($matches[2] === 'cm' && $this->isBetween($matches[1], self::HEIGHT_CM_RANGE))
        || ($matches[2] === 'in' && $this->isBetween($matches[1], self::HEIGHT_IN_RANGE));
    }

    private function getFormattedPassports() : array
    {
        $passports = $this->getInputArray(PHP_EOL.PHP_EOL);
        $formattedPassports = [];
        foreach ($passports as $passport) {
            $fields = $output = preg_split('/\s/', $passport);
            $formattedFields = [];
            foreach ($fields as $field) {
                if (empty($field)) {
                    continue;
                }
                list($key, $value) = explode(':', $field);
                $formattedFields[$key] = $value;
            }
            $formattedPassports[] = $formattedFields;
        }
        return $formattedPassports;
    }
}