
function shownav()
{
    let nav = document.getElementById("nav");
    nav.style.display = "block";
    setTimeout(() => {
        nav.style.right = "0px";
    }, 10); 
}

function hidenav()
{
    let nav = document.getElementById("nav");
    nav.style.right = "-190px";
    setTimeout(() => {
        nav.style.display = "none";
    }, 200);
}


function loginAJAX(event) 
{
    event.preventDefault();

    const form = document.getElementById("loginform");
    const formdata = new FormData(form);
    const submit = document.getElementById("submit");
    const message = document.getElementById("message");

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'scripts/login.php', true);

    xhr.onload = function () 
    {
        if (xhr.status === 200) 
        {
            const res = JSON.parse(xhr.responseText);

            if (res.status === 'error') 
            {
                message.innerText = res.message;
                message.classList.remove("hidden");

                // Hide after 5 seconds
                setTimeout(() => {
                    message.classList.add("hidden");
                }, 5000);

                submit.innerHTML = "Submit";
            } 
            else if (res.status === 'redirect') 
            {
                if (res.location === 'home') 
                {
                    window.location.href = "/petmatch";
                } 
                else if (res.petid) 
                {
                    window.location.href = "/petmatch/pet?id=" + res.petid;
                }
            }
        }
    };

    xhr.onprogress = function () {
        submit.innerHTML = "Processing...";
    };

    xhr.send(formdata);
}



function registerAJAX(event)
{
    event.preventDefault();

    const form = document.getElementById("registerform");
    const formdata = new FormData(form);

    const submit = document.getElementById("submit");

    const message = document.getElementById("message");

    const xhr = new XMLHttpRequest();

    xhr.open('POST','scripts/register.php',true)

    xhr.onprogress = function()
    {
        submit.innerHTML = "Loading...";
    }

    xhr.onload = function()
    {
        if(xhr.status === 200)
        {
            submit.innerHTML = "Submit"
            if(xhr.response === "success")
            {
                window.location.href = "/petmatch/login";
            }
            else if(xhr.response != '')
            {
                message.style.display = "block";
                message.innerHTML = xhr.response;
            }
        }
    }

    xhr.send(formdata)
}


function populateBreed()
{
    fetch('scripts/populatebreed.php')
    .then(data => data.text())
    .then(data => {
        // const selectbreed = document.getElementById("selectbreed");
        let temp = '<option value="">Breed</option>';
        temp += data;
        
        document.querySelectorAll(".selectbreed").forEach(e =>
        {
            e.innerHTML = temp;
        });
    })
    .catch(e => {
        console.log(e);
    });
}
populateBreed();

// for admin dashboard and user dashboard

function hidedivs() {
    let divs = ['adoptionrequests', 'profile', 'logout', 'pet', 'breed', 'logout'];

    divs.forEach(id => {
        let el = document.getElementById(id);
        if (el) {
            el.classList.add('hidden');
        }
    });
}

function showdivs(id) {
    hidedivs(); 

    let el = document.getElementById(id);
    if (el) {
        el.classList.remove('hidden');
    }
}



// utility functions
// function showMessage(message,type)
// {
//     const messagebox = document.getElementById("messagebox");
//     messagebox.innerHTML = message;
//     messagebox.classList.remove("hidden");
    
//     setTimeout(() => {
//         messagebox.innerHTML = "";
//         messagebox.classList.add("hidden");
//     }, 5000);

//     switch(type)
//     {
//         case "success":
//             messagebox.classList.add("bg-green-400");
//             setTimeout(() => {
//                 messagebox.classList.remove("bg-green-400");
//             }, 5000);
//             break;

//         case "info":
//             messagebox.classList.add("bg-blue-400");
//             setTimeout(() => {
//                 messagebox.classList.remove("bg-blue-400");
//             }, 5000);
//             break;

//         case "error":
//             messagebox.classList.add("bg-red-400");
//             setTimeout(() => {
//                 messagebox.classList.remove("bg-red-400");
//             }, 5000);
//             break;
//     }
// }