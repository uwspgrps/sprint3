<?php
require_once("templates/Template.php");
require_once("config/DB.class.php");
$page = new Template("Log In");
$page->setHeadSection("<link rel='stylesheet' href='css/prettylab.css'>");
$page->setTopSection();
$page->setBottomSection();
print $page->getTopSection();
print "
<header>
  <h1>Log In</h1>";
if(isset($_SESSION['isLoggedIn'])){
	if($_SESSION['isLoggedIn'] == true){
		print "<h2 align='right'>Welcome " . $_SESSION['realName'] . "</h2>";
	}
}

print "	
	<nav>
		<a href=\"booksearch.php\">Search</a>
		<a href=\"contactus.php\">Contact</a>
		<a href=\"asgnabout.php\">About</a>
		<a href=\"index.php\">Home</a>	
		<a href=\"login.php\">Log In</a>
	</nav>
</header>";


if(isset($_POST['submit']))
{
	if(isset($_POST['name']) && isset($_POST['passwrd']))
	{
		if(!empty($_POST['name']) && !empty($_POST['passwrd']))
		{
			$user = $_POST['name'];
			$password = $_POST['passwrd'];
			$userData = array("user" => $user);
			$passData = array("pass" => $password);
			$userJson = json_encode($userData);
			$passJson = json_encode($passData);

			$postString = "user=" . urlencode($userJson) . "&password=" . urlencode($passJson);
			$contentLength = strlen($postString);

			$header = array(
			  'Content-Type: application/x-www-form-urlencoded',
			  'Content-Length: ' . $contentLength
			);

			$url = "http://cnmtsrv2.uwsp.edu/~lmanc333/sprint3/backendlogin.php";

			$ch = curl_init();

			curl_setopt($ch,
				CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch,
				CURLOPT_POSTFIELDS, $postString);
			curl_setopt($ch,
				CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_URL,$url);
			

			$return = curl_exec($ch);
			//var_dump($return);
			$authObject = json_decode($return);
			//var_dump($authObject);
			if(!isset($authObject->result)){
				$_SESSION['isLoggedIn'] = $authObject->isLoggedIn;
				$_SESSION['realName'] = $authObject->realName;
				$_SESSION['role'] = $authObject->role;
				curl_close($ch);
				header("Location: index.php");
			}else{
				curl_close($ch);
				print $authObject->result;
			}
			//var_dump($_SESSION);
			
			

		}
		else
		{
			print "You must enter both a username and password";
		}
	}
	else
	{
		print "You must enter both a username and password";
	}
}


print 
"<main>
  <section>
    <article>
      <h2>Log into your account</h2>
	<form method='POST' action='login.php'>
	<div class='input'>
	Username:  <input type='text' name='name' id='name' >
	</div>
	<div class='input'>
	Password:  <input type='password' name='passwrd' id='passwrd' >
	</div>	
	<div class='input'>
	<input type='submit' name='submit' value='Log in'>
	</div>
	</form>
      <p></p>
      <p></p>
    </article>
  </section>
  <aside>
    <div class=capsule>
      <h3>Egestas</h3>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
	  Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
	  in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
    </div>
    <div class=capsule>
      <h3>Pellentesque</h3>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
	  Morbi enim nunc faucibus a. Mauris a diam maecenas sed enim ut sem viverra. In metus vulputate eu scelerisque felis. Platea dictumst 
	  quisque sagittis purus sit amet volutpat consequat mauris. Lacus suspendisse faucibus interdum posuere. </p>
    </div>
    <div class=capsule>
      <h3>Tincidunt</h3>
      <p>Aliquet risus feugiat in ante metus. Urna id volutpat lacus laoreet non curabitur. Auctor elit sed vulputate mi sit amet mauris 
	  commodo quis. Blandit turpis cursus in hac habitasse platea dictumst quisque. Malesuada nunc vel risus commodo viverra.
	  Viverra mauris in aliquam sem fringilla ut morbi.  Pellentesque habitant morbi tristique senectus et netus et malesuada. 
	  Eget felis eget nunc lobortis mattis aliquam. Massa tincidunt dui ut ornare lectus. Libero nunc consequat 
	  interdum varius sit amet.</p>
    </div>
  </aside>
  </main>
  <footer>Sprint 3 Ken Lucas Peter</footer>";
 print $page->getBottomSection();
?>