var oCaja = oCaja || {
    movimientos_modal: document.getElementById("oCaja").getAttribute("data-movimientosModal")
};

oCaja.fnGuardaMovimientoModal = function(){
    let formData = new FormData();
        formData.append("tipo", document.getElementById('modalMovimiento_tipo').value);
        formData.append("movimiento", document.getElementById('modalMovimiento_movimiento').value);
        formData.append("token", document.getElementById('modalMovimiento_token').value);

    fetch(this.movimientos_modal, {
        method: 'post',
        mode: 'cors',
        credentials: 'same-origin',
        body: formData
    }).then(function(response){
        return response.json();
    }).then(function(json){
        let aclaracion = (json.tipo == 1 ? ' (+)' : ' (-)');
        oCaja.agregarOpcion('caja_movimiento', json.movimiento+aclaracion, json.id);
        oGen.fnCerrarModal();
    }).catch(function(error){
        console.log('ERROR:', error);
    })
}

oCaja.agregarOpcion = function(combo, texto, valor = 0){
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