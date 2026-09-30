<?php

// Trending Score algorithm -> Popularity-Weighted Recency Score

include "../includes/database.php";

$gravity    = 1.5;
$view_w     = 1.0;
$reserve_w  = 3.0;
$top_n      = 4;

// Fetch all available pets with their view count and reservation count
// Views are windowed to the last 30 days to keep scores fresh
$sql = "
    SELECT
        p.id,
        p.name,
        p.age,
        p.gender,
        p.image,
        p.breed_id,
        COUNT(DISTINCT pv.id)              AS view_count,
        COUNT(DISTINCT ar.id)              AS reservation_count,
        MIN(pv.viewed_at)                  AS first_viewed_at
    FROM `pets` p
    LEFT JOIN `pet_views` pv
           ON pv.pet_id = p.id
          AND pv.viewed_at >= NOW() - INTERVAL 30 DAY
    LEFT JOIN `adoption_requests` ar
           ON ar.pet_id = p.id
    WHERE p.status = 'Available' OR p.status = 'Reserved'
    GROUP BY p.id
";

$result = mysqli_query($con, $sql);

$pets = [];
while ($row = mysqli_fetch_assoc($result)) {
    $views        = (int)$row['view_count'];
    $reservations = (int)$row['reservation_count'];

    // Hours since first view; brand-new pets get 0
    if ($row['first_viewed_at']) {
        $hours = max(0, (time() - strtotime($row['first_viewed_at'])) / 3600);
    } else {
        $hours = 0;
    }

    $score = ($views * $view_w + $reservations * $reserve_w) / pow($hours + 2, $gravity);

    $pets[] = array_merge($row, ['trending_score' => $score]);
}

// Sort descending by trending score
usort($pets, fn($a, $b) => $b['trending_score'] <=> $a['trending_score']);
$pets = array_slice($pets, 0, $top_n);


foreach ($pets as $row) {
    $id     = $row['id'];
    $image  = $row['image'];
    $name   = $row['name'];
    $age    = $row['age'];
    $gender = $row['gender'];

    $breedid     = $row['breed_id'];
    $breedresult = mysqli_fetch_assoc(
        mysqli_query($con, "SELECT `name` FROM `breeds` WHERE `id` = '$breedid'")
    );
    $breed = $breedresult['name'];

    echo <<<END
    <a href="pet?id=$id">
        <div class="card-shadow max-w-xs overflow-hidden rounded-3xl">
            <div class="h-64 overflow-hidden">
                <img class="w-full" src="assets/images/pets/$image" alt="" />
            </div>
            <div class="mx-4 my-4 flex flex-col gap-2">
                <p class="text-2xl font-bold">$name</p>
                <p class="text-neutral-600">$breed</p>
                <p class="text-neutral-600">$age ● $gender</p>
            </div>
        </div>
    </a>
    END;
}