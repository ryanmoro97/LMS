var contentArea;
var courseTitle;
var quizAns;
var quizAnsText;
var submit;

function registerListeners(){
    document.getElementById("logout").addEventListener("click", function() {
        logout();
    }, false);
    var menuItem;
    menuItem = document.getElementById("comp466");
    menuItem.addEventListener("click",
        function(){localStorage["courseNo"] = "comp466"; loadData();}, false);
    menuItem = document.getElementById("comp569");
    menuItem.addEventListener("click",
        function(){notAvailable();}, false);
    menuItem = document.getElementById("ece420");
    menuItem.addEventListener("click",
        function(){notAvailable();}, false);

}

function notAvailable(){
    courseTitle.innerHTML = "This course is not available yet :)";
    clearContent();
    menuContext();
}

function logout(){
    window.location.replace("main.php");
}

function loadData(){
    $.ajax({
        url: 'courseMenuSetup.php',
        type: 'POST',
        dataType: "json",
        data: {courseNo: localStorage["courseNo"]},
        success: function (data) {
            var numLecs = data.numLecs;
            var numQuizzes = data.numQuizzes;
            courseSetup(numLecs, numQuizzes);
            // console.log("numLecs: " + numLecs + "\nnumQuizzes: " + numQuizzes);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
        }
    });
}


function courseSetup(numLecs, numQuizzes){
    if(localStorage["courseNo"] === "comp466"){
        courseTitle.innerHTML = "<br><h2>Comp 466: Advanced Technologies for Web-Based Systems</h2>"
    }
    menuContext();
    courseContext();
    for(var i = 0; i < numLecs; i++){
        var lecNavList = document.getElementById("lecNav");
        var lecItem = document.createElement("li");
        var lecNum = (i+1).toString();
        lecItem.innerHTML = lecNum;
        lecItem.id = lecNum;
        lecNavList.appendChild(lecItem);
        addLecListener(lecNum);
    }
    // add offset to not interfere ids, compensate in innerHTML assignment
    for(var j = 50; j < numQuizzes+50; j++){
        var quizNavList = document.getElementById("quizNav");
        var quizItem = document.createElement("li");
        var quizNum = (j+1);
        quizItem.innerHTML = (quizNum-50).toString();
        quizItem.id = quizNum.toString();
        quizNavList.appendChild(quizItem);
        addQuizListener(quizNum);
    }
}

function addLecListener(num){
    document.getElementById(num).addEventListener("click", function() {
        getLec(num);
    }, false);
}

function addQuizListener(num){
    document.getElementById(num).addEventListener("click", function() {
        getQuiz(num-50);
    }, false);
}

function getLec(lecNo){
    courseContext();
    $.ajax({
        url: 'getLec.php',
        type: 'post',
        data: {
            courseNo: localStorage["courseNo"],
            lecNo: lecNo
        }
    }).done(function(result) {
        clearContent();
        $('#contentArea').html(result);
    });
}

function getQuiz(quizNo){
    $.ajax({
        url: 'getQuiz.php',
        type: 'post',
        dataType: 'json',
        data: {
            courseNo: localStorage["courseNo"],
            quizNo: quizNo
        }
    }).done(function(result) {
        clearContent();
        // console.log(result);
        quizAnsText = result.quizAnsText;
        quizAns = result.quizAns;
        $('#contentArea').html(result.content);
    });
    setupQuizSubmitListener()
}

function setupQuizSubmitListener(){
    submit.style.display = "block";
    submit.addEventListener("click",
        function(){showResults();}, false);
}

function showResults(){
    submit.style.display = "none";
    getAnswers();
}

// calculate % for submitted quiz
function getAnswers() {
    var q1ans = -1;var q2ans = -1;var q3ans = -1;var q4ans = -1;var q5ans = -1;
    if (document.querySelector('input[name=q1]:checked') != null) {
        q1ans = document.querySelector('input[name=q1]:checked').value;
    }
    if (document.querySelector('input[name=q2]:checked') != null) {
        q2ans = document.querySelector('input[name=q2]:checked').value;
    }
    if (document.querySelector('input[name=q3]:checked') != null) {
        q3ans = document.querySelector('input[name=q3]:checked').value;
    }
    if (document.querySelector('input[name=q4]:checked') != null) {
        q4ans = document.querySelector('input[name=q4]:checked').value;
    }
    if (document.querySelector('input[name=q5]:checked') != null) {
        q5ans = document.querySelector('input[name=q5]:checked').value;
    }
    var score = 0;
    console.log("q1ans: " + q1ans);
    console.log(", quizAns[0]: " + quizAns[0]);
    console.log("\nq2ans: " + q2ans);
    console.log(", quizAns[1]: " + quizAns[1]);
    console.log("\nq3ans: " + q3ans);
    console.log(", quizAns[2]: " + quizAns[2]);
    console.log("\nq4ans: " + q4ans);
    console.log(", quizAns[3]: " + quizAns[3]);
    console.log("\nq5ans: " + q4ans);
    console.log(", quizAns[4]: " + quizAns[4]);

    if(q1ans == quizAns[0]-1){
        document.getElementById("q1ans").innerHTML = "Correct";
        score++;
    }else{
        document.getElementById("q1ans").innerHTML = "Incorrect - The right answer is: " + quizAnsText[0];
    }
    if(q2ans == quizAns[1]-1){
        document.getElementById("q2ans").innerHTML = "Correct";
        score++;
    }else{
        document.getElementById("q2ans").innerHTML = "Incorrect - The right answer is: " + quizAnsText[1];
    }
    if(q3ans == quizAns[2]-1){
        document.getElementById("q3ans").innerHTML = "Correct";
        score++;
    }else{
        document.getElementById("q3ans").innerHTML = "Incorrect - The right answer is: " + quizAnsText[2];
    }
    if(q4ans == quizAns[3]-1){
        document.getElementById("q4ans").innerHTML = "Correct";
        score++;
    }else{
        document.getElementById("q4ans").innerHTML = "Incorrect - The right answer is: " + quizAnsText[3];
    }
    if(q5ans == quizAns[4]-1){
        document.getElementById("q5ans").innerHTML = "Correct";
        score++;
    }else{
        document.getElementById("q5ans").innerHTML = "Incorrect - The right answer is: " + quizAnsText[4];
    }
    var percent = score/5 * 100;
    document.getElementById("percentage").innerHTML = "Your score is " + percent + "%!"
}

function clearContent(){
    contentArea.innerHTML = "";
}

function courseContext(){
    document.getElementById("lecnavBtns").style.display = "block";
    submit.style.display = "none";
}

function menuContext(){
    document.getElementById("lecnavBtns").style.display = "none";
    document.getElementById("lecNav").innerHTML = "";
    document.getElementById("quizNav").innerHTML = "";
    submit.style.display = "none";
}


function setup(){
    submit = document.getElementById("submit");
    courseTitle = document.getElementById( "courseTitle" );
    contentArea = document.getElementById( "contentArea" );
    registerListeners();
    menuContext();

}

window.addEventListener( "load",setup , false );