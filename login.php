<?php
require_once 'init.php';

function login($dbconn) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $encPass = encodePassword($password, $salt = '');
    
    $requiredFields = ['email', 'password'];
    $loginErrorMessages = [];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $loginErrorMessages[$field] = getErrorMessage('login.' . $field, 'required');
        }
    }
    //validate email (pattern)
    if (!isset($loginErrorMessages['email']) && !preg_match('^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$^', $email)) {
        $loginErrorMessages['email'] = getErrorMessage('login.email', 'pattern');
    }

    //validate email (unique)
    $usersByEmailRow = getUsersByField('email', $email, $dbconn);
    
//    var_dump($usersByEmailRow);
    if (!isset($loginErrorMessages['email']) && (empty($usersByEmailRow) || count($usersByEmailRow) != 1 )) {
        $loginErrorMessages['email'] = getErrorMessage('login.email', 'invalid');
    }
    
    $dbUser = $usersByEmailRow[0];
    echo "<b>Echo user row by email select:</b>";
    echo "<br>";
    var_dump ($dbUser);
    echo "<br>";
    echo "<br>";
    
    //validate password
    $userDBPassword = $dbUser['password'];
    
    
    
    if ($userDBPassword != $encPass) {
        $loginErrorMessages['email'] = getErrorMessage('login.email', 'invalid');
    }
    return [
        'errors'    => $loginErrorMessages,
        'user'      => empty($loginErrorMessages)?$dbUser:null
    ];
}

if (isset($_POST['login-btn'])) {
    $result = login($dbconn);
    echo "Halleluiah!<br>";
    if (empty($result['errors'])) {
        //aici user s-a logat corect
        $loggedUser = $result['user'];
        unset($loggedUser['password']);
        
        // Initializing Session
        $_SESSION['user'] = $loggedUser;
        redirectIfLogged();
    }
};
    
?>


<?php include 'header.php' ?>
<link type="text/css" rel="stylesheet" href="/assets/css/login.css">
        
<div class="container">
    <div class="row">
        <div class="form-box">
            <h1> Welcome back on develop </h1>
            <br>
            <form role="form" id="login-form" class="login-form" method="post" action="login.php" novalidate>

                <!-- Email field -->
                <div class="form-group">
                    <label for="form-email-field" class="sr-only">Email address</label>
                    <div class="input-group">
                        <div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
                        <input type="text" class="form-control required" name="email" placeholder="Email"  
                               value = "<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : ''; ?>" required/> 
                    </div>

                    <?php if (isset($loginErrorMessages['email'])): ?>
                        <label class="error" for="email">
                            <?php echo '*' . $loginErrorMessages['email']; ?>
                        </label>
                    <?php endif; ?>
                </div>


                <!-- Password field -->
                <div class="form-group">
                    <label for="form-password-field" class="sr-only">Password</label>
                    <div class="input-group">
                        <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                        <input type="password" class="form-control" name="password" placeholder="Password" 
                               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" required/> 
                    </div>
                    <?php if (isset($loginErrorMessages['password'])): ?>
                        <label class="error" for="password">
                            <?php echo '*' . $loginErrorMessages['password']; ?>
                        </label>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-default" name="login-btn">Login</button>
            </form> 
            <div class ="login-link">
                <h3>Don't have an account? Click<a href="register.php"> here to register </a></h3>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php' ?>