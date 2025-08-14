// Functions used in Pet

function createPet(event)
{
    const form = event.target;
    event.preventDefault();
    const formdata = new FormData(form);

    fetch("scripts/createpet.php",{
        method: "POST",
        body: formdata
    })
    .then(Response => Response.text())
    .then(data=>{
        const imagepreviewbox = document.getElementById("previewbox");
        imagepreviewbox.innerHTML = '<svg class="h-8 fill-black/60" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M480-480ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h320v80H200v560h560v-280h80v280q0 33-23.5 56.5T760-120H200Zm40-160h480L570-480 450-320l-90-120-120 160Zm480-280v-167l-64 63-56-56 160-160 160 160-56 56-64-63v167h-80Z" /></svg><p class="text-black/60">Click to upload image</p>';
        imagepreviewbox.style.backgroundImage = "none";
        document.getElementById("reset").click();
        
        // to display message
        const messagebox = document.getElementById("messagebox");
        messagebox.innerHTML = data;
        setTimeout(() => {
            messagebox.innerHTML = "";
        }, 5000);
    })
}

function triggerFileInput()
{
    document.getElementById("image-input").click();
}

function previewImage(event)
{
    const file = event.target.files[0];
    if(file)
    {
        const reader = new FileReader();
        reader.onload = function(e)
        {
            let previewBox = document.getElementById("preview-box");
            previewBox.style.backgroundImage = `url(${e.target.result})`;
            previewBox.innerHTML = '';
        }
        reader.readAsDataURL(file);
    }
}


function searchPet(event)
{
    form = event.target;
    event.preventDefault();
    const formdata = new FormData(form);

    fetch('scripts/searchpet.php',{
        method : 'POST',
        body : formdata
    })
    .then(Response => Response.text())
    .then(data => {
        const div = document.getElementById("pets");
        div.innerHTML = data;
    })
    .catch(error => {
        console.log(error);
    });
}


// Function used in breed
function createBreed(event)
{
    event.preventDefault();
    form = event.target;
    const formdata = new FormData(form);

    fetch("scripts/createbreed.php",{
        method: "POST",
        body: formdata
    })
    .then(Response => Response.text())
    .then(data => {
        const messagebox = document.getElementById("messagebox");
        messagebox.innerHTML = data;
        setTimeout(() => {
            messagebox.innerHTML = "";
            populateBreed();
        }, 5000);
    })
    getBreeds(new Event('submit'));
}


function getBreeds(event)
{
    event.preventDefault();
    form = document.getElementById("getBreeds");
    const formdata = new FormData(form);

    fetch("scripts/getbreeds.php",{
        method: "POST",
        body: formdata
    })
    .then(Response => Response.text())
    .then(data => {
        const breedsdiv = document.getElementById("breeds");
        breedsdiv.innerHTML = data;
    })
    .catch(error => {
        console.log(error);
    });
}


let originalValue = '';

function editBreed(id) {
    const input = document.getElementById("editbreed"+id);
    originalValue = input.value;
    input.removeAttribute("disabled");
    input.focus();
}

function cancelEdit(id) {
    const input = document.getElementById("editbreed"+id);
    input.value = originalValue;
    input.setAttribute("disabled", true);
}

function updateBreed(event, id) {
    event.preventDefault();
    const input = document.getElementById("editbreed"+id);
    const newValue = input.value.trim();
    
    // If value didn't change, just disable the field
    if (newValue === originalValue) {
        input.setAttribute("disabled", true);
        return;
    }
    
    // Send update to server
    fetch("scripts/setbreed.php", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({id: id, breed: newValue})
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.text();
    })
    .then(data => {
        // Update the originalValue to the new value since update was successful
        originalValue = newValue;
        
        // Show success message
        const messagebox = document.getElementById("messagebox");
        messagebox.innerHTML = data;
        setTimeout(() => {
            messagebox.innerHTML = "";
        }, 5000);
        
        // Keep the new value but disable the input
        input.setAttribute("disabled", true);
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert to original value on error
        input.value = originalValue;
        input.setAttribute("disabled", true);
        
        // Show error message
        const messagebox = document.getElementById("messagebox");
        messagebox.innerHTML = data;
        setTimeout(() => {
            messagebox.innerHTML = "";
        }, 5000);
    });
}


function deleteBreed(id)
{
    let formdata = new FormData();
    formdata.append('id',id);
    fetch("scripts/deletebreed.php",{
        method: 'POST',
        body: formdata
    })
    .then(Response => Response.text())
    .then(data => {
        // Show error message
        const messagebox = document.getElementById("messagebox");
        messagebox.innerHTML = data;
        setTimeout(() => {
            messagebox.innerHTML = "";
        }, 5000);
        getBreeds(new Event('submit'));
    });
}

// to display adoption requests on admin page
function getAdoptionRequests()
{
    fetch("scripts/getadoptionrequests.php")
    .then(Response => Response.text())
    .then(data => {
        const div = document.getElementById("adoptionrequests");
        div.innerHTML = data;
    });
}
getAdoptionRequests();

// to accept or reject adoption request
// this function is called using getadoptionrequests.php
function setAdoptionRequestStatus(option,adoptionrequestsid)
{
    fetch('scripts/setadoptionrequeststatus.php',{
        method: "POST",
        headers:{
            'Content-Type':'application/json'
        },
        body: JSON.stringify({option:option,id:petid})
    })
    .then(Response=>Response.text())
    .then(data => {
        // to display message
        const messagebox = document.getElementById("messagebox");
        messagebox.innerHTML = data;
        setTimeout(() => {
            messagebox.innerHTML = "";
        }, 5000);
        getAdoptionRequests();
    });
}