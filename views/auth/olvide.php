<div class="contenedor olvide">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php';
    ?>

<div class="contenedor-sm">
    <p class="descripcion-pagina">Entra tu email para restablecer tu password</p>
    <form action="/olvide" method="POST" class="formulario">
    
            <div class="campo">
            <label for="email">Email</label>
            <input 
            type="email" 
            id="email" 
            placeholder="Tu Email" 
            name="email">
            </div>
            
    
            <input type="submit" class="boton" value="Enviar Instrucciones">
</form>

</div>
<div class="acciones">
    <a href="/">¿Ya tienes cuenta? Iniciar Sesión</a>
    <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
</div>
</div>
