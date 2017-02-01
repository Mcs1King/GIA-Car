<?php
  session_start();
  function connect(){
     $host = "";
     $user = "";
     $pass = "";
     $database = "";
     $con = mysqli_connect($host,$user,$pass,$database);
     if(!$con)
        die("Fehler beim verbinden mit der Datenbank");
     return $con;
  }
  
  function sendToDatabase($commmand){
    $con = connect();
    $result = mysqli_query($con,$command);
    return $result;
  }
  
  function createUser($Benutzername,$Passwort,$Vorname,$Nachname,$PLZ,$Adresse,$Job){
    $Passwort = password_hash($Passwort);
	$sql = "insert into tab_user(Benutzername,Passwort,Vorname,Nachname,plz,adresse,job)
			values('".$Benutzername."','".$Passwort."','".$Vorname."','".$Nachname."',
			'".$PLZ."','".$Adresse."','".$Job."')";
	$createdUser = sendToDatabase($sql);
	if(!$createdUser) return false;
	return true;
  }
  
  function login($Benutzername,$Passwort){
	$sql = "select Passwort,vorname,nachname,plz,adresse,job from tab_User where Benutzername like '".$Benutzername."'";
	$_UserProfile = sendToDatabase($sql);
	$UserProfile = mysqli_fetch_array($_UserProfile);
	$Pass = $UserProfile[0];
	if(password_verify($Passwort,$Pass){
		$Person = array (
			"Benutzername" => $Benutzername,
			"Vorname" => $UserProfile[1],
			"Nachname" => $UserProfile[2],
			"PLZ" => $UserProfile[3],
			"Adresse" => $UserProfile[4],
			"Job" => $UserProfile[5]
		);
		$_SESSION['profile'] = $Person;
		return true;
	}
	return false;
  }
?>
