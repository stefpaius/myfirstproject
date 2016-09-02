<?php
include_once 'db-conn.php';
$content = '';
var_dump($_SESSION);
//If no one is logged redirect to login.php
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}
    
if (isset($_POST['add-note'])) {

    $currentUser = $_SESSION['user'];
    $authorSQL = "SELECT id FROM `users` WHERE email = '$currentUser";
    $authorID = mysqli_query($dbconn, $authorSQL);
    var_dump($authorID);


    $content = mysqli_real_escape_string($dbconn, $_POST['content']);

    //Note inpun insertion query
    mysqli_query($dbconn, "INSERT INTO `notes` (`id`, `content`, `date`, `author_id`) VALUES ('', '$content', CURRENT_TIMESTAMP, '$authorID')")
            or die(mysqli_error($dbconn));
    echo "Your note has been added!";
}

function retrieveNotes() {
    include 'db-conn.php';
    $select = "SELECT id, content, date, author_id FROM `notes`";
    $result = mysqli_query($dbconn, $select);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<br>Content: " . $row["content"] . "<br>" . " Date: " . $row["date"] . "<br>";
        }
    } else {
        echo "No results";
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title> My first project </title>
    </head>

    <body>
        <h1> Important notes </h1>

        <!-- DISPLAY LOGGED USER -->
        <h3> Oy,  <?php echo ($_SESSION['user']) ?> </h3>
        <a href="login.php"><input type="submit" name="logout" value="Logout" action="logout.php"></a>
    <legend><p>  - write down your notes and then click ADD - </p></legend>
    <form method="post" action="index.php">
        <textarea rows="10" name="content" cols="40"> </textarea>
        <br>
        <input type="submit" name="add-note" value="ADD" action="index.php"/>
    </form>

    <br>
    <hr>

    <div class="added-notes">
        <fieldset>
            <legend><h2> Your added-notes </h2></legend>
            <h4> <?php echo retrieveNotes(); ?> </h4>
        </fieldset>
    </div>
</body>


</html>
