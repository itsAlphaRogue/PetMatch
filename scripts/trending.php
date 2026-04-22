<?php

// Trending Score algorithm
// -------------------------
// Ranks available pets by a time-decayed popularity score.
//
// Formula:
//   trending_score = (views * 1.0 + reservations * 3.0)
//                    / (hours_since_added + 2) ^ 1.5
//
// - views        = rows in pet_views for this pet (last 30 days)
// - reservations = rows in adoption_requests for this pet (any status)
// - hours_since_added = how old the first view of this pet is in hours
//                       (falls back to 0 if never viewed — still gets shown)
// - gravity (1.5) = how fast old interactions decay vs new ones
//
// Reservations are worth 3× a view because they signal strong intent.
// The +2 in the denominator prevents division-by-zero for brand-new pets
// and gives them a fair initial boost.
//
// Returns: top 4 trending Available pets as HTML cards,
//          matching the exact card markup used by displaypets.php.

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
    WHERE p.status = 'Available'
    GROUP BY p.id
";

$result = mysqli_query($con, $sql);

$pets = [];
while ($row = mysqli_fetch_assoc($result)) {
    $views        = (int)$row['view_count'];
    $reservations = (int)$row['reservation_count'];

    // Hours since first view; brand-new pets (never viewed) get 0
    if ($row['first_viewed_at']) {
        $hours = max(0, (time() - strtotime($row['first_viewed_at'])) / 3600);
    } else {
        $hours = 0;
    }

    $score = ($views * $view_w + $reservations * $reserve_w)
             / pow($hours + 2, $gravity);

    $pets[] = array_merge($row, ['trending_score' => $score]);
}

// Sort descending by trending score
usort($pets, fn($a, $b) => $b['trending_score'] <=> $a['trending_score']);
$pets = array_slice($pets, 0, $top_n);

// Render — identical card markup to displaypets.php
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