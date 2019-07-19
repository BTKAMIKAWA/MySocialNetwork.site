<?php
require 'config/config.php';
include("includes/classes/User.php");
include("includes/classes/Post.php");
include("includes/classes/Message.php");
include("includes/classes/Notification.php");

if(isset($_SESSION['username'])) {
    $userLoggedIn = $_SESSION['username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
    $user= mysqli_fetch_array($user_details_query);
}
else {
    header("Location: register.php");
}
?>

<html lang="en">
<head>
    <title>MyNetwork</title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/bootbox.min.js"></script>
    <script src="assets/js/mysocial.js"></script>
    <script src="https://kit.fontawesome.com/27e003e35e.js"></script>
    <script src="assets/js/jquery.Jcrop.js"></script>
    <script src="assets/js/jcrop_bits.js"></script>
    
 
    
  
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/jquery.Jcrop.css" type="text/css" />
</head>
<body>
    <div class="top-bar">
         <div class="logo">
            <img class="picon" src="assets/images/pageicon.png">
            <a class="hlink" href="index.php">MyNetwork</a>
            <a class="name" href="<?php echo $userLoggedIn; ?>"><?php echo "Welcome" . " " . $user['first_name'] . "!"?></a>
        </div>
        <div style="margin-top: 1px; display: inline-block" class="search">
            <form action="search.php" method="GET" name="search_form">
                <input type="text" onkeyup="getLiveSearchUsers(this.value, '<?php echo $userLoggedIn; ?>')" name="q" placeholder="Search for user...." autocomplete="off" id="search_text_input">
                <div class="button_holder">
                    <img src="assets/images/icons/mag_glass.png">
                </div>
            </form>

            <div class="search_results">

            </div>
            <div class="search_results_footer_empty">

            </div>


        </div>
        <nav>
            <?php
                //unread messages
                $messages = new Message($con, $userLoggedIn);
                $num_messages = $messages->getUnreadNumber();

                //unread notifications
                $notifications = new Notification($con, $userLoggedIn);
                $num_notifications = $notifications->getUnreadNumber();

                //friend requests
                $user_obj = new User($con, $userLoggedIn);
                $num_requests = $user_obj->getNumberOfFriendRequests();

            ?>
           
            <div style="display: inline-block" class="icons">          
                <a href="index.php"><i class="fas fa-home"></i></a>
                <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message')">
                    <i class="fas fa-envelope"></i>
                    <?php
                        if($num_messages > 0)
                            echo '<span class="notification_badge" id="unread_message">' . $num_messages . '</span>';
                    ?>
                </a>
                <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'notification')">
                    <i class="fas fa-bell"></i>
                    <?php
                        if($num_notifications > 0)
                            echo '<span class="notification_badge" id="unread_notification">' . $num_notifications . '</span>';
                    ?>
                </a>
                <a href="requests.php">
                    <i class="fas fa-user-friends"></i>
                    <?php
                        if($num_requests > 0)
                            echo '<span class="notification_badge" id="unread_requests">' . $num_requests . '</span>';
                    ?>
                </a>
                <a href="settings.php"><i class="fas fa-cog"></i></a>
                <a class="logout" href="includes/handlers/logout.php">Logout</a>
            </div>
            
        </nav>
        

        <div class="dropdown_data_window" style="height: 0px; border: none;"></div>
            <input type="hidden" id="dropdown_data_window" value="">
        
    </div>
    
    <div class="wrapper">

    

