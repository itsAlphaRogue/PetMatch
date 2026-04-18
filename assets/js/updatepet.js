function getPetDetailsToUpdate()
{
    const params = new URLSearchParams(window.location.search);
    const petid = params.get('id');
    
    fetch("scripts/editpet.php",{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: petid })
    })
    .then(Response => Response.text())
    .then(data => {
        if(data === "admindashboard")
        {
            window.location.href = "/petmatch/admindashboard";
        }
        else
        {
            const div = document.getElementById("pet");
            div.innerHTML = data;

            // to run previewPetImage() from editpet.php
            // it shows uploaded image in previewbox
            const scripts = document.getElementById("pet").querySelectorAll("script");
            scripts.forEach(script => {
                const newScript = document.createElement("script");
                if (script.src) {
                    newScript.src = script.src;
                } else {
                    newScript.textContent = script.textContent;
                }
                document.body.appendChild(newScript); // Appending executes it
            });
        }
    });
}getPetDetailsToUpdate();


function updatePet(event)
{
    event.preventDefault();
    form = event.target;
    const formdata = new FormData(form);

    const params = new URLSearchParams(window.location.search);
    const petid = params.get('id');
    formdata.append('id',petid);

    fetch("scripts/updatepet.php",{
        method: 'POST',
        body: formdata
    })
    .then(Response => Response.text())
    .then(data => {
        // to display message
        const messagebox = document.getElementById("messagebox");
        messagebox.innerHTML = data;
        setTimeout(() => {
            messagebox.innerHTML = "";
        }, 5000);
    })
}