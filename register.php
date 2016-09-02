<?php
require_once 'init.php';
    
function getRegisterFormErrors($dbconn){
    $firstName      = $_POST['first_name'];
    $lastName       = $_POST['last_name'];
    $email          = $_POST['email'];
    $password       = $_POST['password'];
    $repPass        = $_POST['rep_pass'];
    
    //validate all required fields
    $requiredFields = ['first_name', 'last_name', 'password', 'email', 'rep_pass'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])){
            $regErrorMess[$field]   =   getErrorMessage('registration.'.$field, 'required');
        }
    }
    //validate first name
    if (!isset($regErrorMess['first_name']) && !preg_match('^[A-Z][-a-zA-Z]+$^', $firstName)) {
        $regErrorMess['first_name'] =   getErrorMessage('registration.first_name', 'pattern');
    }
    //validate last name
    if (!isset($regErrorMess['last_name']) && !preg_match('^[A-Z][-a-zA-Z]+$^', $lastName)) {
       $regErrorMess['last_name']   =   getErrorMessage('registration.last_name', 'pattern');
    }
    
    //validate email (unique)
    $usersEmail = getUsersByField('email', $email, $dbconn);
    if(!isset($regErrorMess['email']) && !empty($usersEmail) ) {
        $regErrorMess['email']      =   getErrorMessage('registration.email', 'unique');
    }
    //validate email (pattern)
    if (!isset($regErrorMess['email']) && !preg_match( '^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$^', $email)) {
        $regErrorMess['email']      =   getErrorMessage('registration.email', 'pattern');
    }
    //validate password (length + pattern)
    if (!isset($regErrorMess['password']) && !preg_match('^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}^', $password)) {
        $regErrorMess['password']   =   getErrorMessage('registration.password', 'pattern');
    }
    //validate repeat password
    if  (!isset($regErrorMess['rep_pass']) && ($repPass !== $password)) {
        $regErrorMess['rep_pass']    =   getErrorMessage('registration.rep_pass', 'pattern');
    }
    if (isset($regErrorMess)) {
        return $regErrorMess;
    } else {    
        header("Location: login.php");
    }
}
    
if (isset($_POST['register-btn'])) {
    $regErrorMess = getRegisterFormErrors($dbconn);
    if (empty($regErrorMess)){
        //todo register process
        $firstName   =   mysqli_real_escape_string($dbconn, $_POST['first_name']);
        $lastName    =   mysqli_real_escape_string($dbconn, $_POST['last_name']);
        $email       =   mysqli_real_escape_string($dbconn, $_POST['email']);
        $encodePass = encodePassword($_POST['password'], $salt = '');
        
        // Input data insertion query
        if (false === mysqli_query($dbconn, "INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`) VALUES ('NULL', '$firstName', '$lastName', '$email', '$encodePass')")){
            $regErrorMess['general'] = 'Cannot connect to database. Please try again later.';
        }
    }
}
?>


<?php include 'header.php' ?>
<head> 
    <link type ="text/css" rel ="stylesheet" href ="/assets/css/register.css">
    <script type="text/javascript" src="/assets/js/app.js"> </script>
</head>

<div class="container">
	<div class="row">
		<div class="form-box">
                    <h1>Registration <span>form</span></h1>
                <?php if (isset($regErrorMess['general'])): ?>
                    <?php echo $regErrorMess['general']; ?>
                <?php endif; ?>
    	    <form role="form" id="register-form"  method="post" action="register.php" novalidate>
                
            <!-- First name field -->
            <div class="form-group">
            <label for="form-first-name-field" class="sr-only">First name</label>
                <div class="input-group">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                    <input type="text" class="form-control required" name="first_name" placeholder="First name"  
                           value = "<?php echo isset($_POST['first_name'])?$_POST['first_name']:'';?>" required/> 
                </div>
            
                <?php if (isset($regErrorMess['first_name'])):?>
                    <label class="error" for="first_name">
                        <?php echo $regErrorMess['first_name'];?>
                    </label>
                <?php endif; ?>
            </div>
            
            <!-- Last name field -->
            <div class="form-group">
            <label for="form-last-name-field" class="sr-only">Last name</label>
                <div class="input-group">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                    <input type="text" class="form-control required" name="last_name" placeholder="Last name"  
                           value = "<?php echo isset($_POST['last_name'])?$_POST['last_name']:'';?>" required/> 
                </div>
            
                <?php if (isset($regErrorMess['last_name'])):?>
                    <label class="error" for="last_name">
                        <?php echo $regErrorMess['last_name'];?>
                    </label>
                <?php endif; ?>
            </div>
                
            <!-- Email field -->
            <div class="form-group">
            <label for="form-email-field" class="sr-only">Email address</label>
                <div class="input-group">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
                    <input type="email" class="form-control required email" name="email" placeholder="Email" 
                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"  
                           value = "<?php echo isset($_POST['email'])?$_POST['email']:'';?>" required/> 
                </div>
                <?php if (isset($regErrorMess['email'])):?>
                    <label class="error" for="email">
                        <?php echo $regErrorMess['email'];?>
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
                <?php if (isset($regErrorMess['password'])):?>
                    <label class="error" for="password">
                        <?php echo $regErrorMess['password'];?>
                    </label>
                <?php endif; ?>
            </div>
            
             <!-- Repeat password field -->
            <div class="form-group">
                <label for="form-repeat-password-field" class="sr-only">Repeat password</label>
                <div class="input-group">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                    <input type="password" class="form-control" name="rep_pass" placeholder="Repeat password"
                           pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" required/> 
                </div>
                <?php if (isset($regErrorMess['rep_pass'])):?>
                    <label class="error" for="rep_pass">
                        <?php echo $regErrorMess['rep_pass'];?>
                    </label>
                <?php endif; ?>
            </div>
             
            <button type="submit" class="btn btn-default" name="register-btn">Register</button>
    	    </form> 
            <div class ="register-link">
                <h3>Already registered? Click <a href="login.php"> here to login </a></h3>
            </div>
        </div>
    </div>
<?php include 'footer.php' ?>
