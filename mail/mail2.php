<?

date_default_timezone_set('America/Bogota'); //Se define la zona horaria
require_once('class.phpmailer.php'); //Incluimos la clase phpmailer


/*
Usuario:             Abastecimiento
 
Contrase�a:       Ab4$t3c1m13nt0 (Cotrase�a nunca caduca)
 
Mailbox:            Notificaciones, Abastecimiento
 
Correo:              abastecimiento@hocol.com.co
*/


function envia_correos($destino,$asunto,$mensaje_envio,$cabesa)
	{
   $mail = new PHPMailer(true); // Declaramos un nuevo correo, el parametro true significa que mostrara excepciones y errores.

$mail->IsSMTP(); // Se especifica a la clase que se utilizar� SMTP


//------------------------------------------------------
  $correo_emisor="abastecimiento@hocol.com.co";     //Correo a utilizar para autenticarse
					     //Gmail o de GoogleApps
  $nombre_emisor="Notificaciones, Abastecimiento";               //Nombre de quien env�a el correo
  $contrasena='Ab4$t3c1m13nt0';          //contrase�a de tu cuenta en Gmail
  $correo_destino=$destino;      //Correo de quien recibe
  $nombre_destino="Proveedor";                //Nombre de quien recibe   	
//--------------------------------------------------------
  //$mail->SMTPDebug  = 2;                     // Habilita informaci�n SMTP (opcional para pruebas)
                                             // 1 = errores y mensajes
                                             // 2 = solo mensajes
  $mail->SMTPAuth   = true;                  // Habilita la autenticaci�n SMTP
  //$mail->SMTPSecure = "ssl";                 // Establece el tipo de seguridad SMTP
  $mail->Host       = "192.168.100.30";      // Establece Gmail como el servidor SMTP
  $mail->Port       = 25;                   // Establece el puerto del servidor SMTP de Gmail
  $mail->Username   = $correo_emisor;  	     // Usuario Gmail
  $mail->Password   = $contrasena;           // Contrase�a Gmail
  //A que direcci�n se puede responder el correo
  $mail->AddReplyTo($correo_emisor, $nombre_emisor);
  //La direccion a donde mandamos el correo
  $mail->AddAddress($correo_destino, $nombre_destino);
  //De parte de quien es el correo
  $mail->SetFrom($correo_emisor, $nombre_emisor);
  //Asunto del correo
  $mail->Subject = $asunto;
  //Mensaje alternativo en caso que el destinatario no pueda abrir correos HTML
  $mail->AltBody = $asunto;
  //El cuerpo del mensaje, puede ser con etiquetas HTML
  $mail->MsgHTML($mensaje_envio);
  //Archivos adjuntos
  //$mail->AddAttachment('img/logo.jpg');      // Archivos Adjuntos
  
  //Enviamos el correo
  $mail->Send();
  


	}


envia_correos("rene.sterling@enternova.net","prueba hocol","prueba hocol","5");


?>