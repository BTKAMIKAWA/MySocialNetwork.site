<?php 
include("../../config/config.php");
include("../classes/User.php");
include("../classes/Message.php");

$limit = 7;

$messages = new Message($con, $_REQUEST['userLoggedIn']);
echo $messages->getConversationsDropdown($_REQUEST, $limit);

?>