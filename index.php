<?php
session_start();
$xmlusers = simplexml_load_file("xml/users.xml");
$username = "";
$password = "";
$error_message = "";
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    for ($i = 0; $i < sizeof($xmlusers); $i++) {
        if ($xmlusers->user[$i]->username == $username && $xmlusers->user[$i]->password == $password) {
            //var_dump($xmlusers->user[$i]['id']);

            $_SESSION['userid'] = (string) $xmlusers->user[$i]['id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = (string) $xmlusers->user[$i]['role'];
            header("Location:home.php");
        } else {
            $error_message = "<div class='alert alert-danger'> Username/Password is invalid </div>";
        }
    }
}
?>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>Ticketing System</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </head>
	<div class="jumbotron bg-primary">
    <h1 class="text-center">Ticket Support Centre</h1>
    
</div>
    <body>
        <form id="loginform" method="post" action=""> 
           
			<div class="form-group row">
				<label for="username" class="col-sm-3 col-form-label">User Name : </label>
				<div class="col-sm-6">
				  <input type="text" class="form-control" id="username" name="username" />
				</div>
			</div>
            
			<div class="form-group row">
				<label for="password" class="col-sm-3 col-form-label">Password : </label>
				<div class="col-sm-6">
				  <input type="text" class="form-control" id="password" name="password" />
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-10">
				 <button type="submit" class="btn btn-primary" id="loginbtn" Name="login">Log In</button>
				</div>
			</div>

        </form>
		<?= $error_message ?>
		<?php include_once "footer.php"; ?>
    </body>
</html>