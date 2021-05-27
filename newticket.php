<?php
session_start();

//loading xml
$tickets = simplexml_load_file("xml/tickets.xml");

//fetching details of logged in user
$usertickets = [];
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
}
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

//getting ticket id for last ticket to auto increment
$lastid = $tickets->ticket[count($tickets->ticket) - 1]['id'];
$newid = $lastid + 1;

$time = new DateTime();
$current_datetime = $time->format(DateTime::ATOM);

//sending message
if (isset($_POST['createticket'])) {
    if ($_POST['subject'] != "" || $_POST['subject'] != null || $_POST['message'] != "" || $_POST['message']) {
        $subjectText = $_POST['subject'];
        $messageText = $_POST['message'];
        $orderid = $_POST['orderid'];

        $newticket = $tickets->addChild('ticket', '');
        $newticket->addAttribute('id', $newid);
        $newticket->addAttribute('userid', $userid);
        $newticket->addChild('issueDate', $current_datetime);
        $newticket->addChild('status', 'New');
        $newticket->addChild('subject', $subjectText);
        $newticket->addChild('orderId', $orderid);
        $newticket->addChild('messages', '');
        $newticket->messages->addChild('message', $messageText);
        $newticket->messages->message->addAttribute('userId', $userid);
        $newticket->messages->message->addAttribute('datetime', $current_datetime);

        //convert to DOM and save so that the formatting is nicer
        //ownerDocument is the DOMDocument object related to the retrieved DOMElement
        $dom = dom_import_simplexml($tickets)->ownerDocument;
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->save("xml/tickets.xml");

		header("Location:home.php");
    }
}
?>
<html lang="en">
	<head>
		<title>New Ticket</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	</head>
	<div class="jumbotron bg-primary">
    <h1 class="text-center">Ticket Support Centre</h1>
	<a class="btn btn-success" href="home.php">Home</a> ';    		
    <a class="btn btn-warning text-right logout" href="index.php">Log out</a>    
	<p class="text-left text-white loginuser"> User : <?= $username ?></p>
	</div>
	<body>
		<form action="#" method="post" id="newticketform">
			<div class=" modal-body justify-content-centre">
				<h2>New Ticket</h2>
				<div class="form-group">
				<label>Subject:</label>
					<input name="subject" type="text" class="form-control" placeholder="Subject">
				</div>
				<div class="form-group">
				<label>Order ID:</label>
					<input name="orderid" type="text" class="form-control" placeholder="Order ID">
				</div>
				
				
				<div class="form-group">
				<label>Message:</label>
					<textarea name="message" class="form-control" placeholder="Please detail your issue or question" style="height: 120px;"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<a class="btn btn-default" href="client.php">Cancel</a>
				<button type="submit" id="createbtn" class="btn btn-primary pull-right" name="createticket"></i> Create</button>
			</div>
		</form>
		<?php include_once "footer.php"; ?>
	</body>
</html>