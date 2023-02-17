<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
            // debuguear($auth);
            if(empty($alertas)){
                // Comprobar si existe usuario
                $usuario = Usuario::where("email", $auth->email);
                if($usuario){
                    if($usuario->comprobarPasswordAndVerify($auth->password)){
                        // Autentica Usuario
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre.' '.$usuario->apellido;
                        $_SESSION['emai'] = $usuario->email;
                        $_SESSION['login'] = true;
                        if($usuario->admin === "1"){
                            header("Location: /admin");
                        }else{
                            header("Location: /cita");
                        }
                    }
                }else{
                    Usuario::setAlerta("error", "usuario no encontrado");
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }
    public static function logout(){
        echo "Desde Logout";
    }

    public static function olvide(Router $router){
        $router->render("auth/olvide-password", [

        ]);
    }
    public static function recuperar(){
        echo "Recuperar Password";
    }
    public static function crear(Router $router){
        $usuario = new Usuario($_POST);
        // Alertas vacias
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // debuguear($usuario);
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar si alerta esta vacion
            if(empty($alertas)){
                // Verificar si existe usuario
                $resultado = $usuario->existeUsuario();
                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    // Hashear password
                    $usuario->hashPassword();

                    // No está registrado
                    $usuario->crearToken();

                    // Enviar Email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $usuario->guardar();
                    if($resultado){
                        header("Location: /mensaje");
                    }
                    // debuguear($usuario);
                }
            }

        }
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render("auth/mensaje", [

        ]);
    }

    public static function confirmar(Router $router){
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)){
            // Mostrar mensaje de error
            Usuario::setAlerta('error', "Token no Valido");
        }else{
            // Modificar Usuario confirmado
            // echo "Token valido";
            $usuario->confirmado = 1;
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta("exito", "Cuenta Comprobada Correctamente");
        }
        $alertas = Usuario::getAlertas();
        $router->render("auth/confirmar-cuenta", [
            'alertas' => $alertas
        ]);
    }

}

?>