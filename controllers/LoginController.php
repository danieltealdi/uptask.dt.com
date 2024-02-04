<?php

namespace Controllers;

use MVC\Router;

use Model\Usuario;
use Classes\Mailer;

//use PHPMailer\PHPMailer\PHPMailer;

class LoginController
{
    public static function login(Router $router)
    {
        //debuguear('login');
        
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
            if(empty($alertas)) {
                // Comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);
                if($usuario&&$usuario->confirmado) {
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
                        
                            header('Location: /dashboard');
                        
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado o confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        
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
                    $email = new Mailer($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    // Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide', [
            'titulo' => 'Olvidé mi Password',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router)
    {

        $alertas = [];
        $error = false;
        $token = s($_GET['token']);
        if(!$token) {
            header('Location: /');
        }
        //var_dump($token);
        $usuario = Usuario::where('token', $token);
        //var_dump($usuario);die;
        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //echo "aqui";
            $password = new Usuario($_POST);
            //var_dump($password);
            $alertas = $password->validarPassword();
            //var_dump($alertas);
            
            if(empty($alertas)) {
                //var_dump($password->password);
                //var_dump($usuario);
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                //var_dump($usuario);die;
                $resultado=$usuario->guardar();
                //debuguear($resultado);
                if($resultado) {
                    header('Location: /');
                }
                
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router)
    {
        //debuguear('crear');
        $alertas = [];
        $usuario = new Usuario();
        //debuguear($usuario);
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            //debuguear($alertas);

            if(empty($alertas)) {
                $existe = $usuario->where('email', $usuario->email);
                if($existe) {
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    $usuario->hashPassword();
                    unset($usuario->password2);
                    $usuario->crearToken();
                    //var_dump($usuario);
                    //var_dump(Mailer);
                    $email = new Mailer($usuario->email, $usuario->nombre, $usuario->token);
                    //$email = new Mailer;
                    //debuguear($email);
                    $email->enviarConfirmacion();
                    $resultado = $usuario->guardar();
                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }

        }

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

        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            $usuario->confirmado = "1";
            $usuario->token = null;
            unset($usuario->password2);
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta confirmada');
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            'titulo' => 'Confirmar Cuenta',
            'alertas' => $alertas
        ]);
    }

}
