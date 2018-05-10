<?php

date_default_timezone_set('America/Monterrey');

$fecha = date('Ym',strtotime('previous month'));
$ano = date('Y',strtotime('previous month'));
$mes = date('m',strtotime('previous month'));


$xmlRiesgo = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'.
  '<archivo xsi:schemaLocation="http://www.uif.shcp.gob.mx/recepcion/adu adu.xsd"
  xmlns="http://www.uif.shcp.gob.mx/recepcion/adu"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"></archivo>');

$xmlRiesgo_informe = $xmlRiesgo->addChild('informe');
$xmlRiesgo_informe_mesReportado = $xmlRiesgo_informe->addChild('mes_reportado',$fecha);
$xmlRiesgo_informe_sujetoObligado = $xmlRiesgo_informe->addChild('sujeto_obligado');
$xmlRiesgo_informe_sujetoObligado->addChild('clave_sujeto_obligado','VIVR521128LU2');
$xmlRiesgo_informe_sujetoObligado->addChild('clave_actividad','ADU');

$contenidoXML = $xmlRiesgo->asXML();
echo $contenidoXML;

$archivoXML = 'archivosXML/'.$ano.$mes.".xml";
$crearXML = fopen($archivoXML,'w') or die ('No se pudo crear el archivo..');
fwrite($crearXML, $contenidoXML);
fclose($crearXML);

require 'PHPMailer-master/PHPMailerAutoload.php';

$cuerpoCorreoAlt = "Hola, \n adjunto encontrará el XML de las actividades de riesgo.";


$cuerpoCorreo = "Hola, \n adjunto encontrará el XML de las actividades de riesgo.";

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->CharSet = 'UTF-8';

$mail->Host       = "ssl://mail.prolog-mex.com";
$mail->SMTPDebug  = 0;
$mail->SMTPAuth   = true;
$mail->Port       = 465;
$mail->Username   = 'no-reply';
$mail->Password   = 'n0r3pl1';

$mail->setFrom('no-reply@prolog-mex.com', 'System Administrator');
$mail->addAddress('jbelmares@prolog-mex.com','Jesus Belmares');
$mail->addAddress('esantos@prolog-mex.com','Eduardo Santos');
$mail->isHTML(true);

$mail->Subject    = 'XML: Actividades de Riesgo';
$mail->Body       = $cuerpoCorreo;
$mail->altBody    = $cuerpoCorreoAlt;
$mail->AddAttachment($archivoXML);
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

if(!$mail->Send()) {
echo "Mailer Error: " . $mail->ErrorInfo;
} else {
echo "Message sent!";
}

 ?>
