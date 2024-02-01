<div class="contenedor reestablecer">
	<?php include_once __DIR__ . '/../templates/nombre-sitio.php';
	?>

	<div class="contenedor-sm">
		<p class="descripcion-pagina">Entra tu nuevo password</p>
		<form action="/reestablecer" method="POST" class="formulario">
			
			<div class="campo">
				<label for="password">Password</label>
				<input type="password" id="password" placeholder="Tu Password" name="password">
			</div>
			<input type="submit" class="boton" value="Guardar Password">
		</form>
		<div class="acciones">
		<a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
		<a href="/olvide">¿Olvidaste tu Password?</a>
	</div>
	</div>
	
</div>