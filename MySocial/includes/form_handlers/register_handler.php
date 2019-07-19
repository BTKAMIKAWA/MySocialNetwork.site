<?php

$fname = ""; //First name
$lname = ""; //Last name
$email = ""; //Email
$email2 = ""; //Email 2
$password = ""; //Password
$password2 = ""; //Password 2
$date = ""; //Sign up date
$error_array = array(); //Error messages

if(isset($_POST['reg_button'])){
    //Registration form values

    //First name
    $fname = strip_tags($_POST['reg_fname']); //Remove HTML tags
    $fname = str_replace('', '', $fname); //Remove spaces
    $fname = ucfirst(strtolower($fname)); //Format cases
    $_SESSION['reg_fname'] = $fname; //Stores first name in session variable

    //Last name
    $lname = strip_tags($_POST['reg_lname']); //Remove HTML tags
    $lname = str_replace('', '', $lname); //Remove spaces
    $lname = ucfirst(strtolower($lname)); //Format cases
    $_SESSION['reg_lname'] = $lname; //Stores last name in session variable

    //Email
    $email = strip_tags($_POST['reg_email']); //Remove HTML tags
    $email = str_replace('', '', $email); //Remove spaces
    $email = ucfirst(strtolower($email)); //Format cases
    $_SESSION['reg_email'] = $email; //Stores email in session variable

    //Email 2
    $email2 = strip_tags($_POST['reg_email2']); //Remove HTML tags
    $email2 = str_replace('', '', $email2); //Remove spaces
    $email2 = ucfirst(strtolower($email2)); //Format cases
    $_SESSION['reg_email2'] = $email2; //Stores email 2 in session variable

    //Password
    $password = strip_tags($_POST['reg_password']); //Remove HTML tags

    //Password2
    $password2 = strip_tags($_POST['reg_password2']); //Remove HTML tags

    $date = date("Y-m-d"); //Current date

    if($email == $email2) {
        //Check email format
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);

            //Check if email already exists
            $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$email'");

            //Count the number of rows returned
            $num_rows = mysqli_num_rows($e_check);

            if($num_rows > 0) {
                array_push($error_array, "Email already in use<br>");
            }
           
        }
         else {
            array_push($error_array, "Invalid email format<br>");
            }
    }
    else {
        array_push($error_array, "Emails don't match<br>");
    }

    if(strlen($fname) > 25 || strlen($fname) < 2) {
        array_push($error_array, "First name must contain between 2 and 25 characters<br>");
    }

    if(strlen($lname) > 25 || strlen($lname) < 2) {
        array_push($error_array, "Last name must contain between 2 and 25 characters<br>");
    }

    if($password != $password2) {
        array_push($error_array, "Passwords don't match<br>");
    }

    else {
        if(preg_match('/[^A-Za-z0-9]/', $password)) {
            array_push($error_array, "Password can only contain English letters and numbers<br>");

        }
    }

    if(strlen($password) > 30 || strlen($password) < 5) {

        array_push($error_array, "Password must contain between 5 and 30 characters<br>");
    }

    if(empty($error_array)) {
        $password = md5($password);
        $username = strtolower($fname . "_" . $lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

        $i = 0;
        while(mysqli_num_rows($check_username_query) != 0) {
            $i++;
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
        }

        $rand = rand(1, 2);

        if($rand == 1)
        $profile_pic = "assets/images/profile_pics/default/dragonball.png";
        else if($rand == 2)
        $profile_pic = "assets/images/profile_pics/default/wild.jpg";

        $query = mysqli_query($con, "INSERT INTO users VALUES(NULL, '$fname', '$lname', '$username', '$email', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");
    
        array_push($error_array, "<span style='color: #32b8c4;'>Successfully registered, please login!</span><br>");
        
        $_SESSION['reg_fname'] = "";
        $_SESSION['reg_lname'] = "";
        $_SESSION['reg_email'] = "";
        $_SESSION['reg_email2'] = "";
   
    }
}
?>