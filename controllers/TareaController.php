<?php

namespace Controllers;

use Model\ActiveRecord;
use Model\Tarea;
use MVC\Router;
use Model\Proyecto;

class TareaController
{
    public static function index()
    {
        //debuguear('index');
        $proyectoId = $_GET['url'];
        //debuguear($proyectoId);
        if(!$proyectoId) {
            header('Location: /dashboard');
        }
        $proyecto = Proyecto::where('url', $proyectoId);
        session_start();
        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /404');
        }
        
        //$proyectos = Proyecto::belongsTo('proyectoId');
        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);
        //debuguear($tareas);
        echo json_encode(['tareas' => $tareas]);
    }


    public static function crear()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            session_start();

            $proyectoId = $_POST['proyectoId'];

            $proyecto = Proyecto::where('url', $proyectoId);

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            // Todo bien, instanciar y crear la tarea
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea Creada Correctamente',
                'proyectoId' => $proyecto->id
            ];
            echo json_encode($respuesta);

        }
    }

    public static function actualizar()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tarea = new Tarea($_POST);
            $tarea->guardar();
        }
    }
    public static function eliminar()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tarea = new Tarea($_POST);
            $tarea->eliminar();
        }
    }




}
