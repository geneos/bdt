<?php

require 'email/PHPMailerAutoload.php';


/**
 * Esta clase permite enviar emails.
 * 
 *
 */
class Email {

	/**
	 * Método que muestra el contenido de una vista
	 */
	
	public static function enviar($destinatario, $nombre, $titulo, $accion, $item, $estado) {

		$mail = new PHPMailer();
		//$mail->SMTPDebug = 3;                               	// Enable verbose debug output
		
		$mail->isSMTP();                                      	// Set mailer to use SMTP
		$mail->Host = 'smtp1.servage.net';  					// Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               	// Enable SMTP authentication
		$mail->Username = 'bdt@graduadostandil.org.ar';    		// SMTP username
		$mail->Password = 'bdt732graduados';                           	// SMTP password
		$mail->SMTPSecure = 'tls';                            	// Enable TLS encryption, `ssl` also accepted
		$mail->Port = 25;                                    	// TCP port to connect to
		
		$mail->From = 'bdt@graduadostandil.org.ar';
		$mail->FromName = 'Banco de Tiempo';
		$mail->addAddress($destinatario, $nombre);
		$mail->addBCC('bdt@graduadostandil.org.ar');
		
		$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML
		
		$mail->Subject = $titulo;
		$mail->Body    .= 'Hola ' . $nombre . '. Gracias por utilizar el Banco de Tiempo de la Asociación Graduados Tandil.<br>';
		$mail->Body    .= 'Se ha ' . $accion . ' una ' . $item . ' con estado ' . $estado . '.<br>';
		$mail->Body    .= 'Tenes que ingresar a la aplicación del Banco de Tiempo para gestionarla mediante el siguiente enlace <a href="http://bdt.graduadostandil.org.ar">BDT</a><br>';
		$mail->AltBody    = 'Hola ' . $nombre . '. Gracias por utilizar el Banco de Tiempo de la Asociación Graduados Tandil. Se ha ' . $accion . ' una ' . $item . ' con estado ' . $estado . '. Tenes que ingresar a la aplicación del Banco de Tiempo para gestionarla mediante  la URL http://bdt.graduadostandil.org.ar';
		
		
		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message has been sent';
		}
		
		
	}
	
}
