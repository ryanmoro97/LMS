<html>
<head>
    <title>Burn & Learn Online</title>
    <link rel = "stylesheet" type = "text/css" href = "../shared/styles/styles.css">
</head>
<script src ="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src = "user.js"></script>
</body>
<button id="logout" name="logout">Logout</button>
<nav>Courses
    <div id = "courselist"></div>
    <ul>
        <li id = "comp466">COMP 466</li>
        <li id = "comp569">COMP 569</li>
        <li id = "ece420">ECE 420</li>
    </ul>
</nav>
<h1>Burn & Learn Online</h1>
<h3 style="text-align: center "> &nbsp;&nbsp;&nbsp;&nbsp;<em><span style='font-size:1.25em;'>W</span></em>elcome <?php echo htmlspecialchars($_COOKIE["username"])?> !
</h3><hr>
<div id = "lecnavBtns">
    <nav id = "lecs">Lectures<ul id="lecNav"></ul></nav>
    <nav id = "quizs">Quizzes<ul id ="quizNav"></ul></nav>
</div>

<br>
<div id = "courseTitle">To get started select a course from the menu</div> <br>
<hr>
<div id = "contentArea" style="text-align: left"></div>
<button id = "submit">Submit</button>

</html>

