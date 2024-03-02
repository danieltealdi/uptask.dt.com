<?php

namespace Controllers;

use MVC\Router;
//use Model\Tarea;
use Model\Usuario;
use Classes\Mailer;
use Model\Proyecto;

class DashboardController
{
    public static function index(Router $router)
    {

        session_start();
        isAuth();
        if(!isset($_SESSION['id'])) {
            header('Location: /');
        }
        $proyectos = Proyecto::belongsTo('propietarioId', $_SESSION['id']);
        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }
    public static function crear_proyecto(Router $router)
    {

        session_start();
        isAuth();
        if(!isset($_SESSION['id'])) {
            header('Location: /');
        }
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();
            if(empty($alertas)) {
                $proyecto->url = md5(uniqid());
                $proyecto->propietarioId = $_SESSION['id'];
                $proyecto->guardar();
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }

        //$proyectos = Proyecto::belongsTo('propietarioId', $_SESSION['id']);
        $router->render('dashboard/crear-proyecto', [
            'alertas' => $alertas,
            'titulo' => 'Crear Proyecto',
            'proyectos' => $proyectos
        ]);
    }
    public static function proyecto(Router $router)
    {

        session_start();
        isAuth();
        if(!isset($_SESSION['id'])) {
            header('Location: /');
        }
        $url = $_GET['url'];
        //debuguear($url);
        if(!$url) {
            header('Location: /dashboard');
        }
        $proyecto = Proyecto::where('url', $url);
        //debuguear($proyecto);
        if($proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }
        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto,
            'proyecto' => $proyecto
        ]);
    }
    public static function perfil(Router $router)
    {

        session_start();
        isAuth();
        if(!isset($_SESSION['id'])) {
            header('Location: /');
        }
        $usuario = Usuario::find($_SESSION['id']);
        $alertas = [];
        //debuguear($usuario);
        //$proyectos = Proyecto::belongsTo('propietarioId', $_SESSION['id']);
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar_perfil();

            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);
                if($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    // Mensaje de error
                    Usuario::setAlerta('error', 'Email no válido, ya pertenece a otra cuenta');
                    $alertas = $usuario->getAlertas();
                } else {
                    // Guardar el registro
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Guardado correctamente');
                    $alertas = $usuario->getAlertas();
                    $_SESSION['nombre'] = $usuario->nombre;
                    $_SESSION['email'] = $usuario->email;
                }
            }
        }
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    public static function cambiar_password(Router $router)
    {
        //debuguear('cambiar password');

        session_start();
        isAuth();
        if(!isset($_SESSION['id'])) {
            header('Location: /');
        }
        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);
        //debuguear($usuario);
        //$proyectos = Proyecto::belongsTo('propietarioId', $_SESSION['id']);
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->nuevo_password();

            if(empty($alertas)) {
                //$existeUsuario = Usuario::where('email', $usuario->email);
                $resultado = $usuario->comprobar_password();
                if(!$resultado) {
                    // Mensaje de error
                    Usuario::setAlerta('error', 'Password incorrecto');
                    $alertas = $usuario->getAlertas();
                } else {
                    // Guardar el registro
                    $usuario->password = $usuario->password_nuevo;
                    $usuario->hashPassword();
                    unset($usuario->password_nuevo);
                    unset($usuario->password_actual);
                    if($usuario->guardar()) {
                        Usuario::setAlerta('exito', 'Password cambiado correctamente');
                        $alertas = $usuario->getAlertas();
                    }

                }
            }
        }
        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
}
