var oRep = oRep || {
    marcas_modal: document.getElementById("oReparaciones").getAttribute("data-marcasModal"),
    modelos_modal: document.getElementById("oReparaciones").getAttribute("data-modelosModal"),
    tareas_modal: document.getElementById("oReparaciones").getAttribute("data-tareasModal")
};

oCli.cargaCliente = function(id, txt){
    document.getElementById('buscador_clientes').value=txt;
    document.getElementById('hiddenClienteId').value=id;
    document.getElementById('hiddenClienteId').dispatchEvent(new Event('change'));
    oCli.ocultarBuscadorClientes();
}

oRep.fnGuardaMarcaModal = function(){
    let formData = new FormData();
        formData.append("marca", document.getElementById('modalMarca_marca').value);
        formData.append("token", document.getElementById('modalMarca_token').value);

    fetch(this.marcas_modal, {
        method: 'post',
        mode: 'cors',
        credentials: 'same-origin',
        body: formData
    }).then(function(response){
        return response.json();
    }).then(function(json){
        oRep.agregarOpcion('reparacion_marca', json.marca);
        oGen.fnCerrarModal();
    }).catch(function(error){
        console.log('ERROR:', error);
    })
}

oRep.fnGuardaModeloModal = function(){
    let formData = new FormData();
        formData.append("modelo", document.getElementById('modalModelo_modelo').value);
        formData.append("token", document.getElementById('modalModelo_token').value);

    fetch(this.modelos_modal, {
        method: 'post',
        mode: 'cors',
        credentials: 'same-origin',
        body: formData
    }).then(function(response){
        return response.json();
    }).then(function(json){
        oRep.agregarOpcion('reparacion_modelo', json.modelo);
        oGen.fnCerrarModal();
    }).catch(function(error){
        console.log('ERROR:', error);
    })
}

oRep.fnGuardaTareaModal = function(){
    let formData = new FormData();
        formData.append("tarea", document.getElementById('modalTarea_tarea').value);
        formData.append("token", document.getElementById('modalTarea_token').value);

    fetch(this.tareas_modal, {
        method: 'post',
        mode: 'cors',
        credentials: 'same-origin',
        body: formData
    }).then(function(response){
        return response.json();
    }).then(function(json){
        oRep.agregarOpcion('reparacion_tarea', json.tarea);
        oGen.fnCerrarModal();
    }).catch(function(error){
        console.log('ERROR:', error);
    })
}

oRep.agregarOpcion = function(combo, texto, valor = 0){
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