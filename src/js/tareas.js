(function () {

    obtenerTareas();
    let tareas = [];
    let filtradas = [];
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', function () {
        mostrarFormulario()
    });

    const filtros = document.querySelectorAll('#filtros input[type="radio');
    filtros.forEach(radio => {
        radio.addEventListener('input', filtrarTareas);
    })

    function filtrarTareas(e) {
        const filtro = e.target.value;
        //console.log(filtro); return;
        
        if (filtro !== '') {
            filtradas = tareas.filter(tarea => tarea.estado === filtro);
        } else {
            filtradas = [];
        }
        //console.log(filtradas);
        mostrarTareas();
    }


    async function obtenerTareas() {
        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?url=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();
            tareas = resultado.tareas;
            //console.log(tareas);
            mostrarTareas();
        } catch (error) {
            console.log(error);
        }
    }

    function totalPendientes() {
        const total = tareas.filter(tarea => tarea.estado==='0').length;
        const pendientes = document.querySelector('#pendientes');
        //console.log(total);
        if(total === 0) {
            pendientes.disabled = true;
        } else {
            pendientes.disabled = false;
        }
        
    }
    function totalCompletas() {
        const total = tareas.filter(tarea => tarea.estado==='1').length;
        const completadas = document.querySelector('#completadas');
        //console.log(total);
        if(total === 0) {
            completadas.disabled = true;
        } else {
            completadas.disabled = false;
        }
        
    }
    function mostrarTareas() {
        limpiarTareas();
        totalPendientes();
        totalCompletas();
        arrayTareas=filtradas.length ? filtradas : tareas;
        if (tareas.length === 0) {
            const listado = document.querySelector('#listado-tareas');
            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No hay tareas';
            listado.appendChild(textoNoTareas);
            textoNoTareas.classList.add('no-tareas');
            return;
        }
        estado = {
            0: 'Pendiente',
            1: 'Completada'
        }
        arrayTareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaid = tarea.id;
            contenedorTarea.classList.add('tarea');
            //Nombre tarea
            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function () {
                mostrarFormulario(editar = true, { ...tarea });
            }
            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');
            //botones
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estado[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estado[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function () {
                cambiarEstadoTarea({ ...tarea });
            }
            //botones
            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.ondblclick = function () {
                confirmarEliminarTarea({ ...tarea });
            }
            //agregar eventos
            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);
            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);
            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenedorTarea);
        });
    }
    function confirmarEliminarTarea(tarea) {
        Swal.fire({
            title: "¿Eliminar Tarea?",
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: `No`
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            }
        });
        /*  
        const confirmar = confirm('¿Deseas eliminar esta tarea?');
        if (confirmar) {
            eliminarTarea(tarea);
        }
        */
    }
    async function eliminarTarea(tarea) {
        const { estado, id, nombre, proyectoId } = tarea;
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());
        try {
            const url = '/api/tarea/eliminar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            console.log(resultado);

            if (resultado.resultado) {
                /*
                mostrarAlerta(
                    resultado.resultado.mensaje,
                    resultado.resultado.tipo,
                    document.querySelector('.contenedor-nueva-tarea')
                );  
                */
                Swal.fire(
                    'Tarea Eliminada',
                    resultado.resultado.mensaje,
                    'success'
                )

                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
                mostrarTareas();
            }

        }
        catch (error) {
            console.log(error);
        }
    }
    function mostrarFormulario(editar = false, tarea = {}) {
        console.log(tarea);
        const modal = document.createElement('div');
        modal.classList.add('modal');
        modal.innerHTML = `
        <form class="formulario nueva-tarea">
        <legend>${editar ? 'Editar Tarea' : 'Añade una nueva Tarea'}</legend>
            <div class="campo">
                <label for="tarea">Tarea</label>
                <input 
                type="text" 
                id="tarea"
                placeholder="${tarea.nombre ? 'Edita la Tarea' : 'Añadir tarea al proyecto actual'}"
                value="${tarea.nombre ? tarea.nombre : ''}">
            </div>
            <div class="opciones">
                <input 
                type="submit" 
                class="submit-nueva-tarea" 
                value="${editar ? 'Guardar Cambios' : 'Añadir Tarea'}" >
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
                const nombreTarea = document.querySelector('#tarea').value;

                if (nombreTarea === '') {
                    mostrarAlerta('No se puede agregar una tarea vacía', 'error', document.querySelector('.formulario legend'));

                    return;
                }
                if (editar) {
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                } else {
                    agregarTarea(nombreTarea);
                }

            }
        })

        document.querySelector('.dashboard').appendChild(modal);
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
        datos.append('nombre', tarea);
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
            if (resultado.tipo === 'exito') {
                setTimeout(() => {
                    document.querySelector(".modal").remove();
                }, 3000);
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                }
                //console.log(tareaObj);
                tareas = [...tareas, tareaObj];
                //console.log(tareas);
                //return;

                mostrarTareas();

            }
        } catch (error) {
            console.log(error);
        }
    }
    function cambiarEstadoTarea(tarea) {
        const nuevoEstado = tarea.estado === '0' ? '1' : '0';
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
        //console.log(tarea.estado);
    }
    async function actualizarTarea(tarea) {

        const { estado, id, nombre, proyectoId } = tarea;
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());
        /*
           for( let valor of datos.values()){
               console.log(valor);
           }
        */
        try {
            const url = '/api/tarea/actualizar';
            const respuesta = await fetch(url, {
                method: "POST",
                body: datos
            });
            //return;
            const resultado = await respuesta.json();
            //console.log(resultado.respuesta);
            const modal = document.querySelector('.modal');
            mostrarAlerta(
                resultado.respuesta.mensaje,
                resultado.respuesta.tipo,
                document.querySelector(modal ? '.formulario legend' : '.contenedor-nueva-tarea'));
            if (resultado.respuesta.tipo === 'exito') {
                setTimeout(() => {

                    if (modal) {
                        modal.remove();
                    }
                }, 3000);
                tareas = tareas.map(tareaMemoria => {
                    if (tareaMemoria.id === id) {
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    return tareaMemoria;

                });
                mostrarTareas();
            }
            //console.log(respuesta);        
        } catch (error) {
            console.log(error);
        }
    }
    function obtenerProyecto() {
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
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
    function limpiarTareas() {
        const listadoTareas = document.querySelector('#listado-tareas');
        while (listadoTareas.firstChild) {
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
        // listadoTareas.innerHTML = '';
    }

})();