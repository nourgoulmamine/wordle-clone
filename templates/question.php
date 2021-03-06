<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Nour Goulmamine">
    <meta name="description" content="include some description about your page">
    <title>Wordle Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
</head>

<body>
    <div class="container" style="margin-top: 15px;">
        <div class="row col-xs-8">
            <h1>Wordle Clone: Play</h1>

            <h3>Hello <?= $user["name"] ?>!</h3>

        </div>
        <div class="row">
        <?php
                if (!empty($error_msg)) {
                    echo "<div class='alert alert-danger'>$error_msg</div>";
                }
                ?>
            <div class="col-xs-8 mx-auto">
                <form action="?command=question" method="post">
                    <div class="h-40 p-5 bg-light border rounded-3">
                        <h2>Guess the word. Guess #<?= $user["guesses"] ?></h2>
                        <p><?= $user["wordList"] ?></p>
                    </div>
                    <?= $message ?>
                    <div class="h-10 p-5 mb-3">
                        <input type="text" class="form-control" id="answer" name="answer" placeholder="Type your answer here">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="?command=gameOver" class="btn btn-danger">End Game</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</body>

</html>
