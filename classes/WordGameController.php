<?php
class WordGameController {

    private $command;

    public function __construct($command) {
        $this->command = $command;
    }

    public function run() {
        switch($this->command) {
            case "question":
                $this->question();
                break;
            case "gameover":
                $this->gameover();
                break;
            case "logout":
                $this->destroyCookies();
            case "login":
            default:
                $this->login();
                break;
        }
    }

    // Clear all the cookies that we've set
    private function destroyCookies() {          
        session_destroy();
    }
    

    // Display the login page (and handle login logic)
    public function login() {
        if (isset($_POST["email"]) && !empty($_POST["email"])) { /// validate the email coming in
    
            $_SESSION["name"] = $_POST["name"];
            $_SESSION["email"] = $_POST["email"];
            $_SESSION["wordList"] = array();
            $_SESSION["guesses"] =0;
            $_SESSION["answer"]  = $this->loadQuestion();
            if ($_SESSION["answer"]== null) {
                die("No questions available");
            }
            header("Location: ?command=question");
            return;
        }

        include "templates/login.php";
    }

    private function gameover() {
        include "templates/gameOver.php";

        //if user wants to start a new game, reset these items
        $_SESSION["wordList"] = array();
        $_SESSION["guesses"] =0;
        $_SESSION["answer"]  = $this->loadQuestion();
         if ($_SESSION["answer"] == null) {
            die("No questions available");
        }
    }


    // Load word ?
    private function loadQuestion() {

        $response = file_get_contents("https://www.cs.virginia.edu/~jh2jf/courses/cs4640/spring2022/wordlist.txt", false);
        $pieces = explode("\n", $response);
        
        $randomIndex = rand(0, count($pieces));
        return $pieces[$randomIndex]; // return random word
    }


    // Display the question template (and handle question logic)
    public function question() {
        // set user information for the page from the cookie
        $user = [
            "name" => $_SESSION["name"],
            "email" => $_SESSION["email"],
            "guesses" => $_SESSION["guesses"],
            "wordList" => json_encode($_SESSION["wordList"])
        ];

        // load the question
        $question=$_SESSION["answer"];
        $message="";

        // if the user submitted an answer, check it
        if (isset($_POST["answer"])) {
            $answer = $_POST["answer"]; //retrieve user's answer
            
            $user["guesses"] += 1; //update number of guesses
            $_SESSION["guesses"] = $user["guesses"];
            
            array_push($_SESSION["wordList"], $answer); //update list of guessed words
            $user["wordList"] = json_encode($_SESSION["wordList"]);
            

           // echo "<script>console.log('cookie Debug Objects: " .json_encode($_COOKIE["wordList"]). "' );</script>";
            
           //see if user's guess is the answer
            if ($_SESSION["answer"] == strtolower($answer)) {
                // user answered correctly -- perhaps we should also be better about how we
                // verify their answers, perhaps use strtolower() to compare lower case only.
                // $message = "<div class='alert alert-success'><b>$answer</b> was correct!</div>";
                header("Location: ?command=gameOver");    
            } else { 
                $letters = 0; // amount of letters you got right
                // How many letters you got right
                $common_letters = similar_text($_SESSION["answer"], $answer);

                // How many letters were in the correct location
                $real_answer_length = strlen($_SESSION["answer"]);

                // If the guessed word was too long or short
                $length = "";
                $user_answer = strlen($answer);
                if ($user_answer > $real_answer_length) {
                    $length = "too long";
                }
                elseif ($user_answer < $real_answer_length) {
                    $length = "too short";
                } else {
                    $length = "the same length";
                }

                $message = "<div class='alert alert-danger'>There were <b>$common_letters</b> characters in the word. There were ___ in the correct place. Your answer was <b>$length</b>.
                </div>";            
            }  
        }

        // update the question information in cookies

        $_SESSION["answer"] = $question;

        include("templates/question.php");
    }
}
