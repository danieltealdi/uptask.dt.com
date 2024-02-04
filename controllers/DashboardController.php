<?php
namespace Controllers;
use MVC\Router;
//use Model\Proyecto;
//use Model\Tarea;
use Model\Usuario;
use Classes\Mailer;

class DashboardController
{
    public static function index(Router $router)
    {
        
        session_start();
        isAuth();
        if(!isset($_SESSION['id'])) {
            header('Location: /');
        }
        //$proyectos = Proyecto::belongsTo('propietarioId', $_SESSION['id']);
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
        //$proyectos = Proyecto::belongsTo('propietarioId', $_SESSION['id']);
        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'proyectos' => $proyectos
        ]);
    }
    public static function perfil(Router $router)
    {
        
        session_start();
        isAuth();
        if(!isset($_SESSION['id'])) {
            header('Location: /');
        }
        //$proyectos = Proyecto::belongsTo('propietarioId', $_SESSION['id']);
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'proyectos' => $proyectos
        ]);
    }
}