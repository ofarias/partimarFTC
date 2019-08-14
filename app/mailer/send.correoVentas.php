<?php
    require_once('app/mailer/class.phpmailer.php'); // Contiene las funciones para envio de correo
    require_once('app/mailer/class.smtp.php'); // Envia correos mediante servidores SMTP
    $mail = new PHPMailer(true); // Se crea una instancia de la clase phpmailer
    $mail->IsSMTP(); // Establece el tipo de mensaje html
    $exec = $_SESSION['exec'];
    $titulo = $_SESSION['titulo'];
    $mensaje = "<p>";
    $contacto = "ofarias0424@gmail.com";
    $correo = $_SESSION['correo'];
    $correos = explode(",", $correo);
    $correoUsuario=$_SESSION['user']->USER_EMAIL;
    $mensajec = '';
    $mensaje1='Solicito para el cliente: '.$_SESSION['cliente'];
    foreach ($exec as $data):
         if (strpos($correo, "@")>1){
             $mensaje = '<br/>Producto: <b>'.$data->CLAVE.' : '.$data->DESCR.'</b> --> <font color="blue">'.$data->CANTIDAD.'</font> -- '.$data->UNIDAD.', '.$data->FECHA;
             $mensaje.= '<br/>Existencia el momento de la solicitud : <font color="red">'. $data->EXISTENCIAS.'</font>';
             $mensajec = $mensajec.$mensaje;
        } else {
             echo "No se ha localizado el correo electr&oacute;nico a quien enviar. No se va a enviar el correo.";
             return;
         }
    endforeach;
     $asunto = "Solicitud de Producto ".$_SESSION['user']->NOMBRE;  
     //$mensaje.= "<p>Le informamos que hemos creado una cuenta por pagar a nombre de $contacto con una fecha estiamda de pago del $promesaPago.</p>";
     //$mensaje.= "<p>Gracias por su confianza<br />Atentamente Ferretera Pegaso</p>";
     try {  
         $mail->Username   = "ofarias0424@gmail.com";  // Nombre del usuario SMTP
         $mail->Password   = "elPaso35+";                    // Contraseña del servidor SMTP
        for ($i=0; $i<count($correos); $i++) { 
            $correo= $correos[$i];
            $mail->AddAddress($correo);//Direccion a la que se envia 
         }
         $mail->AddAddress($correoUsuario);
         $mail->SetFrom('ofarias0424@gmail.com' , "Partimar. Solicitud de Productos Ventas"); // Esccribe datos de contacto
         $mail->Subject = $asunto;
         $mail->AltBody = 'Para ver correctamente este mensaje, por favor usa un manejador de correo con compatibilidad HTML !'; // optional - MsgHTML will create an alternate automatically
         $mail->MsgHTML($mensaje1.'<br/>'.$mensajec.'<br/><br/> Gracias y quedo al pendiente de su pronta respuesta.<br/><br/> Atentamente: <b>'.$_SESSION['user']->NOMBRE.'<b/>');
         $mail->Send();
     } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
     } catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
     }
 ?>