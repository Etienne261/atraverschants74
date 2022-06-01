function loadDoc() {
    var zip_code = document.getElementById('zip_code');
    let xhttp = new XMLHttpRequest();
    document.getElementById('city').innerHTML="";
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const arrayJson = JSON.parse(this.response)
            const select = document.getElementById('city')
            arrayJson.forEach(element => {
                let nodeOption = document.createElement("option")
                nodeOption.value = element.nomCommune
                select.appendChild(nodeOption)
                nodeOption.innerHTML = element.nomCommune;
            });
        }
    };
    xhttp.open("GET", "https://apicarto.ign.fr/api/codes-postaux/communes/" + zip_code.value, true);
    xhttp.send();
}