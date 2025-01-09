<?php

namespace Classes;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Email
{

    public $id;
    public $id_partido;
    public $email;
    public $apellido1;
    public $apellido2;
    public $nombre;
    public $fecha;
    public $hora;
    public $terreno;
    public $categoria;
    public $grupo;
    public $local;
    public $visitante;
    public $observaciones;


    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->id_partido = $data['id_partido'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->apellido1 = $data['apellido1'] ?? '';
        $this->apellido2 = $data['apellido2'] ?? '';
        $this->nombre = $data['nombre'] ?? '';
        $this->fecha = $data['fecha'] ?? '';
        $this->hora = $data['hora'] ?? '';
        $this->terreno = $data['terreno'] ?? '';
        $this->categoria = $data['categoria'] ?? '';
        $this->grupo = $data['grupo'] ?? '';
        $this->local = $data['local'] ?? '';
        $this->visitante = $data['visitante'] ?? '';
        $this->observaciones = $data['observaciones'] ?? '';
    }

    public function enviarConfirmacion()
    {

        // create a new object
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@devwebcamp.com');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'Confirma tu Cuenta';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->nombre .  "</strong> Has Registrado Correctamente tu cuenta en DevWebCamp; pero es necesario confirmarla</p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['HOST'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a>";
        $contenido .= "<p>Si tu no creaste esta cuenta; puedes ignorar el mensaje</p>";
        $contenido .= '</html>';
        $mail->Body = $contenido;

        //Enviar el mail
        $mail->send();
    }

    // public function enviarInstrucciones()
    // {

    //     // create a new object
    //     $mail = new PHPMailer();
    //     $mail->isSMTP();
    //     $mail->Host = $_ENV['EMAIL_HOST'];
    //     $mail->SMTPAuth = true;
    //     $mail->Port = $_ENV['EMAIL_PORT'];
    //     $mail->Username = $_ENV['EMAIL_USER'];
    //     $mail->Password = $_ENV['EMAIL_PASS'];

    //     $mail->setFrom('arbitros@cuentasrfaf.es');
    //     $mail->addAddress($this->email, $this->nombre);
    //     $mail->Subject = 'Reestablece tu contraseña';

    //     // Set HTML
    //     $mail->isHTML(TRUE);
    //     $mail->CharSet = 'UTF-8';

    //     $contenido = '<html>';
    //     $contenido .= " Hola, <strong>" . $this->nombre .  "</strong> <br><br>El Administrador a restablecido tu perfil de la web de gestión del IMD, sigue el siguiente enlace para crear tu nueva contraseña.</p>";
    //     $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['HOST'] . "/admin/perfiles/restablecer?email=" . $this->email . "'>Crear contraseña</a>";
    //     // $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje.</p>";
    //     $contenido .= '</html>';
    //     $mail->Body = $contenido;

    //     //Enviar el mail
    //     $mail->send();
    // }

    public function enviarInstrucciones()
    {
        try {
            // Crear un nuevo objeto PHPMailer
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['EMAIL_PORT'];
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASS'];

            $mail->setFrom('arbitros@cuentasrfaf.es');
            $mail->addAddress($this->email, $this->nombre);
            $mail->Subject = 'Reestablece tu contraseña';

            // Configuración HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            $contenido = '<html>';
            $contenido .= "Hola, <strong>" . $this->nombre .  "</strong><br><br>El Administrador ha restablecido tu perfil de la web de gestión del IMD. Sigue el siguiente enlace para crear tu nueva contraseña.</p>";
            $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['HOST'] . "/admin/perfiles/restablecer?email=" . $this->email . "'>Crear contraseña</a>";
            $contenido .= '</html>';
            $mail->Body = $contenido;

            // Enviar el correo
            if ($mail->send()) {
                return true; // Envío exitoso
            } else {
                return false; // Envío fallido
            }
        } catch (Exception $e) {
            // Registrar el error para depuración
            error_log('Error al enviar el email: ' . $e->getMessage());
            return false; // Envío fallido
        }
    }


    public function enviar_partido()
    {

        try {
            // create a new object
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['EMAIL_PORT'];
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASS'];

            $mail->setFrom('arbitros@cuentasrfaf.es');
            // $mail->setFrom('Comité Árbitros@cuentasrfaf.es');
            $mail->addAddress($this->email, $this->nombre);
            $mail->Subject = 'Aviso desde el Comité de Árbitros';

            // Set HTML
            $mail->isHTML(TRUE);
            $mail->CharSet = 'UTF-8';

            $contenido = '<html>';

            $contenido .= '<head>';
            $contenido .= '<style>';
            $contenido .= 'body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }';
            $contenido .= 'p { margin: 10px 0; }';
            $contenido .= 'a { color: #0066cc; text-decoration: none; }';
            $contenido .= 'a:hover { text-decoration: underline; }';
            $contenido .= '.title { font-size: 13px; font-weight: bold; color: #000; }';
            $contenido .= '.info { margin-left: 20px; }';
            $contenido .= '.negrito { font-size: 12px; font-weight: bold; background-color:rgb(209, 191, 29); color:rgb(1, 25, 49); padding: 3px; border-radius: 5px; }';

            // Estilos para los botones Aceptar y Rechazar
            $contenido .= '.buttons { display: flex; justify-content: center; align-items: center; margin-top: 20px; }';
            $contenido .= '.accept { background-color: rgb(60, 118, 73); color: white; padding: 10px 20px; border-radius: 5px; text-align: center; margin-left: 55px; margin-right: 20px; }'; // Agregar margen derecho
            $contenido .= '.reject { background-color: rgb(149, 15, 28); color: white; padding: 10px 20px; border-radius: 5px; text-align: center; }';
            $contenido .= '.buttons a { text-decoration: none; color: white; font-weight: bold; }';
            $contenido .= '</style>';
            $contenido .= '</head>';

            $contenido .= '<body>';

            $contenido .= '<p class="title">Le ha sido asignada la siguiente designación:</p><br>';
            $contenido .= '<p class="info">Designación:&nbsp;<span class="">' . $this->id . '</span></p>';
            $contenido .= '<p class="info">Para: ' . $this->apellido1 . ' ' . $this->apellido2 . ', ' . $this->nombre . '</p>';
            $contenido .= '<p class="info">En función de: ÁRBITRO</p><br>';

            $contenido .= '<p class="info">Fecha partido: ' . $this->fecha . '</p>';
            $contenido .= '<p class="info">Hora comienzo: ' . $this->hora . ' H</p><br>';

            $contenido .= '<p class="info">Campo: ' . $this->terreno . '</p>';
            $contenido .= '<p class="info">Localidad: Sevilla</p><br>';

            $contenido .= '<p class="info">Competición: IMD</p>';
            $contenido .= '<p class="info">ID partido: <span class="negrito">' . $this->id_partido . '</span></p>';
            $contenido .= '<p class="info">Grupo: ' . $this->grupo . '</p>';
            $contenido .= '<p class="info">Categoría: ' . $this->categoria . '</p>';
            $contenido .= '<p class="info">Equipo de casa: ' . $this->local . '</p>';
            $contenido .= '<p class="info">Equipo visitante: ' . $this->visitante . '</p><br>';

            $contenido .= '<div class="buttons">';
            $contenido .= '<a href="' . $_ENV['HOST'] . '/paginas/aceptar?id_partido=' . $this->id_partido . '" class="accept">Aceptar</a>';
            $contenido .= '<a href="' . $_ENV['HOST'] . '/paginas/motivo_rechazo?id_partido=' . $this->id_partido . '" class="reject">Rechazar</a>';
            $contenido .= '</div><br>';

            $contenido .= '<p class="info">En caso de suspensión del partido, introduce el ID DEL PARTIDO en el siguiente enlace: <a href="https://forms.office.com/e/mcqeAWEjvA">Enlace partidos suspendidos</a></p><br>';

            $contenido .= '</body>';
            $contenido .= '</html>';

            $mail->Body = $contenido;




            //Enviar el mail
            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar el correo: " . $e->getMessage());
            throw $e;
        }
    }

    public function enviar_partido_editado()
    {

        try {
            // create a new object
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['EMAIL_PORT'];
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASS'];

            $mail->setFrom('arbitros@cuentasrfaf.es');
            // $mail->setFrom('Comité Árbitros@cuentasrfaf.es');
            $mail->addAddress($this->email, $this->nombre);
            $mail->Subject = 'Aviso desde el Comité de Árbitros';

            // Set HTML
            $mail->isHTML(TRUE);
            $mail->CharSet = 'UTF-8';

            $contenido = '<html>';

            $contenido .= '<head>';
            $contenido .= '<style>';
            $contenido .= 'body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }';
            $contenido .= 'p { margin: 10px 0; }';
            $contenido .= 'a { color: #0066cc; text-decoration: none; }';
            $contenido .= 'a:hover { text-decoration: underline; }';
            $contenido .= '.title { font-size: 13px; font-weight: bold; color: #000; }';
            $contenido .= '.info { margin-left: 20px; }';
            $contenido .= '.negrito { font-size: 12px; font-weight: bold; background-color:rgb(209, 191, 29); color:rgb(1, 25, 49); padding: 3px; border-radius: 5px; }';

            // Estilos para los botones Aceptar y Rechazar
            $contenido .= '.buttons { display: flex; justify-content: center; align-items: center; margin-top: 20px; }';
            $contenido .= '.accept { background-color: rgb(60, 118, 73); color: white; padding: 10px 20px; border-radius: 5px; text-align: center; margin-left: 55px; margin-right: 20px; }'; // Agregar margen derecho
            $contenido .= '.reject { background-color: rgb(149, 15, 28); color: white; padding: 10px 20px; border-radius: 5px; text-align: center; }';
            $contenido .= '.buttons a { text-decoration: none; color: white; font-weight: bold; }';
            $contenido .= '</style>';
            $contenido .= '</head>';

            $contenido .= '<body>';

            $contenido .= '<p class="title">Los datos de su designación han sido modificados:</p>';
            $contenido .= '<p class="info">Modificación: ' . $this->observaciones . '</p><br>';
            $contenido .= '<p class="info">Designación:&nbsp;<span class="">' . $this->id . '</span></p>';
            $contenido .= '<p class="info">Para: ' . $this->apellido1 . ' ' . $this->apellido2 . ', ' . $this->nombre . '</p>';
            $contenido .= '<p class="info">En función de: ÁRBITRO</p><br>';

            $contenido .= '<p class="info">Fecha partido: ' . $this->fecha . '</p>';
            $contenido .= '<p class="info">Hora comienzo: ' . $this->hora . ' H</p><br>';

            $contenido .= '<p class="info">Campo: ' . $this->terreno . '</p>';
            $contenido .= '<p class="info">Localidad: Sevilla</p><br>';

            $contenido .= '<p class="info">Competición: IMD</p>';
            $contenido .= '<p class="info">ID partido: <span class="negrito">' . $this->id_partido . '</span></p>';
            $contenido .= '<p class="info">Grupo: ' . $this->grupo . '</p>';
            $contenido .= '<p class="info">Categoría: ' . $this->categoria . '</p>';
            $contenido .= '<p class="info">Equipo de casa: ' . $this->local . '</p>';
            $contenido .= '<p class="info">Equipo visitante: ' . $this->visitante . '</p><br>';

            $contenido .= '<div class="buttons">';
            $contenido .= '<a href="' . $_ENV['HOST'] . '/paginas/aceptar?id_partido=' . $this->id_partido . '" class="accept">Aceptar</a>';
            $contenido .= '<a href="' . $_ENV['HOST'] . '/paginas/motivo_rechazo?id_partido=' . $this->id_partido . '" class="reject">Rechazar</a>';
            $contenido .= '</div><br>';

            $contenido .= '<p class="info">En caso de suspensión del partido, introduce el ID DEL PARTIDO en el siguiente enlace: <a href="https://forms.office.com/e/mcqeAWEjvA">Enlace partidos suspendidos</a></p><br>';

            $contenido .= '</body>';
            $contenido .= '</html>';

            $mail->Body = $contenido;




            //Enviar el mail
            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar el correo: " . $e->getMessage());
            throw $e;
        }
    }

    public function cancelar_partido()
    {
        try {
            // Crear un nuevo objeto PHPMailer
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['EMAIL_PORT'];
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASS'];

            $mail->setFrom('arbitros@cuentasrfaf.es');
            $mail->addAddress($this->email, $this->nombre);
            $mail->Subject = 'Aviso desde el Comité de Árbitros';

            // Configurar para enviar el correo en formato HTML
            $mail->isHTML(TRUE);
            $mail->CharSet = 'UTF-8';

            // Construir el contenido HTML del correo
            $contenido = '<html>';

            $contenido .= '<head>';
            $contenido .= '<style>';
            $contenido .= 'body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }';
            $contenido .= 'p { margin: 10px 0; }';
            $contenido .= 'a { color: #0066cc; text-decoration: none; }';
            $contenido .= 'a:hover { text-decoration: underline; }';
            $contenido .= '.title { font-size: 13px; font-weight: bold; color: #000; }';
            $contenido .= '.info { margin-left: 20px; }';
            $contenido .= '.negrito { font-size: 12px; font-weight: bold; background-color:rgb(209, 191, 29); color:rgb(1, 25, 49); padding: 3px; border-radius: 5px; }';

            // Estilos para los botones Aceptar y Rechazar
            $contenido .= '.buttons { display: flex; justify-content: center; align-items: center; margin-top: 20px; }';
            $contenido .= '.accept { background-color: rgb(60, 118, 73); color: white; padding: 10px 20px; border-radius: 5px; text-align: center; margin-left: 55px; margin-right: 20px; }'; // Agregar margen derecho
            $contenido .= '.reject { background-color: rgb(149, 15, 28); color: white; padding: 10px 20px; border-radius: 5px; text-align: center; }';
            $contenido .= '.buttons a { text-decoration: none; color: white; font-weight: bold; }';
            $contenido .= '</style>';
            $contenido .= '</head>';

            $contenido .= '<body>';

            $contenido .= '<p class="title">Su designación ha sido cancelada:</p><br>';
            $contenido .= '<p class="info">Designación:&nbsp;<span class="">' . $this->id . '</span></p>';
            $contenido .= '<p class="info">Para: ' . $this->apellido1 . ' ' . $this->apellido2 . ', ' . $this->nombre . '</p>';
            $contenido .= '<p class="info">En función de: ÁRBITRO</p><br>';

            $contenido .= '<p class="info">Fecha partido: ' . $this->fecha . '</p>';
            $contenido .= '<p class="info">Hora comienzo: ' . $this->hora . ' H</p><br>';

            $contenido .= '<p class="info">Campo: ' . $this->terreno . '</p>';
            $contenido .= '<p class="info">Localidad: Sevilla</p><br>';

            $contenido .= '<p class="info">Competición: IMD</p>';
            $contenido .= '<p class="info">ID partido: <span class="negrito">' . $this->id_partido . '</span></p>';
            $contenido .= '<p class="info">Grupo: ' . $this->grupo . '</p>';
            $contenido .= '<p class="info">Categoría: ' . $this->categoria . '</p>';
            $contenido .= '<p class="info">Equipo de casa: ' . $this->local . '</p>';
            $contenido .= '<p class="info">Equipo visitante: ' . $this->visitante . '</p><br>';

            $contenido .= '</body>';
            $contenido .= '</html>';

            $mail->Body = $contenido;

            // Intentar enviar el correo
            if ($mail->send()) {
                return true; // Retornar true si el correo se envía correctamente
            } else {
                // Si no se pudo enviar, logueamos el error y devolvemos false
                error_log("Error al enviar el correo: " . $mail->ErrorInfo);
                return false;
            }
        } catch (Exception $e) {
            // En caso de excepción, logueamos el error y lanzamos la excepción
            error_log("Excepción al enviar el correo: " . $e->getMessage());
            return false;
        }
    }
}
