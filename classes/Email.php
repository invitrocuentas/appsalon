<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token){
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = 'bbc1ea9275383c';
            $mail->Password = '80fde517226d79';
            $mail->Port = 2525;
    
            $mail->isHTML(true);
            $mail->CharSet = "UTF-8";
            $mail->setFrom("cuentas@appsalon.com", "Mailer");
            $mail->addAddress("cuentas@appsalon.com", "AppSalon.com");
            $mail->Subject = "Confirma tu cuenta";
            $contenido = '<html>';
            $contenido .= '<p><strong>Hola '.$this->email.'</strong> Has creado tu cuenta en AppSalon, solo debes confirmarla presionando el siguiente enlace</p>';
            $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar-cuenta?token=".$this->token."'>Confimar Cuenta</a></p>";
            $contenido .="</html>";
            $mail->Body = $contenido;
            $mail->send();

        

    }

}