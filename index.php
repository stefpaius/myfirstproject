<?php
require_once 'init.php';

//If no one is logged redirect to login.php
redirectIfNotLoggedIn();

// Getting user session data
$author = getLoggedUser();
$authorID = $author['id'];
$admin = $author['roles'];

echo $admin;

if ($admin == 'MasterAdmin') {
    header("Location: admin.php");
}

// Displaying notes function
function getUserNotes($authorID) {
//    $hostname = "localhost";
//    $username = "root";
//    $password = "";
//    $dbconn = mysqli_connect($hostname, $username, $password) or die("Unable to connect to MySQL");
    include 'db-conn.php';
    $notes = array();
    $select = "SELECT * FROM `notes` INNER JOIN `users` ON notes.author_id = users.id WHERE author_id = '$authorID'";
    $result = mysqli_query($dbconn, $select);
    if (mysqli_num_rows($result) > 0) {
        /* fetch associative array */
        while ($row = mysqli_fetch_assoc($result)) {
            $notes[] = $row;
        }
        /* free result set */
        mysqli_free_result($result);
    } else {
        echo "<h3>No notes to display!</h3>";
    };
    return $notes;
}


?>

<?php include 'header.php' ?>
<link type ="text/css" rel="stylesheet" href="/assets/css/index.css">


<div class="container">
    <div class="row">
        <div class="form-box">
            <h1> Important notes </h1>
            <br>
            <h3> Oy,  <?php echo getLoggedUserFullName() ?> </h3> <a href="logout.php"><input type="submit" name="logout" value="Logout"></a>
            <h4>  - write down your notes and then click ADD - </h4>

            <br>
            <br>
            <form role="form" method="post" action="index.php" novalidate>
                <textarea rows="10" name="content" cols="40"> </textarea>
                <button type="submit" class="btn btn-default" name="add-note" value="ADD" action="index.php">Add note</button>
            </form>
            <br>
            <hr>
            <div class="added-notes">
                <fieldset>
                    <h2> Your added-notes </h2>
                    <?php
                    // Condition that checks if there is post-data and there is an non--empty author post-data
                    if (isset($_POST['add-note']) && (!empty($author))) {
                        include 'db-conn.php';
                        $content = '';
                        $content = mysqli_real_escape_string($dbconn, $_POST['content']);
                        // Note input insertion query
                        $insertQuery = mysqli_query($dbconn, "INSERT INTO `notes` (`content`, `author_id`) VALUES ('$content', '$authorID')")
                                or die(mysqli_error($dbconn));

                        echo "<h3><i>Your note has been added!</i></h3>";
                    }
                    ?>
                    <div class="notes-area">
                        <?php
                        $notes = getUserNotes($authorID);
                        if (is_array($notes)) {
                            foreach ($notes as $note) {
                                getUserNotes($authorID);
                                $dbNoteContent = $note['content'];
                                $dbNoteDate = $note['date'];
                                echo "<br>Content: " . $dbNoteContent . "<br>" . "Date: " . $dbNoteDate . "<br>";
                            }
                        };
                        ?>
                    </div>
                </fieldset>
            </div>
        </div> 
    </div> 
</div>
    
    
    
    
<?php include 'footer.php' ?>