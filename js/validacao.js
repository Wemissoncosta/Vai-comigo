var form = document.getElementById('form');

    form.onsubmit = ((ev) => {
        var matricula = document.getElementById('matricula').value;
        var senha = document.getElementById('senha').value;
        ev.preventDefault()
        if (matricula == "admin" && senha == "admin") {
            location.href = "home.html"
        }else{
            document.getElementById("text").innerText= "Credenciais incorretas."
        }
    })