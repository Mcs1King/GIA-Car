<?php
  /******************************************************************************************
                                            OVERVIEW

   ------------------------------------------------------------------------------------------
    Datenbank Verbindungs Funktion
   ------------------------------------------------------------------------------------------
      - connect()
      - sendToDatabase(command)

   ------------------------------------------------------------------------------------------
    USER-MANAGEMENT FUNKTIONEN
   ------------------------------------------------------------------------------------------
      - createUser(Benutzername,Passwort,Vorname,Nachname,PLZ,Adresse,Job)
      - login(Benutzername,Passwort)

   ------------------------------------------------------------------------------------------
    FUNKTIONEN ZUM HINZUFUEGEN IN DIE DATENBANK
   ------------------------------------------------------------------------------------------
      - addExtra(Ausstattung)
      - addMangel(Mangel)

   ------------------------------------------------------------------------------------------
    SETTER
   ------------------------------------------------------------------------------------------
      - setMangel(AutoNr,MangelNr)
      - setStellplatz(AutoNr,StellplatzNr)
      - setAusstattung(AutoNr,AusstattungsNr)

   ------------------------------------------------------------------------------------------
    GET ABFRAGEN
   ------------------------------------------------------------------------------------------
      - getMarken()
      - getExtras()
      - getStellplaetze()
      - getKlassen()
      - getTypen()

   ------------------------------------------------------------------------------------------
    CONVERTER
   ------------------------------------------------------------------------------------------
      - PLZToOrt(PLZ)
      - StellplatzNrToStellplatz(StellplatzNr)
      - ExtraNrToExtra(ExtraNr)
      - MarkenNrToMarke(MarkenNr)
      - MangelNrToMangel(MaengelNr)
      - KlassenNrToKlasse(KlassenNr)

  ******************************************************************************************/


  session_start();


  /******************************************************
             DATENBANK VERBINDUNGS FUNKTIONEN
  ******************************************************/

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



  /******************************************************
                USER-MANAGEMENT FUNKTIONEN
  ******************************************************/
  
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



  /******************************************************
        FUNKTIONEN ZUM HINZUFUEGEN IN DIE DATENBANK
  ******************************************************/

  function addExtra($Ausstattung){
    $sql = "insert into tab_Extra(Ausstattung) values('".$Ausstattung."')";
    return sqlInsertOrUpdate($sql);
  }

  function addMangel($Mangel){
    $sql = "insert into tab_Mangel(Mangel) values('".$Mangel."')";
    return sqlInsertOrUpdate($sql);
  }



  /******************************************************
                          SETTER
  ******************************************************/

  function setMangel($AutoNr,$MangelNr){
    $sql = "insert into tab_Auto_Mangel(AutoNr,MangelNr) values(".$AutoNr.",".$MangelNr.")";
    return sqlInsertOrUpdate($sql);
  }

  function setStellplatz($AutoNr,$StellplatzNr){
    $sql = "update tab_Auto set StellplatzNr = ".$StellplatzNr." where AutoNr = ".$AutoNr;
    return sqlInsertOrUpdate($sql);
  }

  function setAusstattung($AutoNr,$AusstattungsNr){
    $sql = "insert into tab_Ausstattung(AutoNr,AusstattungsNr) values(".$AutoNr.",".$AusstattungsNr.")";
    return sqlInsertOrUpdate($sql);
  }



  /******************************************************
                       GET ABFRAGEN
  ******************************************************/

  function getMarken(){
    $sql = "select MarkenNr,Marke from tab_Marke";
    return convertTableToArray($sql);
  }

  function getExtras(){
    $sql = "select AusstattungsNr,Ausstattung from tab_Extra";
    return convertTableToArray($sql);
  }

  function getStellplaetze(){
    $sql = "select StellplatzNr,Stellplatz from tab_Stellplatz";
    return convertTableToArray($sql);
  }

  function getKlassen(){
    $sql = "select KlassenNr,Klasse from tab_Klasse";
    return convertTableToArray($sql);
  }

  function getTypen(){
    $typenArray = array();

    $sql = "select TypNr,Typ,tab_Marke.Marke,tab_Klasse.Klasse from tab_Typ
            inner join tab_Marke on tab_Marke.MarkenNr = tab_Typ.MarkenNr
            inner join tab_Klasse on tab_Klasse.KlassenNr = tab_Typ.KlassenNr";
    $_typ = sendToDatabase($sql);
    while($typ = mysqli_fetch_array($_typ)){
      $AutoTyp = array("TypNr" => $typ[0],"Typ" => $typ[1], "Marke" => $typ[2],"Klasse" => $typ[3]);
      array_push($typenArray, $AutoTyp);
    }
    return $typenArray;
  }



  /******************************************************
                         CONVERTER
  ******************************************************/

  function PLZToOrt($PLZ){
    $sql = "select ort from tab_ort where PLZ like '".$PLZ."'";
    return convertSelectToValue($sql);
  }

  function StellplatzNrToStellplatz($StellplatzNr){
    $sql = "select Stellplatz from tab_Stellplatz where StellplatzNr = ".$StellplatzNr;
    return convertSelectToValue($sql);
  }

  function ExtraNrToExtra($ExtraNr){
    $sql = "select Ausstattung from tab_Extra where AusstattungsNr = ".$ExtraNr;
    return convertSelectToValue($sql);
  }

  function MarkenNrToMarke($MarkenNr){
    $sql = "select Marke from tab_Marke where MarkenNr = ".$MarkenNr;
    return convertSelectToValue($sql);
  }

  function MangelNrToMangel($MaengelNr){
    $sql = "select Mangel from tab_Mangel where MangelNr = ".$MaengelNr;
    return convertSelectToValue($sql);
  }

  function KlassenNrToKlasse($KlassenNr){
    $sql = "select Klasse from tab_Klasse where KlassenNr = ".$KlassenNr;
    return convertSelectToValue($sql);
  }



  /******************************************************
                      INTERNE FUNKTIONEN
  ******************************************************/
  function convertTableToArray($sqlQuery){
    $convertedArray = array();
    $query = sendToDatabase($sqlQuery);
    while($value = mysqli_fetch_array($query)){
      array_push($convertedArray,array("Name" => $value[1],"Nr" => $value[0]);
    }
    return $convertedArray;
  }

  function convertSelectToValue($sql){
    $_value = sendToDatabase($sql);
    if(!$_value) return "Unbekannt";
    $value = mysqli_fetch_array($_value)[0];
    return $value;
  }

  function sqlInsertOrUpdate($sql){
    if(!sendToDatabase($sql)) return false;
    return true;
  }
  
?>
