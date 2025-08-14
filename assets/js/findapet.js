// displays all pets in findapet.html
function displayPets()
{
    fetch("scripts/displaypets.php")
    .then(Response => Response.text())
    .then(data =>{
        const div = document.getElementById("pets");
        div.innerHTML = data;
    });
}displayPets();

// displays pets according to filter in findapet.html
function filterPets(event)
{
    event.preventDefault();
    form = event.target;
    formdata = new FormData(form);
    fetch("scripts/filterpets.php",{
        method: "POST",
        body: formdata
    })
    .then(Response => Response.text())
    .then(data => {
        const div = document.getElementById("pets");
        div.innerHTML = data;
    })
}

// Functions to control filter options behaviour
function showfilter()
{
    let filterx = document.getElementById("filterx");
    if(window.innerWidth <= 1280)
    {
        filterx.style.display = "block";
    }

    height();
    width();
}

function height()
{
    let filter = document.getElementById("filter");
    if(window.innerWidth < 360)
    {
        filter.style.height = "285px";
    }
    else if(window.innerWidth <1280)
    {
        filter.style.height = "225px";  
    }
    else
    {
        filter.style.height = "280px";
    }
}

function width()
{
    if(window.innerWidth<=430)
    {
        filter.style.width = window.innerWidth* (90/100) + "px";
    }
    else
    {
        filter.style.width = "344px";
    }
}

window.onresize = function()
{
    let filter = document.getElementById("filter");

    if(filter.clientHeight > 36)
    {
        height();
        width();
    }

    let filterx = document.getElementById("filterx");
    if(window.innerWidth < 1280)
    {
        filterx.style.display = "block";
    }
    else
    {
        filterx.style.display = "none";
    }

    if(window.innerWidth >= 1280)
    {
        showfilter();
    }
};

showfilter();

function hidefilter()
{
    let filter = document.getElementById("filter");
    filter.style.height = "36px";
    filter.style.width = "60px";

    let filterx = document.getElementById("filterx");
    filterx.style.display = "none";
}
