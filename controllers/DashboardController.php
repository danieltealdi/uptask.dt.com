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
        $alertas=[];
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
    public static function proyecto(Router $router){

        session_start();
        isAuth();
        if(!isset($_SESSION['id'])) {
            header('Location: /');
        }
        $url = $_GET['url'];
        //debuguear($url);
        if(!$url){
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
        //$proyectos = Proyecto::belongsTo('propietarioId', $_SESSION['id']);
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'proyectos' => $proyectos
        ]);
    }
}