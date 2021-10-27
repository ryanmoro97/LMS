<?php
include 'database.php';
$conn = openConn();
if ($conn->connect_error) {
    die("Connection error");
}

if(isset($_POST['quizNo'], $_POST['courseNo'])){
    $courseNo = $_POST['courseNo'];
    $quizNo = $_POST['quizNo'];

//    get entire quiz from database and trim whitespace
    $query="SELECT content FROM quizzes WHERE course=? AND quizNo=?";
    if ($stmt = mysqli_prepare($conn,$query)){
        mysqli_stmt_bind_param($stmt, "ss",$courseNo,$quizNo);
        mysqli_stmt_execute($stmt);
        $stmt->store_result();
        $stmt->bind_result($result);
        $stmt->fetch();
        $quiz = trim($result);

        // initialize all finder variables
        $searchQ = "<question>";
        $searchQend = "</question>";
        $searchAns = "<answer>";
        $searchAnsEnd = "</answer>";
        $searchText = "<text>";
        $searchTextEnd = "</text>";
        $searchOption = "<option>";
        $searchOptionEnd = "</option>";

        // find all start/end pos of questions
        $offset = 0;
        $startQ = array();
        while (($pos = strpos($quiz, $searchQ, $offset)) !== FALSE) {
            $offset   = $pos + strlen($searchQ);
            $startQ[] = $pos;
        }
        $offset = 0;
        $endQ = array();
        while (($pos = strpos($quiz, $searchQend, $offset)) !== FALSE) {
            $offset   = $pos + strlen($searchQend);
            $endQ[] = $pos;
        }
        $questions = array();
        // split questions apart
        for($i = 0; $i < count($startQ); $i++){
            $questions[$i] = substr($quiz, $startQ[$i]+strlen($searchQ), $endQ[$i]-$startQ[$i]-strlen($searchQend));
        }
        $answers = array();
        $answersText = array();

        // for each q find the answers and append to answers array
        for($i = 0; $i < count($questions); $i++){
            $startAns = strpos($questions[$i], $searchAns);
            $endAns = strpos($questions[$i], $searchAnsEnd);
            $answers[$i] = substr($questions[$i], $startAns+strlen($searchAns), $endAns-$startAns-strlen($searchAnsEnd)+1);
        }

        // setup quiz div, form, header
        $quizHTML = "<div id = 'quizArea'><form>";
        $quizHTML .= "<h2 class = 'quizHeader' style='text-align: center'>Quiz ".$quizNo."</h2><h2 id = 'percentage'></h2>";

        // parse body for each question into html
        for($i = 0; $i < count($questions); $i++) {
            // find question text and append it
            $startText = strpos($questions[$i], $searchText);
            $endText = strpos($questions[$i], $searchTextEnd);
            $text = substr($questions[$i], $startText+strlen($searchText), $endText-$startText-strlen($searchTextEnd)+1);
            $quizHTML .=  "<div class = 'question'>".$text. "</div>";

            // find all answer options start/end for chosen question
            $offset = 0;
            $startOption = array();
            while (($pos = strpos($questions[$i], $searchOption, $offset)) !== FALSE) {
                $offset   = $pos + strlen($searchOption);
                $startOption[] = $pos;
            }
            $offset = 0;
            $endOption = array();
            while (($pos = strpos($questions[$i], $searchOptionEnd, $offset)) !== FALSE) {
                $offset   = $pos + strlen($searchOptionEnd);
                $endOption[] = $pos;
            }

            // create radio inputs for options of current question and append
            for($j = 0; $j < count($startOption); $j++){
                $option = substr($questions[$i], $startOption[$j]+strlen($searchOption), $endOption[$j]-$startOption[$j]-strlen($searchOptionEnd)+1);
                $quizHTML .= "<input type='radio' name='q" . ($i+1) . "' value='" ."$j'><label>". $option . "</label><br>";
                if($answers[$i] == ($j+1)){
                    $answersText[$i] = $option;
                }
            }
            $quizHTML .=  "<div id = q". ($i+1) . "ans" . "></div><br>";
        }
        $quizHTML .= "</form></div>";

//      encode answers and html quiz content for js to display
        $data = array(
            'content' => $quizHTML,
            'quizAns' => $answers,
            'quizAnsText' => $answersText
        );
        echo json_encode($data);
    }
    else{
        echo mysqli_error($conn);
    }
}
else{
    echo "invalid quiz";
}
