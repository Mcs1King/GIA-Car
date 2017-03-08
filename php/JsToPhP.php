<?php

  include('DatenbankConnection.php');

  if(!isset($_POST['call'])) die("false");

  $call = $_POST['call'];

  if(call == 'login'){
	  if(!isset($_POST['username']) or !isset($_POST['password']))
      die("false");
    if(login($_POST['username'],$_POST['password']))
      die(json_encode($_SESSION['profile']));
    else
      die("false");
  }

  if($call == 'register'){
    if(!isset($_POST['username']) or !isset($_POST['passwort']) or
         !isset($_POST['email']) or !isset($_POST['vorname']) or
         !isset($_POST['nachname']) or !isset($_POST['plz']) or
         !isset($_POST['adresse']) or)
      die("false");

    if(createUser($_POST['username'],$_POST['passwort'],$_POST['email'],$_POST['vorname'],$_POST['nachname'],$_POST['plz'],$_POST['adresse'],"Kunde"))
      die(json_encode($_SESSION['profile']));
    else
      die("false");
  }

  if($call == 'getWarenkorb'){
    if(!isset($_SESSION['profile'])) die("false");
    die(json_encode(BenutzernameToWatchlist($_SESSION['profile']["Benutzername"])));
  }

  if($call == 'deleteWarenkorb'){
    if(!isset($_SESSION['profile'])) die("false");
    if(deleteWatchlist($_SESSION['profile']['Benutzername'])) die("true");
    die("false");
  }

  if($call == 'getAllAutos'){
    die(json_encode(getAutos()));
  }

  if($call == 'search'){
    if(!isset($_POST['suchbegriff'])) die("false");
  }
?>
