<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day21 extends BaseDay
{
    private array $allAllergens = [];

    public function execute()
    {
        $possibilities = $this->formatInput();
        $translations = $this->getAllergenTranslations($possibilities);
        $knownAllergens = array_keys($translations);

        foreach ($possibilities as $possibility) {
            $allergenFree = 0;
            foreach ($possibility['ingredients'] as $ingredient) {
                if (!in_array($ingredient, $knownAllergens)) {
                    $allergenFree++;
                }
            }
            $this->part1 += $allergenFree;
        }

        asort($translations);
        $this->part2 = implode(',', array_keys($translations));
    }

    private function formatInput() : array
    {
        $possibilities = [];
        foreach ($this->getInputArray(PHP_EOL) as $row) {
            preg_match('/(.*) \(contains (.*)\)/', $row, $matches);
            $allergens = explode(', ', $matches[2]);
            $this->allAllergens = array_unique(array_merge($this->allAllergens, $allergens));
            $possibilities[] = [
                'ingredients' => explode(' ', $matches[1]),
                'allergens' => $allergens
            ];
        }
        return $possibilities;
    }

    private function getAllergenTranslations(array $possibilities) : array
    {
        $allergenPossibilities = [];
        foreach ($this->allAllergens as $allergen) {
            foreach ($possibilities as $possibility) {
                if (!in_array($allergen, $possibility['allergens'])) {
                    continue;
                }
                if (!($allergenPossibilities[$allergen] ?? null)) {
                    $allergenPossibilities[$allergen] = $possibility['ingredients'];
                    continue;
                }
                $allergenPossibilities[$allergen] = array_intersect($allergenPossibilities[$allergen], $possibility['ingredients']);
            }
        }

        asort($allergenPossibilities);
        $allergenTranslations = [];

        foreach ($allergenPossibilities as $allergen => $possibilities) {
            $possibilities = array_diff($possibilities, array_keys($allergenTranslations));
            if (count($possibilities) === 1) {
                $allergenTranslations[array_pop($possibilities)] = $allergen;
                continue;
            }
            throw new \Exception('Something went wrong');
        }

        return $allergenTranslations;
    }
}