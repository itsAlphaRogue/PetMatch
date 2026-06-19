<?php

use PetMatch\Service\MatchingEngine;

include "../includes/database.php";

$user_vector = [
    'living-space'        => (int)($_POST['living-space']   ?? 0),
    'activity-level'      => (int)($_POST['activity-level'] ?? 0),
    'grooming-time'       => (int)($_POST['grooming-time']  ?? 0),
    'experience'          => (int)($_POST['experience']     ?? 0),
    'children'            => (int)($_POST['children']       ?? 0),
    'budget'              => (int)($_POST['budget']         ?? 0),
    'shedding'            => (int)($_POST['shedding']       ?? 0),
    'noise'               => (int)($_POST['noise']          ?? 0),
    'other-pets'          => (int)($_POST['other-pets']     ?? 0),
    'alone-time'          => (int)($_POST['alone-time']     ?? 0),
    'climate'             => (int)($_POST['climate']        ?? 0),
];

if (in_array(0, $user_vector, true)) {
    die('<p class="text-red-500 font-bold text-center">Please answer all questions before submitting.</p>');
}

$matchingEngine = new MatchingEngine();
$matches = $matchingEngine->findMatches($user_vector);

if (empty($matches)) {
    die('<p class="text-red-500 font-bold text-center">No breed matches found.</p>');
}

$best           = $matches[0];
$breed2         = $matches[1] ?? null;
$breed3         = $matches[2] ?? null;
$breed4         = $matches[3] ?? null;

$bestBreedName  = $best->getBreedName();
$bestBreedScore = $best->getScore();
[$bestLabel, $labelBg, $labelText] = $best->getCompatibilityLabel();

echo <<<END
    <div class="rounded-3xl sticky top-8  bg-green-300 px-10 py-8 flex flex-col gap-4">
        <p class="text-center text-lg text-green-900">Best match for you is</p>
        <h1 class="font-poppins text-center text-4xl font-bold text-green-950">$bestBreedName</h1>
        <div class="flex flex-col items-center gap-2 mt-1">
            <span class="$labelBg $labelText text-sm font-bold px-4 py-1 rounded-full">$bestLabel</span>
            <span class="text-green-900 text-sm">Compatibility score: <strong>$bestBreedScore%</strong></span>
        </div>
        <div class="w-full bg-green-200 rounded-full h-3 mt-1">
            <div class="bg-green-600 h-3 rounded-full transition-all" style="width: $bestBreedScore%"></div>
        </div>
        <p class="font-poppins text-xl font-bold text-green-950 mt-4">Other suggested breeds</p>
        <ul class="flex flex-col gap-3">
END;

$suggestions = [$breed2, $breed3, $breed4];
foreach ($suggestions as $s) {
    if ($s) {
        $sName = $s->getBreedName();
        $sScore = $s->getScore();
        [$sLabel, , ] = $s->getCompatibilityLabel();
        echo <<<END
            <li class="flex items-center justify-between bg-green-200 rounded-xl px-4 py-2">
                <span class="font-semibold">$sName</span>
                <span class="text-sm text-green-800">$sLabel &bull; $sScore%</span>
            </li>
END;
    }
}

echo <<<END
        </ul>
        <p class="text-xs text-green-800 text-center mt-2">
            Score is based on how closely each breed matches your lifestyle across 11 factors.
            100% means a perfect match on every factor.
        </p>
    </div>
END;