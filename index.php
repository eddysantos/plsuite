<?php
date_default_timezone_set('America/Monterrey');
session_start();
// $root = $_SERVER['DOCUMENT_ROOT'];
// require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$url = "";

function detectDevice(){
	$userAgent = $_SERVER["HTTP_USER_AGENT"];
	$devicesTypes = array(
        "computer" => array("msie 10", "msie 9", "msie 8", "windows.*firefox", "windows.*chrome", "x11.*chrome", "x11.*firefox", "macintosh.*chrome", "macintosh.*firefox", "opera"),
        "tablet"   => array("tablet", "android", "ipad", "tablet.*firefox"),
        "mobile"   => array("mobile ", "android.*mobile", "iphone", "ipod", "opera mobi", "opera mini"),
        "bot"      => array("googlebot", "mediapartners-google", "adsbot-google", "duckduckbot", "msnbot", "bingbot", "ask", "facebook", "yahoo", "addthis")
    );
 	foreach($devicesTypes as $deviceType => $devices) {
        foreach($devices as $device) {
            if(preg_match("/" . $device . "/i", $userAgent)) {
                $deviceName = $deviceType;
            }
        }
    }
    return ucfirst($deviceName);
 	}



if (isset($_POST['login'])) {
  if (isset($_POST['userName']) && isset($_POST['password'])) {
    include ('Resources/PHP/loginDatabase.php');

    $usuario = $_POST['userName'];
    $pass = $_POST['password'];

    // include('Resources/PHP/loginDatabase.php');

    $loginQry = "SELECT *, u.pkIdUsers , u.Nombre , u.Apellido , u.NombreUsuario , u.TipoUsuario , u.Status , u.NombreUsuario , up.invoice_control_save ic_save FROM users u LEFT JOIN users_permisos up ON u.pkIdUsers = up.fkid_user WHERE NombreUsuario = ? AND Contrasena = ?";

    $stmt = $db->prepare($loginQry) or die ('Error Login('.$db->errno.'): '.$db->error);
    $stmt->bind_param('ss',$usuario, $pass);
    $stmt->execute();
    $results = $stmt->get_result();
    $row = $results->fetch_array(MYSQLI_ASSOC);

    $validador = $results->num_rows;

    if ($validador == 1) {
			if ($row['cred_american_portal']) {
			 $url = "location:Ubicaciones/";
			 $_SESSION['current_portal'] = "us";
		 } elseif ($row['cred_mexican_portal']) {
		 	$url = "location:mxportal/";
			$_SESSION['current_portal'] = "mx";
		} else {
			$url = "/access_denied.php";
		}

      $_SESSION['user_info'] = $row;
      setcookie('Nombre',$row['Nombre']);
      setcookie('Apellido',$row['Apellido']);
      setcookie('Usuario',$row['NombreUsuario']);
      setcookie('idUsuario',$row['pkIdUsers']);
      setcookie('nombreUsuario', $row['NombreUsuario']);

      if (detectDevice() == "Mobile") {
        header('location:Ubicaciones/registroHoras.php');
      } else {
        header($url);
      }

      exit();
    }
  }

}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="Resources/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="Resources/CSS/main.css">
    <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="Resources/CSS/mainMobile.css">
    <link href="https://fonts.googleapis.com/css?family=Sansita" rel="stylesheet">
  </head>
  <body>
		<div class="container-fluid login-box">
			<div class="login-wrapper">
				<div class="login-header">
					<img src="Resources/images/logo.png" style="height: 150px" alt="">
				</div>
				<div class="login-info">
					<form class="form-group p-5" method="post">
						<input type="text" class="form-control login-input" name="userName" placeholder="Username" value="">
						<input type="password" class="form-control login-input mt-3" name="password" placeholder="Password" value="">
						<br>
						<div class="">
							<!-- <a href="#" class="text-secondary">Forgot password?</a> -->
							<input type="submit" class="btn btn-outline-primary float-right" name="login" id="login" value="Login">
						</div>
					</form>
				</div>
			</div>
		</div>
    <!-- jQuery first, then Tether, then Bootstrap JS. -->
		<script src="Resources/JQuery/jquery-3.2.1.min.js" charset="utf-8"></script>
    <!--script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script-->
    <script src="Resources/Bootstrap_4_1_1/js/bootstrap.min.js"></script>
  </body>
</html>
