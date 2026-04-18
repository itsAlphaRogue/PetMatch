// displays main pet info in pet.html with id
function getPet()
{
    const params = new URLSearchParams(window.location.search);
    const petid = params.get('id');
    
    fetch("scripts/pet.php",{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: petid })
    })
    .then(Response => Response.text())
    .then(data => {
        const div = document.getElementById("pet");
        div.innerHTML = data;
    });
}getPet();



function displayPets()
{
    fetch("scripts/displaypets.php")
    .then(Response => Response.text())
    .then(data =>{
        const div = document.getElementById("pets");
        div.innerHTML = data;
    });
}displayPets();


function reservePet(petid)
{
    fetch("scripts/reservepet.php",{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: petid})
    })
    .then(Response => Response.text())
    .then(data => {
        if(data === 'redirect')
        {
            window.location.href = "/petmatch/login";
        }
        else
        {
            getPet();
        }
    });
}

