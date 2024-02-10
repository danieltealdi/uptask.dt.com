(function () {
    obtenerTareas();
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

    async function obtenerTareas() {
        try{
            const id=obtenerProyecto();
            const url = `/api/tareas?url=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();
            const {tareas} = resultado;
            //console.log(tareas);
            mostrarTareas(tareas);
        }catch(error){
            console.log(error);
        }
    }

    function mostrarTareas(tareas) {
        if(tareas.length === 0) {
            const listado = document.querySelector('#listado-tareas');
            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No hay tareas';
            listado.appendChild(textoNoTareas);
            textoNoTareas.classList.add('no-tareas');
            return;
        }
        estado={
            0: 'Pendiente',
            1: 'Completada'
        }
        tareas.forEach(tarea => {
            const contenedorTarea=document.createElement('LI');
            contenedorTarea.dataset.tareaid=tarea.id;
            contenedorTarea.classList.add('tarea');
            const nombreTarea=document.createElement('P');
            nombreTarea.textContent=tarea.nombre;
            const opcionesDiv=document.createElement('DIV');
            opcionesDiv.classList.add('opciones');
            const btnEstadoTarea=document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estado[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent=estado[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea=tarea.estado;
            const btnEliminarTarea=document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.textContent='Eliminar';
            btnEliminarTarea.dataset.idTarea=tarea.id;
            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);
            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);
            const listadoTareas=document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenedorTarea);
        })
            /*
            const { nombre, id, estado } = tarea;
            const nuevaTarea = document.createElement('li');
            nuevaTarea.classList.add('tarea');
            nuevaTarea.dataset.id = id;
            if (estado) {
                nuevaTarea.classList.add('tarea-completa');
            }
            nuevaTarea.innerHTML = `
            <p>${nombre}</p>
            <div class="opciones">
                <button class="completa-tarea">
                    ${estado ? '✓' : '✗'}
                </button>
                <button class="elimina-tarea">
                </button>
            </div>
            `;
            const eliminaTareaBtn = nuevaTarea.querySelector('.elimina-tarea');
            eliminaTareaBtn.addEventListener('click', eliminaTarea);
            const completaTareaBtn = nuevaTarea.querySelector('.completa-tarea');
            completaTareaBtn.addEventListener('click', cambiaEstadoTarea);
            listado.appendChild(nuevaTarea);*/
        }
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