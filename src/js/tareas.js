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
                    const formulario= document.querySelector('.formulario');
                    formulario.classList.add('animar');
                }, 0);
                modal.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (e.target.classList.contains('cerrar-modal')) {
                        const formulario= document.querySelector('.formulario');
                        formulario.classList.add('cerrar');
                        setTimeout(() => {
                            modal.remove();
                        }, 500);
                    }
                })
        document.querySelector('body').appendChild(modal);
    }
})();