<html lang="en-us">
    <head>
        <title>Burn & Learn Online</title>
        <link rel = "stylesheet" type = "text/css" href = "../shared/styles/styles.css">
    </head>
    <body>
    <h1>Burn & Learn Online</h1>
    <h3> &nbsp;&nbsp;&nbsp;&nbsp;<em><span style='font-size:1.25em;'>W</span></em>elcome to Burn and Learn Online. This site allows you
        to access lecture materials and take quizzes on them.
        Login to begin learning :)
    </h3><hr><br>


    <?php
    session_start();
    if (isset($_SESSION['errmsg'])){
        echo '<div id = "errmsg9">Invalid username/password</div>';
    }
    else if(isset($_SESSION['errmsg1'])){
        echo '<div id = "errmsg9">Username is taken</div>';
    }
    else if(isset($_SESSION['errmsg2'])){
        echo '<div id = "errmsg9">Username cannot be empty</div>';
    }
    else if(isset($_SESSION['errmsg3'])){
        echo '<div id = "errmsg9">Password cannot be empty</div>';
    }
    ?>


    <form id="login" style="text-align: center" method="post" action="login.php" name = "login">
        <label for="username">Username: </label><input type="text" id="username" name = "username" maxlength="20"
                                                       style="margin-right: 80px; width: 150px; align-content: center; border-radius: 5px; font-size: 12px; border-width: thick; border-color: blueviolet;
                                                       color: rgb(192, 98, 236); font-weight: bold; background-color: rgb(39, 12, 85);"/><br><br>
        <label for="password">Password: </label><input type="password" id="password" name = "password" maxlength="15"
                                                       style="margin-right: 78px;width: 150px; align-content: center; border-radius: 5px; font-size: 12px; border-width: thick; border-color: blueviolet;
                                                       color: rgb(192, 98, 236); font-weight: bold; background-color: rgb(39, 12, 85);"/><br><br>
        <button id="signIn" name="signIn">Sign In</button>
        <button id="signUp" name="signUp">Sign Up</button>
    </form><br>

    <hr><br><br>


    <?php unset($_SESSION['errmsg']); unset($_SESSION['errmsg1']);
            unset($_SESSION['errmsg2']);unset($_SESSION['errmsg3']);?>
     </body>
</html>