<?php
include 'database.php';

$conn = openConn();
if ($conn->connect_error) {
    die("Connection error");
}

if(isset($_POST['courseNo'])) {
    //get number of lecs and quizzes for specified course
    $courseNo = $_POST['courseNo'];
    $lec_query = mysqli_query($conn, "SELECT * FROM lectures WHERE course = '" . $courseNo . "' ");
    $quiz_query = mysqli_query($conn, "SELECT * FROM quizzes WHERE course = '" . $courseNo . "' ");
    $results['numLecs'] = mysqli_num_rows($lec_query);
    $results['numQuizzes'] = mysqli_num_rows($quiz_query);
    echo json_encode($results);
}
else{
    echo "No course selected";
}
