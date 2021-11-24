var oMotivosAjustesStock = oMotivosAjustesStock || {
    motivos_modal: document.getElementById("oMotivosAjustesStock").getAttribute("data-motivosAjustesStockModal")
};

oMotivosAjustesStock.fnGuardaMotivoModal = function(){
    let motivo = document.getElementById('modalMotivoAjusteStock_motivo').value;
    if(motivo == ''){
        document.getElementById('modalMotivoAjusteStock_motivo').focus();
        return false;
    }

    let formData = new FormData();
        formData.append("tipo", document.getElementById('modalMotivoAjusteStock_tipo').value);
        formData.append("motivo", motivo);
        formData.append("token", document.getElementById('modalMotivoAjusteStock_token').value);

    fetch(this.motivos_modal, {
        method: 'post',
        mode: 'cors',
        credentials: 'same-origin',
        body: formData
    }).then(function(response){
        return response.json();
    }).then(function(json){
        let aclaracion = (json.tipo == 1 ? ' (+)' : ' (-)');
        oMotivosAjustesStock.agregarOpcion('ajuste_motivo', json.motivo+aclaracion, json.id);
        oGen.fnCerrarModal();
    }).catch(function(error){
        console.log('ERROR:', error);
    })
}

oMotivosAjustesStock.agregarOpcion = function(combo, texto, valor = 0){
    var select = document.getElementById(combo);
    var option = document.createElement("option");
    option.text = texto;
    if(valor != 0)
        option.value = valor;

    select.add(option);

    if(valor != 0)
        select.value = valor;
    else
        select.value = texto;
}