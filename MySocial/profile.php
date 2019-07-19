<?php
include("includes/header.php");
$message_obj = new Message($con, $userLoggedIn);

if(isset($_GET['profile_username'])) {
    $username = $_GET['profile_username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");

    if(mysqli_num_rows($user_details_query) == 0) {
        exit("User does not exist");
    }

    $user_array = mysqli_fetch_array($user_details_query);

    $num_friends = (substr_count($user_array['friend_array'], ",")) -1;
}

if(isset($_POST['remove_friend'])) {
    $user = new User($con, $userLoggedIn);
    $user->removeFriend($username);
}

if(isset($_POST['add_friend'])) {
    $user = new User($con, $userLoggedIn);
    $user->sendRequest($username);
}

if(isset($_POST['respond_request'])) {
    header("Location: requests.php");
}

if(isset($_POST['post_message'])) {
    if(isset($_POST['message_body'])){
        $body = mysqli_real_escape_string($con, $_POST['message_body']);
        $date = date("Y-m-d H:i:s");
        $message_obj->sendMessage($username, $body, $date);
    }

    $link = '#myTab a[href="#messages_div"]';
    echo "<script>
        $(function() {
            $('" . $link . "').tab('show');
            });
    </script>";
}

?>
    <style type="text/css">
        .wrapper {
            margin-left: 0px;
            padding-left: 0px;
        }
    </style>

    <div class="profile-left">
        <img src="<?php echo $user_array['profile_pic']; ?>">
        <div class="profile-info">
            <p><?php 
                $logged_in_user_obj = new User($con, $userLoggedIn);
                if($userLoggedIn != $username) {
                    echo "Mutual Friends: " . $logged_in_user_obj->getMutualFriends($username);
                }?>
            </p>
            <p><?php echo "Friends: " . $num_friends; ?></p>
            <p><?php echo "Posts: " . $user_array['num_posts']; ?></p>
            <p><?php echo "Likes: " . $user_array['num_likes']; ?></p>
        </div>

        <input type="submit" class="btn btn-friend btn-primary" data-toggle="modal" data-target="#post_form" value="Leave Post">
        
        <form action="<?php echo $username; ?>" method="POST">
            <?php
            $profile_user_obj = new User($con, $username);
            if($profile_user_obj->isClosed()) {
                header("Location: user_closed.php");
            }

            $logged_in_user_obj = new User($con, $userLoggedIn);

            if($userLoggedIn != $username) {
                if($logged_in_user_obj->isFriend($username)) {
                    echo '<input type="submit" name="remove_friend" class="btn btn-friend btn-danger" value="Unfriend"><br>';
                }
                else if($logged_in_user_obj->didReceiveRequest($username)) {
                    echo '<input type="submit" name="respond_request" class="btn btn-friend btn-warning" value="Respond to Request"><br>';
                }
                else if($logged_in_user_obj->didSendRequest($username)) {
                    echo '<input type="submit" name="" class="btn btn-friend btn-basic" value="Request Sent"><br>';
                }
                else {
                    echo '<input type="submit" name="add_friend" class="btn btn-friend btn-success" value="Add Friend"><br>';
                }
            }

            ?>
        </form>

                

        
    </div>



    <div class="profile-main-column column">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">    
                <a class="nav-link" id="newsfeed-div" data-toggle="tab" href="#newsfeed_div" role="tab" aria-controls="newsfeed_div">Newsfeed</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="about-div" data-toggle="tab" href="#about_div" role="tab" aria-controls="about_div">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="messages-div" data-toggle="tab" href="#messages_div" role="tab" aria-controls="messages_div">Messages</a>
            </li>
        </ul>
     
        
          
        
        <div class="tab-content" id="myTabContent"><br>
            <div role="tabpanel" class="tab-pane fade show active" id="newsfeed_div" aria-labelledby="newsfeed-div">
                <div class="posts_area"></div>
                <img id="loading" src="assets/images/icons/loaging.gif">
            </div>

            <div role="tabpanel" class="tab-pane fade show" id="about_div" aria-labelledby="about_div">
              
            </div>

            <div role="tabpanel" class="tab-pane fade show" id="messages_div" aria-labelledby="messages-div">
                <?php
                    echo "<h4>You and <a href='" . $username ."'>" . $profile_user_obj->getFirstAndLastName() . "</a></h4><hr><br>";
                    echo "<div class='loaded_messages' id='scroll_messages'>";
                        echo $message_obj->getMessages($username);
                    echo "</div>";
                ?>

                    <div class="message_post">
                        <form action="" method="POST">
                            <textarea name='message_body' id='message_textarea' placeholder='Write your message ...'></textarea>
                            <input type='submit' name='post_message' class='info' id='message_submit' value='Send'>
                    </form>

                    </div>

                <script>
                        var div = document.getElementById("scroll_messages");
                        div.scrollTop = div.scrollHeight;
                </script>
            </div>
        </div>

    
 

    </div>
  
    <!-- Modal -->
    <div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Leave post on friend's profile and newsfeed!</p>
                    <form class="profile_post" action="" method="POST">
                        <div class="form-group">
                            <textarea class="form-control" name="post_body"></textarea>
                            <input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
                            <input type="hidden" name="user_to" value="<?php echo $username; ?>">
                        </div>
                
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="submit_profile_post" class="btn btn-primary">Post</button>
                </div>
            </div>
        </div>
    </div>

    <script>
          var userLoggedIn = '<?php echo $userLoggedIn; ?>';
          var profileUsername = '<?php echo $username; ?>';

          $(document).ready(function() {

            $('#loading').show();

            //Original ajax request for loading first posts 
            $.ajax({
              url: "includes/handlers/ajax_load_profile_posts.php",
              type: "POST",
              data: "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
              cache:false,

              success: function(data) {
                $('#loading').hide();
                $('.posts_area').html(data);
              }
            });

            $(window).scroll(function() {
              var height = $('.posts_area').height(); //Div containing posts
              var scroll_top = $(this).scrollTop();
              var page = $('.posts_area').find('.nextPage').val();
              var noMorePosts = $('.posts_area').find('.noMorePosts').val();

              if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {
                $('#loading').show();

                var ajaxReq = $.ajax({
                  url: "includes/handlers/ajax_load_profile_posts.php",
                  type: "POST",
                  data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
                  cache:false,

                  success: function(response) {
                    $('.posts_area').find('.nextPage').remove(); //Removes current .nextpage 
                    $('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage 

                    $('#loading').hide();
                    $('.posts_area').append(response);
                  }
                });

              } //End if 

              return false;

            }); //End (window).scroll(function())


          });

    </script>



</div>
</body>
</html>