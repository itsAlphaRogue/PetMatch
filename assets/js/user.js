// getting adoption requests made by user
function getAdoptionRequests()
{
    fetch("scripts/useradoptionrequests.php")
    .then(Response => Response.text())
    .then(data => {
        const adoptiondiv = document.getElementById("adoptionrequestsdiv");
        adoptiondiv.innerHTML = data;
    });
}getAdoptionRequests();


// getting user profile details

function getUserAccountDetails()
{
    fetch("scripts/useraccountdetails.php")
    .then(Response => Response.json())
    .then(data => {
        const name = document.getElementById("name"); 
        const email = document.getElementById("email");
        const phone = document.getElementById("phone");

        name.value = data.name;
        email.value = data.email;
        phone.value = data.phone;
    })
}getUserAccountDetails();


// to update user details
function updateUserAccountDetails(event)
{
    event.preventDefault();
    const form = event.target;
    let formdata = new FormData(form);

    fetch("scripts/updateuseraccountdetails.php",{
        method: 'POST',
        body: formdata
    })
    .then(Response => Response.text())
    .then(data =>{
        // to display message
        const messagebox = document.getElementById("messagebox");
        messagebox.innerHTML = data;
        setTimeout(() => {
            messagebox.innerHTML = "";
        }, 5000);
    });
}



//change password
function changePassword(event)
{
    event.preventDefault();
    form = event.target;
    let formdata = new FormData(form);

    fetch("scripts/changeuserpassword.php",{
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