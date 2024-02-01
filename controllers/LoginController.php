<?php

namespace Controllers;

use MVC\Router;

use Model\Usuario;
use Classes\Email;
use PHPMailer\PHPMailer\PHPMailer;

class LoginController
{
    public static function login(Router $router)
    {
        //debuguear('login');
        /*
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
            if(empty($alertas)) {
                // Comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);
                if($usuario) {
                    // Verificar el password
                    if($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        // Autenticar el usuario
                        if(!isset($_SESSION)) {
                            session_start();
                        }
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        // Redireccionamiento
                        if($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        */
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout()
    {
        if(isset($_SESSION)) {
            $_SESSION = [];
            header('Location: /');
        }
    }

    public static function olvide(Router $router)
    {
        //debuguear($router);
        /*
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();
            if(empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);
                if($usuario && $usuario->confirmado === "1") {
                    // Generar un token
                    $usuario->crearToken();
                    $usuario->guardar();
                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    // Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        */
        $router->render('auth/olvide', [
            'titulo' => 'Olvidé mi Password',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router)
    {
        /*
        $alertas = [];
        $error = false;
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
            if(empty($alertas)) {
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $usuario->guardar();
                header('Location: /');
            }
        }
        $alertas = Usuario::getAlertas();
        */
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router)
    {
        //echo "aqui";
        //debuguear($router);
        /*
        $usuario = new Usuario();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar();
            if(empty($alertas)) {
                $existe = $usuario->existeEmail();
                if($existe) {
                    $alertas = Usuario::getAlertas();
                } else {
                    $usuario->hashPassword();
                    $usuario->crearToken();
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    $usuario->guardar();
                    header('Location: /mensaje');
                }
            }
        }
        */
        $router->render('auth/crear', [
            'titulo' => 'Crea tu cuenta en UpTask',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta creada exitosamente'
        ]);
    }

    public static function confirmar(Router $router)
    {
        /*
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta confirmada');
        }
        $alertas = Usuario::getAlertas();
        */
        $router->render('auth/confirmar', [
            'titulo' => 'Confirmar Cuenta',
            'alertas' => $alertas
        ]);
    }

}
