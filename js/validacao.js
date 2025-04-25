var botao = document.getElementById('botao');
    botao.addEventListener('click', function logar(){
    var matricula = document.getElementById('matricula').value;
    var senha = document.getElementById('senha').value;

    if (matricula == "admin" && senha == "admin") {
        location.href="home.html"
    }
})