var oGen = oGen || {};

// GENERALES MODAL
oGen.fnCerrarModal = function(self){
    if(document.querySelector('.baseModal') !== null)
        document.querySelector('.baseModal').remove();
}

// MODAL GENERAL
oGen.fnCargaModal = function(ruta){

    fetch(ruta)
    .then(function(response){
        return response.text();
    }).then(function(html){
        
        oGen.fnCerrarModal();

        var parser = new DOMParser();
        var doc = parser.parseFromString(html, 'text/html');
        var modal = doc.querySelector('.baseModal');
        
        document.querySelector('section').append(modal);

    }).catch(function(error){
        console.log('ERROR:', error);
    })

}

// REGLAS PRECIOS PRODUCTOS
oGen.fnCargaReglaPreciosProductos = function(){
    let maxIdReglas = 0;
    document.querySelectorAll('[data-reglas="cantidad"]').forEach(function(item, index){
        let idRegla = item.dataset.idregla;
        if(idRegla > maxIdReglas)
            maxIdReglas = idRegla;
    });
    let i = maxIdReglas++;
    let HTML  = "<tr>";
        HTML += "   <td><input type='number' step='.01' name='reglasCantidad["+maxIdReglas+"]' data-reglas='cantidad' data-idRegla='"+maxIdReglas+"' placeholder='Cantidad' /></td>";
        HTML += "   <td><input type='number' step='.01' name='reglasPrecio["+maxIdReglas+"]' data-reglas='precio' data-idRegla='"+maxIdReglas+"' placeholder='Precio' /></td>";
        HTML += "   <td><img src='/images/iconos/borrar_rojo.svg' alt='Eliminar regla' title='Eliminar regla' onclick='oGen.fnElimiaReglaPreciosProductos(this)'></td>";
        HTML += "</tr>";

        document.getElementById('tablaReglasProductos').insertAdjacentHTML('beforeend', HTML);
}

oGen.fnElimiaReglaPreciosProductos = function(self){
    self.parentElement.parentElement.remove();
}