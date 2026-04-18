function matcher(event)
{
    event.preventDefault();
    const form = event.target;
    const formdata = new FormData(form);

    fetch("scripts/matcher.php",{
        method: 'POST',
        body: formdata
    })
    .then(Response => Response.text())
    .then(data => {
        const answer = document.getElementById("answer");
        answer.innerHTML = data;
    })
}