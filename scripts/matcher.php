<?php

$user_vector = [
    $_POST['living-space'] ?? 0,
    $_POST['activity-level'] ?? 0,
    $_POST['grooming-time'] ?? 0,
    $_POST['experience'] ?? 0,
    $_POST['children'] ?? 0,
    $_POST['budget'] ?? 0
];

$json = file_get_contents("../assets/breeds.json");
$breeds = json_decode($json, true);

$matches = [];
// creating array as i.e vectors to compare. 
foreach ($breeds as $breed) {
    $breed_vector = [
        $breed['space_requirement'],
        $breed['activity_level'],
        $breed['grooming_needs'],
        $breed['trainability'],
        $breed['good_with_children'],
        $breed['maintenance_cost']
    ];

    $similarity = cosineSimilarity($user_vector, $breed_vector);

    $matches[] = [
        'name' => $breed['name'],
        'score' => $similarity
    ];
}

usort($matches, function($a, $b) {
    return $b['score'] <=> $a['score']; 
});

$bestBreedName = $matches[0]['name'];
$bestBreedScore = $matches[0]['score'];

$breed2name = $matches[1]['name'];
$breed2score = $matches[1]['score'];

$breed3name = $matches[2]['name'];
$breed3score = $matches[2]['score'];

$breed4name = $matches[3]['name'];
$breed4score = $matches[3]['score'];

echo <<<END
    <div class="rounded-3xl bg-green-300 px-14 py-8">
        <p class="text-center text-2xl">Best match for you is</p>
        <h1 class="font-poppins my-2 text-center text-3xl font-bold">$bestBreedName</h1>
        <p class="text-center">$bestBreedScore%</p>
        <p class="font-poppins mt-8 text-2xl font-bold">Other suggested breeds</p>
        <ul class="mt-2 flex list-disc flex-col gap-2 text-xl">
            <li>$breed2name - $breed2score%</li>
            <li>$breed3name - $breed3score%</li>
            <li>$breed4name - $breed4score%</li>
        </ul>
    </div>
END;


function cosineSimilarity($user, $breed)
{
    $dotProduct = 0;
    $magnitudeUser = 0;
    $magnitudeBreed = 0;

    for($i = 0; $i<count($user); $i++)
    {
        $dotProduct += $user[$i] * $breed[$i];
        $magnitudeUser += $user[$i] * $user[$i];
        $magnitudeBreed += $breed[$i] * $breed[$i];
    }

    $magnitude = sqrt($magnitudeUser) * sqrt($magnitudeBreed);
    if($magnitude != 0)
    {
        $similarity = $dotProduct / $magnitude;
        return number_format(($similarity)*100,2);
    }
    else
    {
        die("No suitable breeds found. Please check your input.");
    }
}