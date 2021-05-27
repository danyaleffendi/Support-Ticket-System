<?php

session_start();
//fetching user id of logged in user

$usertickets = [];

$tickets = simplexml_load_file("xml/tickets.xml");

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
}
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

//var_dump($userid);
//$tickets = $tickets->xpath("//ticket[@user_id=$userid]");
?>
<html lang="en">
	<head>
		<title>Dashboard</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	</head>
	<div class="jumbotron bg-primary">
    <h1 class="text-center">Ticket Support Centre</h1>
    <a class="btn btn-success" href="newticket.php">Create New</a>
    		
        <a class="btn btn-warning text-right logout" href="index.php">Log out</a>
    
	<p class="text-left text-white loginuser"> User : <?= $username ?></p>
    <p class="text-right text-white role"> Role : <?= $role ?></p>
	</div>
	<body>
		
		<div class="jumbotron">

		<div id="headings" class="row">
				<div class="col-sm-2">No.</div>
				<div class="col-sm-2">Ticket ID</div>				
				<div class="col-sm-2">Ticket Subject</div>
				<div class="col-sm-2">Ticket Created</div>
				<div class="col-sm-2">Ticket Status</div>
				<div class="col-sm-2">Details</div>
			</div>

		<!-- condition to check to identify role of logged in user -->
			<?php if ($role == 'Customer') { //if user is customer
       for ($i = 0; $i < sizeof($tickets); $i++) {
           if ($tickets->ticket[$i]['userid'] == $userid) {
               echo '<div id="listcticekt" class="row">
					<div class="col-sm-2">' .
                   ($i + 1) .
                   '</div>					
					<div class="col-sm-2"><a href = "clientViewTicket.php?id=' .
                   $tickets->ticket[$i]['id'] .
                   '">' .
                   $tickets->ticket[$i]['id'] .
                   '</a></div>					
					<div class="col-sm-2"><a href = "clientViewTicket.php?id=' .
                   $tickets->ticket[$i]['id'] .
                   '">' .
                   $tickets->ticket[$i]->subject .
                   '</a></div>
					<div class="col-sm-2">' .
                   date_format(date_create($tickets->ticket[$i]->issueDate), "Y/m/d H:i:s") .
                   '</div>
					<div class="col-sm-2">' .
                   $tickets->ticket[$i]->status .
                   '</div>
					<div class="col-sm-2"><a href = "clientViewTicket.php?id=' .
                   $tickets->ticket[$i]['id'] .
                   '">View Details</a></div>
						</div>';
           }
       }
   } else { //if user is admin or staff
       for ($i = 0; $i < sizeof($tickets); $i++) {
           echo '<div id="listcticekt" class="row">
					<div class="col-sm-2">' .
               ($i + 1) .
               '</div>					
					<div class="col-sm-2"><a href = "staffViewTicket.php?id=' .
               $tickets->ticket[$i]['id'] .
               '">' .
               $tickets->ticket[$i]['id'] .
               '</a></div>					
					<div class="col-sm-2"><a href = "staffViewTicket.php?id=' .
               $tickets->ticket[$i]['id'] .
               '">' .
               $tickets->ticket[$i]->subject .
               '</a></div>
					<div class="col-sm-2">' .
               date_format(date_create($tickets->ticket[$i]->issueDate), "Y/m/d H:i:s") .
               '</div>
					<div class="col-sm-2">' .
               $tickets->ticket[$i]->status .
               '</div>
					<div class="col-sm-2"><a href = "staffViewTicket.php?id=' .
               $tickets->ticket[$i]['id'] .
               '">View Details</a></div>
						</div>';
       }
   } ?>
		</div>
		<?php include_once "footer.php"; ?>
	</body>
</html>