<?php
session_start();
//getting user id of logged in user
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];

//loading both xml files
$tickets = simplexml_load_file('xml/tickets.xml');
$users = simplexml_load_file('xml/users.xml');

//getting tickets from the id
$ticketID = isset($_GET['id']) ? $_GET['id'] : '';

for ($i = 0; $i < sizeof($tickets); $i++) {
    if ($tickets->ticket[$i]['id'] == $ticketID) {
        $ticketUserId = $tickets->ticket[$i]['userid'];
        $ticketStatus = $tickets->ticket[$i]->status;
        $ticketSubject = $tickets->ticket[$i]->subject;
        $ticketdate = $tickets->ticket[$i]->issueDate;
        $ticketorderId = $tickets->ticket[$i]->orderId;
        $ticketMessage = $tickets->ticket[$i]->messages->message;
        $date = date_create($tickets->ticket[$i]->issueDate);
    }
}

$time = new DateTime();
$current_datetime = $time->format(DateTime::ATOM);

//sending message
if (isset($_POST['sendmsg'])) {
    if ($_POST['message'] != "" || $_POST['message'] != null) {
        $msgText = $_POST['message'];

        for ($i = 0; $i < sizeof($tickets); $i++) {
            if ($tickets->ticket[$i]['id'] == $ticketID) {
                $message = $tickets->ticket[$i]->messages->addChild('message', $msgText);
                $message->addAttribute('userId', $userid);
                $message->addAttribute('datetime', $current_datetime);

                //convert to DOM and save so that the formatting is nicer
                //ownerDocument is the DOMDocument object related to the retrieved DOMElement
                $dom = dom_import_simplexml($tickets)->ownerDocument;
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $dom->save("xml/tickets.xml");
            }
        }
    }
}
?>

<html>
    <head>
        <title>Client View ticket</title>
        <meta charset="utf-8">
        <link href="css/style.css" rel="stylesheet">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  
</head>
<div class="jumbotron bg-primary">
    <h1 class="text-center">Ticket Support Centre</h1>
    <a class="btn btn-success" href="home.php">Home</a>
    		
        <a class="btn btn-warning text-right logout" href="index.php">Log out</a>
    
	<p class="text-left text-white loginuser"> User : <?= $username ?></p>
</div>
    <body>
        <main>
            <div class="jumbotron">
				
				<h5>Ticket Details</h5>
                <h3>Ticket Subject: <?= $ticketSubject ?></h3>
                <p>Ticket ID: <?= $ticketID ?> <span> Order ID: <?= $ticketorderId ?> </span></p> 
                 <p>Ticket Created: <?= date_format($date, "Y/m/d H:i:s") ?> </p>
				 <p></p>
            </div>
            <div class="jumbotron">
				<h5>Messages</h5>
				<?php foreach ($ticketMessage as $ticketMsg) {
        //displaying the messages
        $msgdate = date_create($ticketMsg['datetime']);
        $Messageuserid = $ticketMsg['userId'];
        $Messageuser = $users->xpath('//user[@id=' . $Messageuserid . ']/username');

        if ($ticketMsg['userId'] == $userid) {
            echo '' . date_format($msgdate, "Y/m/d H:i:s") . '<br>' . 'You : ' . $ticketMsg . '<br><br>';
        } else {
            echo '' . date_format($msgdate, "Y/m/d H:i:s") . '<br>' . $Messageuser[0] . ' : ' . $ticketMsg . '<br><br>';
        }
    } ?>
				<form method="post">
					<div class="row comment-box-main p-3 rounded-bottom">
			  		<div class="col-md-9 col-sm-9 col-9 pr-0 comment-box">
					  <input type="text" class="form-control" name="message" placeholder="message ...." />
			  		</div>
			  		<div class="col-md-2 col-sm-2 col-2 pl-0 text-center send-btn">
			  			<button type="submit" class="extlink" name="sendmsg">Send</button>
			  		</div>
                      
				</div>
				</form>
				<a class="btn btn-success text-right logout" href="home.php">Back</a>
			</div>
        </main>
        <?php include_once "footer.php"; ?>
    </body>
</html>