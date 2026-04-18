<?php

// ---------------------------------------------------------------------------
// Weighted Manhattan Distance — breed matcher v2
// Added 4 new attributes: shedding_level, noise_level,
//                         good_with_pets, alone_time_tolerance
// ---------------------------------------------------------------------------

$weights = [
    'living-space'        => 2.0,
    'activity-level'      => 2.5,
    'grooming-time'       => 1.5,
    'experience'          => 2.0,
    'children'            => 2.5,
    'budget'              => 1.5,
    // New attributes
    'shedding'            => 2.0,   // Allergies / cleaning — matters a lot day-to-day
    'noise'               => 2.0,   // Neighbour complaints, apartment living
    'other-pets'          => 2.0,   // Safety — prey drive vs. resident cat/dog
    'alone-time'          => 2.0,   // Working owners; destructive behaviour risk
];

// ---------------------------------------------------------------------------
// Max possible distance = sum of (weight × 2) across all attributes.
// All attributes use a 1–3 scale, so the maximum mismatch per attribute is 2.
// Normalising by this value keeps scores in a consistent range regardless
// of how many attributes exist. Without this, adding attributes compresses
// all scores toward zero.
// ---------------------------------------------------------------------------
$max_dist = array_sum(array_map(fn($w) => $w * 2, $weights)); // 40.0

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
];

if (in_array(0, $user_vector, true)) {
    die('<p class="text-red-500 font-bold text-center">Please answer all questions before submitting.</p>');
}

$json   = file_get_contents("../assets/breeds.json");
$breeds = json_decode($json, true);

$matches = [];
foreach ($breeds as $breed) {
    $breed_vector = [
        'living-space'        => $breed['space_requirement'],
        'activity-level'      => $breed['activity_level'],
        'grooming-time'       => $breed['grooming_needs'],
        'experience'          => $breed['trainability'],
        'children'            => $breed['good_with_children'],
        'budget'              => $breed['maintenance_cost'],
        'shedding'            => $breed['shedding_level'],
        'noise'               => $breed['noise_level'],
        'other-pets'          => $breed['good_with_pets'],
        'alone-time'          => $breed['alone_time_tolerance'],
    ];

    $score    = weightedManhattan($user_vector, $breed_vector, $weights, $max_dist);
    $matches[] = ['name' => $breed['name'], 'score' => $score];
}

usort($matches, fn($a, $b) => $b['score'] <=> $a['score']);

$best       = $matches[0];
$breed2     = $matches[1];
$breed3     = $matches[2];
$breed4     = $matches[3];

$bestBreedName  = $best['name'];
$bestBreedScore = $best['score'];
$breed2name     = $breed2['name'];
$breed2score    = $breed2['score'];
$breed3name     = $breed3['name'];
$breed3score    = $breed3['score'];
$breed4name     = $breed4['name'];
$breed4score    = $breed4['score'];

[$bestLabel, $labelBg, $labelText] = compatibilityLabel($bestBreedScore);
[$label2, ,] = compatibilityLabel($breed2score);
[$label3, ,] = compatibilityLabel($breed3score);
[$label4, ,] = compatibilityLabel($breed4score);

echo <<<END
    <div class="rounded-3xl sticky top-8 bg-green-300 px-10 py-8 flex flex-col gap-4">
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
            <li class="flex items-center justify-between bg-green-200 rounded-xl px-4 py-2">
                <span class="font-semibold">$breed2name</span>
                <span class="text-sm text-green-800">$label2 &bull; $breed2score%</span>
            </li>
            <li class="flex items-center justify-between bg-green-200 rounded-xl px-4 py-2">
                <span class="font-semibold">$breed3name</span>
                <span class="text-sm text-green-800">$label3 &bull; $breed3score%</span>
            </li>
            <li class="flex items-center justify-between bg-green-200 rounded-xl px-4 py-2">
                <span class="font-semibold">$breed4name</span>
                <span class="text-sm text-green-800">$label4 &bull; $breed4score%</span>
            </li>
        </ul>
        <p class="text-xs text-green-800 text-center mt-2">
            Score is based on how closely each breed matches your lifestyle across 10 factors.
            100% means a perfect match on every factor.
        </p>
    </div>
END;

// ---------------------------------------------------------------------------
function compatibilityLabel(string $score): array
{
    $s = (float)$score;
    if ($s >= 70) return ['Exceptional match', 'bg-green-600',  'text-white'];
    if ($s >= 60) return ['Great match',        'bg-green-500',  'text-white'];
    if ($s >= 50) return ['Good match',         'bg-green-400',  'text-green-900'];
    if ($s >= 40) return ['Decent match',       'bg-yellow-300', 'text-yellow-900'];
    return             ['Low compatibility',   'bg-red-200',    'text-red-900'];
}

function weightedManhattan(array $user, array $breed, array $weights, float $max_dist): string
{
    $distance = 0.0;
    foreach ($weights as $key => $weight) {
        $distance += $weight * abs($user[$key] - $breed[$key]);
    }
    // Normalise: divide by max possible distance before inverting.
    // This keeps scores in a stable range regardless of attribute count.
    // Perfect match → 100%, complete mismatch → 50%.
    return number_format((1.0 / (1.0 + $distance / $max_dist)) * 100, 2);
}