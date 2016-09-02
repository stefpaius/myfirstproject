<?php

session_start();
include 'db-conn.php';

// Verify if user is logged in
function isLoggedIn() {
    return (isset($_SESSION['user']));
}

// Redirect function for non-logged users
function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: login.php");
    }
}

// Redirect for logged users
function redirectIfLogged() {
    if (isLoggedIn()) {
        header("Location: index.php");
    }
}

// Get user function 
function getLoggedUser() {
    return ($_SESSION['user']);
}

/**
 * get logged user full name
 * @return string
 */
function getLoggedUserFullName() {
    $loggedUser = getLoggedUser();
    return $loggedUser['first_name'] . ' ' . $loggedUser['last_name'];
}

function getLoggedUserRole() {
    $loggedUserRole = getLoggedUser();
    return $loggedUserRole['roles'];
}

function encodePassword($password, $salt = ''){
    $salted = $password. $salt;
    $digest = hash('sha512', $salted, true);

    // "stretch" hash
    for ($i = 1; $i < 5000; $i++) {
        $digest = hash('sha512', $digest.$salted, true);
    }
    return base64_encode($digest);
}

function getErrorMessage($field, $errorType){
    $errorMessages = [
        'registration.first_name'    => [
            'required'  => 'This field is required',
            'pattern'   => 'Invalid first name'
        ],
        'registration.last_name'    => [
            'required'  => 'This field is required',
            'pattern'   => 'Invalid last name'
        ],
        'registration.email'    => [
            'required'  => 'This field is required',
            'pattern'   => 'Invalid email format! Must have a name@domain.com format',
            'unique'    => 'Email is already registered'
        ],
        'registration.password'    => [
            'required'  => 'This field is required',
            'pattern'   => 'Invalid password format'
        ],
        'registration.rep_pass'    => [
            'required'  => 'This field is required',
            'pattern'   => 'Passwords do not match'
        ],
        'login.email'   => [ 
            'invalid'  => 'Wrong credentials or non existing account',
            'required' => 'This field is required',
            'pattern'  => 'Wrong credentials or non existing account'
        ],
        'login.password'   => [
            'invalid'  => 'Wrong credentials or non existing account',
            'required' => 'This field is required'
        ]
    ];
    if (isset($errorMessages[$field][$errorType])){
        return $errorMessages[$field][$errorType];
    }  else {
        return '';
    }
}

function getUsersByField($fieldName, $fieldValue, $databaseConnection) {
    $value = mysqli_real_escape_string($databaseConnection, $fieldValue);
    $query = "SELECT * FROM `users` WHERE $fieldName = '$value'";
    $result = mysqli_query($databaseConnection, $query) or exit(mysql_error());
    
    //todo pt a obtine rezultatele din baza de date, nu se foloseste mysqli_num_rows
    $rows = [];
    if ($result) {
        /* fetch associative array */
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        /* free result set */
        mysqli_free_result($result);
    }
    return $rows;
}

// Displaying user Account information in admin panel
function getUserAccount($dbconn) {
//    $hostname = "localhost";
//    $username = "root";
//    $password = "";
//    $dbconn = mysqli_connect($hostname, $username, $password) or die("Unable to connect to MySQL");
    
    $userAccountRow = array();
    $sql = "SELECT id, first_name, last_name, email, roles, is_active FROM users";
    $result = mysqli_query($dbconn,$sql);
    echo mysqli_error($dbconn);
    
    if ($result == FALSE) {
        return NULL;
    }
    if (mysqli_num_rows($result) > 0)  {
        /* fetch associative array */
        while ($row = mysqli_fetch_assoc($result)) {
            $userAccountRow[] = $row;
        }
        /* free result set */
        mysqli_free_result($result);
    }
    
    return $userAccountRow;
}

function updateAdminTableRow($dbconn, $userAccount, $fieldName, $fieldValue) {
    $value = mysqli_real_escape_string($dbconn, ($_POST[$fieldValue]));
    $query = "UPDATE users SET id, first_name, last_name, email, roles, is_active WHERE $userAccount[$fieldName] != $_POST[$fieldName]";
    $result = mysqli_query($dbconn, $query) or exit(mysql_error());
    
    return $result;
}

function deleteAdminTableRow($dbconn, $userAccount, $fieldName, $fieldValue) {
    
    $value = mysqli_real_escape_string($dbconn, ($_POST[$fieldValue]));
    $query = "DELETE FROM users WHERE $userAccount[$fieldName] != $_POST[$fieldName]";
    $result = mysqli_query($dbconn, $query) or exit(mysql_error());
    
    return $result;
}
//
//function undoLastChange() {
//    
//}


?>