(function () {
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);
    function mostrarFormulario() {
        const modal = document.createElement('div');
        modal.classList.add('modal');
        modal.innerHTML = `
        <form class="formulario nueva-tarea">
        <legend>Añade una nueva Tarea</legend>
            <div class="campo">
                <label for="tarea">Tarea</label>
                <input 
                type="text" 
                id="tarea"
                placeholder="Añadir tarea al proyecto actual">
            </div>
            <div class="opciones">
                <input 
                type="submit" 
                class="submit-nueva-tarea" 
                value="Añadir Tarea"/>
                <button type="button" class="cerrar-modal">Cancelar</button>
            </div>
                `;
        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);
        modal.addEventListener('click', (e) => {
            e.preventDefault();
            if (e.target.classList.contains('cerrar-modal')) {
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 500);
            }
            if (e.target.classList.contains('submit-nueva-tarea')) {
                submitFormularioNuevaTarea();
            }
        })

        document.querySelector('.dashboard').appendChild(modal);
    }
})();
function submitFormularioNuevaTarea() {
    const tarea = document.querySelector('#tarea').value.trim();
    if (tarea.length === 0) {
        mostrarAlerta('No se puede agregar una tarea vacía', 'error', document.querySelector('.formulario legend'));

        return;
    }
    agregarTarea(tarea);
}
function mostrarAlerta(mensaje, tipo, referencia) {
    const alertaPrevia = document.querySelector('.alerta');
    if (alertaPrevia) {
        alertaPrevia.remove();
    }
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta', tipo);
    //console.log(referencia);return;
    //const formulario=document.querySelector('.formulario');
    referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);
    setTimeout(() => {
        alerta.remove();
    }, 5000);
    return alerta;
}
async function agregarTarea(tarea) {
    const datos = new FormData();
    datos.append('nombre',tarea);
    datos.append("proyectoId", obtenerProyecto());
    //console.log(datos);return;
    try {
        const url = '/api/tarea';
        const respuesta = await fetch(url, {
            method: "POST",
            body: datos
        });
        //console.log(respuesta);
        const resultado = await respuesta.json();
        mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));
        if(resultado.tipo==='exito'){
            setTimeout(() => {
                document.querySelector(".modal").remove();
            }, 3000);
            
        }
    } catch (error) {
        console.log(error);
    }
}
function obtenerProyecto(){
    const proyectoParams= new URLSearchParams(window.location.search);
    const proyecto=Object.fromEntries(proyectoParams.entries());
    //console.log(proyecto.url);
    return proyecto.url;
}
async function consultarAPI() {

    try {
        const url = '/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);

    } catch (error) {
        console.log(error);
    }
}