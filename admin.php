<?php
require_once 'init.php';


redirectIfNotLoggedIn();
$currentUser = getLoggedUser();
$adminRole = $currentUser['roles'];

if ($adminRole !== 'MasterAdmin') {
    redirectIfNotLoggedIn();
}

if($dbconn === FALSE){
    die("Error: Could not connect. " . mysqli_connect_error());
} 

if(isset($_POST['update-changes'])) {
    //    echo '<pre>';
    
    // Check array of data for update //
    // var_dump($_POST);
    
    $userAccount = getUserAccount($dbconn);
    
    $userID = $_POST['id'];
    $userFirstName = $_POST['first_name'];
    $userLastName = $_POST['last_name'];
    $userEmail = $_POST['tableEmail'];
    $userRoles = $_POST['tableRoles'];
    $userActivity = $_POST['tableIsActive'];
    $query = "UPDATE users SET first_name = ' ".$userFirstName." ', last_name = ' ".$userLastName." ', email = ' ".$userEmail." ', roles = ' ".$userRoles." ', is_active = ' ".$userActivity." ' WHERE (id = $userID) ";
    if(mysqli_query($dbconn, $query)) {
        echo "Records have been successfully updated!";
    } else {
        echo "Could not update records. Please try again later.";
    }
}
//
if(isset($_POST['delete-row'])){
    $userID = $_POST['id'];
    $query = "DELETE FROM users  WHERE (id = $userID)";
    if(mysqli_query($dbconn, $query)) {
        echo "Records have been removed permanently!";
    } else {
        echo "Could not delete records. Please try again later.";
    }
}
//
//if(isset('revert-changes')){
//    
//}



?>

<?php include 'header.php' ?>
<link type ="text/css" rel="stylesheet" href="/assets/css/admin.css">

    <h3> Hello  <?php echo getLoggedUserFullName() . ' ' . '<br>' . '<h4>' . '['. $adminRole .']' . '</h4>' ?> </h3> <a href="logout.php"><input type="submit" name="logout" value="Logout"></a>
    <br>
    <br>
    <div class="legend"> 
        <h3> Legend </h3>
        
            <div class="update-wrapper">
                <button name="updateChanges" ><span class="glyphicon glyphicon-floppy-disk"></span></button><span>Update row changes</span>
            </div>
            <br>
            <div class="delete-wrapper">
                <button name="deleteRow" ><span class="glyphicon glyphicon-remove"></span></button><span>Delete entire row</span> <span class="delete-warning"> ( WARNING DELETING ANY DATA CANNOT BE UNDONE OR RECOVERED ) </span>
            </div>
            <br>
            <div class="revert-wrapper">
                <button name="revertChanges" ><span class="glyphicon glyphicon-repeat"></span></button><span>Undo last row changes</span>
            </div>
    </div>
    <table>
        <tr>
            <th> <h4> User ID </h4> </th>
            <th> <h4> First name</h4> </th>
            <th> <h4> Last name </h4> </th>
            <th> <h4> Email address </h4> </th>
            <th> <h4> Role </h4> </th>
            <th> <h4> Active </h4> </th>
            <th> <h4> Options </h4> </th>
        </tr>
   
    <?php
        $userAccount = getUserAccount($dbconn);
        if (is_array($userAccount)) {
            foreach ($userAccount as $userRow) {?>
                <tr> 
                    <form role="form" id="admin-panel-form"  method="post" action="admin.php">
                        <td>
                            <?php echo $userRow['id'];?>
                            <input class="form-control required" name="id" type="hidden" value = "<?php echo $userRow['id'];?>" >
                        </td>
                        <td><input class="form-control required" name="first_name" type="text" value = "<?php echo $userRow['first_name'];?>" ></td>
                        <td><input class="form-control required" name="last_name" type="text" value = "<?php echo $userRow['last_name'];?>" ></td>
                        <td><input class="form-control required email" name="tableEmail" type="email" value ="<?php echo $userRow['email'];?>"></td>
                        <td><input class="form-control required email" name="tableRoles" type="text" value = "<?php echo $userRow['roles'];?>"'></td>
                        <td ><input class="form-control required email" name="tableIsActive" value ="<?php echo $userRow['is_active'];?>"></td>
                      <td>
                            <div class="input-group-addon">
                                <button name="update-changes" ><span class="glyphicon glyphicon-floppy-disk"></span></button>
                                <button name="delete-row" ><span class="glyphicon glyphicon-remove"></span></button>
                                <button name="revert-changes" ><span class="glyphicon glyphicon-repeat"></span></button>
                            </div>
                        </td>
                    </form>
                </tr>
            <?php }
        }
    ?>
    </table>
    
 
    
    
   
                
                
    




