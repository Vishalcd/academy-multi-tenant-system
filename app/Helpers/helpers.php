<?php

// Format Currency

use App\Models\Academy;
use App\Models\Sport;

if (! function_exists('formatCurrency')) {
    function formatCurrency($amount, $currency = 'INR', $locale = 'en_IN')
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, $currency);
    }
}

// get last year input
function generateYearArray($input)
{
    $currentYear = date('Y');
    $years = [];

    // Generate last (input + 1) years starting from current year
    for ($i = 0; $i <= $input; $i++) {
        $year = $currentYear - $i;
        $years[$year] = "Year $year";
    }

    return $years;
}

// Active Acedemy ID
if (! function_exists('activeAcademy')) {
    function activeAcademy(): ?Academy
    {
        $academyId = session('active_academy_id');
        if (! $academyId) {
            return null;
        }

        return Academy::find($academyId);
    }
}

// Get All Sports
function getAllSports()
{
    $academyId = session('active_academy_id');
    $sports = Sport::where('academy_id', $academyId)
        ->pluck('sport_title', 'id')
        ->toArray();

    return $sports;

}

// Get All Academies
function getAllAcademies()
{
    return Academy::pluck('name', 'id')->toArray();
}