// Fetches and renders trending pets into #trending-pets on home page
function displayTrendingPets()
{
    fetch("scripts/trending.php")
    .then(Response => Response.text())
    .then(data => {
        const div = document.getElementById("trending-pets");
        div.innerHTML = data;
    });
}displayTrendingPets();
