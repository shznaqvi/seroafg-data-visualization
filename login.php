<?php
   include("dbconfig.php");
   session_start();
   $redirect = "_index.html";
   $_SESSION['errors'] = [];

   if($_SERVER["REQUEST_METHOD"] == "POST") {
	   
		// username and password sent from form 
		if($_POST['location'] != '') {
			$redirect = $_POST['location'];
		}
		
		$username = mysqli_real_escape_string($db,$_POST['inputEmail']);
		$password = mysqli_real_escape_string($db,$_POST['inputPassword']); 
	  
		if(strpos( $username, '@aku.edu' ) !== false){
			$ldapconn = ldap_connect("ldap://akudc01.aku.edu") 
				or die("Could not connect to LDAP server.");
//ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
//ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
			if ($ldapconn) {
				$ldaprdnr = str_replace("@aku.edu", "", $username );
				$ldaprdn =  $username;
				
				$ldappass = $password;
				// echo "<script type='text/javascript'>alert('$username');</script>";
				// binding to ldap server
				ldap_set_option( $ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3 );
				ldap_set_option( $ldapconn, LDAP_OPT_REFERRALS, 0 );
				
				$ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
				$dn="dc=aku, dc=edu";
				// verify binding
				if ($ldapbind) {
						//session_register("username");
				  $filter = "(sAMAccountName=" . $ldaprdnr . ")";
    $attr = array("memberof","givenname");
    $result = ldap_search($ldapconn, $dn, $filter, $attr) or exit("Unable to search LDAP server");
	
    $entries = ldap_get_entries($ldapconn, $result);
    
	/* var_dump($entries);
	die(); */
	
	$givenname = $entries[0]['givenname'][0];
    ldap_unbind($ldapconn);
				
				
				$_SESSION['username'] = $givenname; 
				$sql = "INSERT INTO `user_log` (`username`, `remote_ip`) VALUES ('".$_SESSION['username']."', '".$_SERVER['REMOTE_ADDR']."');";
				/* var_dump($sql);
				die(); */
				mysqli_query($db,$sql) or die(mysqli_error($db));
				
				if($_POST['location'] != '') {
					header("Location:". $redirect."?ldap=1");
				} else {
					header("Location:index.php?ldap=2");
				}
				} else {
					$_SESSION['errors'] = array("AKU:Your username or password was incorrect.");
				}
			} 
			//echo "<script type='text/javascript'>alert('$username');</script>";
		} else {
			
			$sql = "SELECT * FROM users WHERE username = '$username' and password = '$password'";
			$result = mysqli_query($db,$sql) or die(mysqli_error($db));
			//$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			//$active = $row['active'];
      
			$count = mysqli_num_rows($result);
      
			// If result matched $myusername and $mypassword, table row must be 1 row
		
			if($count == 1) {
				//session_register("username");
				
				$_SESSION['username'] = $username;
				$sql = "INSERT INTO `user_log` (`username`, `remote_ip`) VALUES ('".$_SESSION['username']."', '".$_SERVER['REMOTE_ADDR']."');";
								mysqli_query($db,$sql) or die(mysqli_error($db));				
				
				if($_POST['location'] != '') {
					header("Location:". $redirect."?db=1");
				} else {
					header("Location:index.php?db=2");
				}
			}else {
				$_SESSION['errors'] = array("LOCAL: Your username or password was incorrect.");
			}
		}
	}	
	
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->

    <link rel="icon" href="../../favicon.ico">

    <title>Sero Afghanistan: Login</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->

    <!-- Custom styles for this template -->
    <link href="../css/signin.css" rel="stylesheet">
  <!-- Bootstrap Material Design -->
  <link href="../css/bootstrap-material-design.css" rel="stylesheet">
  <link href="../css/ripples.min.css" rel="stylesheet">
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
 <div class="container">
<?php if (isset($_SESSION['errors'])): ?>
    <div class="form-errors">
        <?php foreach($_SESSION['errors'] as $error): ?>
            <div class="alert alert-danger">
  <strong>Login Failedd!</strong> <?php echo sizeof($_SESSION['errors'])." ".$error;?>
</div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
      <form class="form-signin"  action = <?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method = "post">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
		<?php
		echo '<input type="hidden" name="location" value="';
if(isset($_GET['location'])) {
    echo htmlspecialchars($_GET['location']);
}
echo '" />';?>
        <div class="checkbox">
      <label>
        <input type="checkbox"> Notifications
      </label>
    </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>

    </div> <!-- /container -->



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

<!-- Material Design for Bootstrap -->
<script src="../js/material.js"></script>
<script src="../js/ripples.min.js"></script>
<script>
  $.material.init();
</script>

<!-- Sliders -->
<script src="//cdnjs.cloudflare.com/ajax/libs/noUiSlider/6.2.0/jquery.nouislider.min.js"></script>

<!-- Dropdown.js -->
<script src="https://cdn.rawgit.com/FezVrasta/dropdown.js/master/jquery.dropdown.js"></script>
<script>
  $("#dropdown-menu select").dropdown();
</script>
  </body>
</html>
