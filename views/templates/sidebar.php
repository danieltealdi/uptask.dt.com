<aside class="sidebar">
    <div class="contenedor-sidebar">
        <h2>UpTask</h2>

        <div class="cerrar-menu">
            <img id="cerrar-menu" src="build/img/cerrar.svg" alt="imagen cerrar menu">
        </div>
    </div>
    
    <nav class='sidebar-nav'>
    <a class="<?php echo $titulo === 'Proyectos' ? 'active' : '' ?>" href="/dashboard">Proyectos</a>
    <a class="<?php echo $titulo === 'Crear Proyecto' ? 'active' : '' ?>" href="/crear-proyecto">Crear Proyecto</a>
    <a class="<?php echo $titulo === 'Perfil' ? 'active' : '' ?>" href="/perfil">Perfil</a>
    </nav>
    <div class="cerrar-sesion-mobile">
        <a href="/logout" class="cerrar-sesion">Cerrar Sesión</a>
    </div>
</aside>