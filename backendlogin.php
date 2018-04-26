<?php
require_once("config/DB.class.php");
	if(isset($_POST['user']) && isset($_POST['password']))
	{
		if(!empty($_POST['user']) && !empty($_POST['password']))
		{
			$user = $_POST['user'];
			$password = $_POST['password'];
			
			$jsonUser = urldecode($user);
			$jsonPass = urldecode($password);
			
			$userData = json_decode($jsonUser);
			$passData = json_decode($jsonPass);
			
			
			$dbUser = $userData->user;
			$dbPass = $passData->pass;
			
			
			
			$db = new DB();
			if(!$db->getConnStatus()){
				print json_encode(array("result"=>"An error has occurred with connection\n"));
				exit;
			}
			
			$sanitizedUser = filter_var($dbUser,FILTER_SANITIZE_STRING);
			$sanitizedPass = filter_var($dbPass,FILTER_SANITIZE_STRING);
			$safeUser = $db->dbEsc($sanitizedUser);
			
			$query = "SELECT username,userpass,realname,rolename 
					  FROM user, user2role, role
					  WHERE user.id = user2role.userid AND user2role.roleid = role.id AND user.username = '{$safeUser}'";
			
			$result = $db->dbCall($query);
			if(!count($result) == 0)
			{
				if((password_verify($sanitizedPass,$result[0]['userpass'])) == 1)
				{
					
					print json_encode(array("isLoggedIn"=>true,
									  "realName"=>$result[0]['realname'],
									  "role"=>$result[0]['rolename']));
				}
				else
				{
					print json_encode (array("result"=>"The username or password was incorrect, please try again."));
				}
			}
			else
			{
				print json_encode (array("result"=>"The username or password was incorrect, please try again."));		
			}
		}
		else
		{
			print json_encode (array("result"=>"You must enter both a username and password"));
		}
	}
	else
	{
		print json_encode (array("result"=>"You must enter both a username and password"));
	}

?>