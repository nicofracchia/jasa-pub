var oCli = oCli || {
    clientes_buscar: document.getElementById("oClientes").getAttribute("data-clientesBuscar"),
    clientes_modal: document.getElementById("oClientes").getAttribute("data-clientesModal")
};

oCli.buscaClientes = function (self){
    if(self.value.length > 2){
        let formData = new FormData();
        formData.append("busqueda", self.value);

        fetch(this.clientes_buscar, {
            method: 'post',
            mode: 'cors',
            credentials: 'same-origin',
            body: formData
        }).then(function(response){
            return response.json();
        }).then(function(json){
            oCli.cargaBuscadorClientes(json);
            oCli.mostrarBuscadorClientes();
        }).catch(function(error){
            console.log('ERROR:', error);
        })
    }else{
        oCli.ocultarBuscadorClientes();
    }
}

oCli.ocultarBuscadorClientes = function (){
    document.getElementById('modalClientes').style.display = 'none';
}

oCli.mostrarBuscadorClientes = function (){
    document.getElementById('modalClientes').style.display = 'flex';
}

oCli.cargaBuscadorClientes = function (json){
    document.getElementById('resultadosClientes').innerHTML = '';
    json.forEach(function(item, index){
        let tr = document.createElement('tr');
            tr.onclick = function(){
                txt = item.dni + ' - ' + item.apellido + ', ' + item.nombre + ' - ' + item.mail;
                oCli.cargaCliente(item.id, txt);
            }
        let td1 = document.createElement('td');
            td1.innerHTML = item.dni;
        let td2 = document.createElement('td');
            td2.innerHTML = item.apellido + ', ' + item.nombre;
        let td3 = document.createElement('td');
            td3.innerHTML = item.mail;
        tr.append(td1);
        tr.append(td2);
        tr.append(td3);
        document.getElementById('resultadosClientes').append(tr);
    });
}

oCli.cargaCliente = function(id, txt){
    document.getElementById('buscador_clientes').value=txt;
    document.getElementById('hiddenClienteId').value=id;
    document.getElementById('hiddenClienteId').dispatchEvent(new Event('change'));
    oCli.ocultarBuscadorClientes();
}

oCli.fnGuardaClienteModal = function(){

    if(
        document.getElementById('modalCliente_rs').value == '' && 
        ( 
            document.getElementById('modalCliente_nombre').value == '' || 
            document.getElementById('modalCliente_apellido').value == '' 
        ) 
    ){
        alert('Debe ingresar nombre y apellido o razon social para guardar el cliente.');
        return false;
    }

    let formData = new FormData();
        formData.append("razon_social", document.getElementById('modalCliente_rs').value);
        formData.append("nombre", document.getElementById('modalCliente_nombre').value);
        formData.append("apellido", document.getElementById('modalCliente_apellido').value);
        formData.append("mail", document.getElementById('modalCliente_mail').value);
        formData.append("dni", document.getElementById('modalCliente_dni').value);
        formData.append("cuit", document.getElementById('modalCliente_cuit').value);
        formData.append("telefono", document.getElementById('modalCliente_telefono').value);
        formData.append("direccion", document.getElementById('modalCliente_direccion').value);
        formData.append("cuenta_corriente", document.getElementById('modalCliente_cc').checked);
        formData.append("token", document.getElementById('modalCliente_token').value);

    fetch(this.clientes_modal, {
        method: 'post',
        mode: 'cors',
        credentials: 'same-origin',
        body: formData
    }).then(function(response){
        return response.json();
    }).then(function(json){
        txt = json.dni + ' - ' + json.apellido + ', ' + json.nombre + ' - ' + json.mail;
        oCli.cargaCliente(json.id, txt);
        oGen.fnCerrarModal();
    }).catch(function(error){
        console.log('ERROR:', error);
    })
}