<?php 
include("../../config/config.php");
include("../classes/User.php");
include("../classes/Notification.php");

$limit = 7;

$notifications = new Notification($con, $_REQUEST['userLoggedIn']);
echo $notifications->getNotifications($_REQUEST, $limit);

?>