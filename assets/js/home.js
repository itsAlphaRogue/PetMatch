// display 5 pets at home page
function displayPetsHome()
{
    fetch("scripts/displaypets.php",{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ limit: 4 })
    })
    .then(Response => Response.text())
    .then(data =>{
        const div = document.getElementById("pets");
        div.innerHTML = data;
    });
}displayPetsHome();