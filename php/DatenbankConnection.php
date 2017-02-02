<?php
  /**************************************************************************************************************************************
                                            OVERVIEW
   --------------------------------------------------------------------------------------------------------------------------------------
    Datenbank Verbindungs Funktion
   --------------------------------------------------------------------------------------------------------------------------------------
      - connect()
      - sendToDatabase(command)
      - getIdOfLastInserted()
   --------------------------------------------------------------------------------------------------------------------------------------
    USER-MANAGEMENT FUNKTIONEN
   --------------------------------------------------------------------------------------------------------------------------------------
      - createUser(Benutzername,Passwort,Vorname,Nachname,PLZ,Adresse,Job)
      - login(Benutzername,Passwort)
      - isLoggedIn()
   --------------------------------------------------------------------------------------------------------------------------------------
    FUNKTIONEN ZUM HINZUFUEGEN IN DIE DATENBANK
   --------------------------------------------------------------------------------------------------------------------------------------
      - addExtra(Ausstattung)
      - addMangel(Mangel)
      - addMotor(Motor)
      - addMarke(Marke)
      - addKlasse(Klasse)
      - addTyp(Typ,KlassenNr,MarkenNr)
      - addOrt(PLZ,Ort)
      - addAuto(TypNr,Farbe,Kurzbeschreibung,Detailbeschreibung,StellplatzNr,Baujahr,Leistung,KMLaufleistung,MangelArray,ExtrasArray,MotorenArray)
      - addBild(AutoNr, Bildpfad)
   --------------------------------------------------------------------------------------------------------------------------------------
    SETTER
   --------------------------------------------------------------------------------------------------------------------------------------
      - setMangel(AutoNr,MangelNr)
      - setStellplatz(AutoNr,StellplatzNr)
      - setAusstattung(AutoNr,AusstattungsNr)
      - setMotoren(AutoNr,MotorenNr)
      - setFarbe(AutoNr,Farbe)
      - setKurbeschreibung(AutoNr,Kurzbeschreibung)
      - setDetailbeschreibung(AutoNr,Detailbeschreibung)
      - setKMLaufleistung(AutoNr,KMLaufleistung)
      - setLeistung(AutoNr,Leistung)
      - setBaujahr(AutoNr,Baujahr)
   --------------------------------------------------------------------------------------------------------------------------------------
    GET ABFRAGEN
   --------------------------------------------------------------------------------------------------------------------------------------
      - getMarken()
      - getExtras()
      - getStellplaetze()
      - getKlassen()
      - getTypen()
      - getMotoren()
   --------------------------------------------------------------------------------------------------------------------------------------
    DELETE
   --------------------------------------------------------------------------------------------------------------------------------------
      - deleteBild(BildNr)
      - deleteStellplatz(StellplatzNr)
      - deleteMangel(MangelNr)
      - deleteAusstattung(AusstattungsNr)
      - deleteMotorArt(MotorNr)
      - deleteMarke(MarkenNr)
      - deleteKlasse(KlassenNr)
   --------------------------------------------------------------------------------------------------------------------------------------
    CONVERTER
   --------------------------------------------------------------------------------------------------------------------------------------
      - PLZToOrt(PLZ)
      - StellplatzNrToStellplatz(StellplatzNr)
      - ExtraNrToExtra(ExtraNr)
      - MarkenNrToMarke(MarkenNr)
      - MangelNrToMangel(MaengelNr)
      - KlassenNrToKlasse(KlassenNr)
      - AutoNrToBilder(AutoNr)
      - AutoNrToAuto(AutoNr)
      - BenutzernameToWatchlist(Benutzername)
      - AutoNrToExtras(AutoNr)
      - AutoNrToMangel(AutoNr)
      - AutoNrToMotor(AutoNr)
  **************************************************************************************************************************************/

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
    if(!$con){
      errorHappend(mysqli_error($con));
      die("Fehler beim verbinden mit der Datenbank");
    }
    return $con;
  }
  
  function sendToDatabase($commmand){
    $con = connect();
    $result = mysqli_query($con,$command);
    if(!$result)
      errorHappend(mysqli_error($con));
    return $result;
  }

  function getIdOfLastInserted(){
    $con = connect();
    return mysqli_insert_id($con);
  }

  /******************************************************
                USER-MANAGEMENT FUNKTIONEN
  ******************************************************/
  
  function createUser($Benutzername,$Passwort,$Email,$Vorname,$Nachname,$PLZ,$Adresse,$Job){
     $sql = "insert into tab_user(Benutzername,Passwort,email,Vorname,Nachname,plz,adresse,job)
	     values('".$Benutzername."','".password_hash($Passwort)."','".$Email."','".$Vorname."','".$Nachname."',
	     '".$PLZ."','".$Adresse."','".$Job."')";
    return sqlInsertOrUpdate($sql);
  }
  
  function login($Benutzername,$Passwort){
    $sql = "select Passwort,vorname,nachname,plz,adresse,job,Email from tab_User where Benutzername like '".$Benutzername."'";
    $UserProfile = convertSelectToArray($sql);
    if($Password_Correct = password_verify($Passwort,$UserProfile[0])
      $_SESSION['profile'] = createProfile($Benutername,$UserProfile);
    return $Password_Correct;
  }

  function isLoggedIn(){
    if(!isset($_SESSION['profile'])) return false;
    return true;
  }

  /******************************************************
        FUNKTIONEN ZUM HINZUFUEGEN IN DIE DATENBANK
  ******************************************************/

  function addExtra($Ausstattung){
    return addSql("tab_Extra","Ausstattung","'".$Ausstattung."'");
  }

  function addMangel($Mangel){
    return addSql("tab_Mangel","Mangel","'".$Mangel."'");
  }

  function addMotor($Motor){
    return addSql("tab_MotorArt","Motor","'".$Motor."'");
  }

  function addMarke($Marke){
    return addSql("tab_Marke","Marke","'".$Marke."'");
  }

  function addKlasse($Klasse){
    return addSql("tab_Klasse","Klasse","'".$Klasse."'");
  }

  function addTyp($Typ,$KlassenNr,$MarkenNr){
    return addSql("tab_Typ","Typ,KlassenNr,MarkenNr","'".$Typ."',".$KlassenNr." , ".$MarkenNr);
  }

  function addOrt($PLZ,$Ort){
    return addSql("tab_Ort","PLZ,Ort","'".$PLZ."','".$Ort."'");
  }

  function addAuto($TypNr,$Farbe,$Kurzbeschreibung,$Detailbeschreibung,$StellplatzNr,$Baujahr,$Leistung,$KMLaufleistung,$MangelArray,$ExtrasArray,$MotorenArray){
    $success = addSql("tab_Auto","TypNr,Farbe,Kurzbeschreibung,Detailbeschreibung,StellplatzNr,Baujahr,Leistung,KMLaufleistung",$TypNr.",'".$Farbe."','".$Kurzbeschreibung."','".$Detailbeschreibung."',".$StellplatzNr.",".$Baujahr.",".$Leistung.",".$KMLaufleistung);
    $AutoNr = getIdOfLastInserted();
    foreach($MangelArray as $Mangel => $Value) setMangel($AutoNr,$Value);
    foreach($ExtrasArray as $Extra => $Value) setAusstattung($AutoNr,$Value);
    foreach($MotorenArray as $MotorenNr => $Value) setAusstattung($AutoNr,$Value);
    return $success;
  }

  function addBild($AutoNr, $Bildpfad){
    return addSql("tab_Bilder","AutoNr,BildPfad",$AutoNr.",'".$Bildpfad."'");
  }

  /******************************************************
                          SETTER
  ******************************************************/

  function setMangel($AutoNr,$MangelNr){
    return addSql("tab_Auto_Mangel","AutoNr,MangelNr",$AutoNr.",".$MangelNr);
  }

  function setStellplatz($AutoNr,$StellplatzNr){
    return updateAutoSql($AutoNr,"StellplatzNr",$StellplatzNr);
  }

  function setAusstattung($AutoNr,$AusstattungsNr){
    return addSql("tab_Ausstattung","AutoNr,AusstattungsNr",$AutoNr.",".$AusstattungsNr);
  }

  function setMotoren($AutoNr,$MotorenNr){
    return addSql("tab_Motor","AutoNr,MotorNr",$AutoNr.",".$MotorenNr);
  }

  function setFarbe($AutoNr,$Farbe){
    return updateAutoSql($AutoNr,"Farbe",$Farbe);
  }

  function setKurbeschreibung($AutoNr,$Kurzbeschreibung){
    return updateAutoSql($AutoNr,"Kurzbeschreibung",$Kurzbeschreibung);
  }

  function setDetailbeschreibung($AutoNr,$Detailbeschreibung){
    return updateAutoSql($AutoNr,"Detailbeschreibung",$Detailbeschreibung);
  }

  function setKMLaufleistung($AutoNr,$KMLaufleistung){
    return updateAutoSql($AutoNr,"KMLaufleistung",$KMLaufleistung);
  }

  function setLeistung($AutoNr,$Leistung){
    return updateAutoSql($AutoNr,"Leistung",$Leistung);
  }

  function setBaujahr($AutoNr,$Baujahr){
    return updateAutoSql($AutoNr,"Baujahr",$AutoNr);
  }

  /******************************************************
                       GET ABFRAGEN
  ******************************************************/

  function getMarken(){
    return selectSqlArray("tab_Marke","MarkenNr,Marke","1","1","=");
  }

  function getExtras(){
    return selectSqlArray("tab_Extra","AusstattungsNr,Ausstattung","1","1","=");
  }

  function getStellplaetze(){
    return selectSqlArray("tab_Stellplatz","StellplatzNr,Stellplatz","1","1","=");
  }

  function getKlassen(){
    return selectSqlArray("tab_Klasse","KlassenNr,Klasse","1","1","=");
  }

  function getTypen(){
    $sql = "select TypNr,Typ,tab_Marke.Marke,tab_Klasse.Klasse from getTypen";
    return convertTypToArray($sql);
  }

  function getMotoren(){
    return selectSqlArray("tab_MotorArt","MotorNr,Motor","1","1","=");
  }

  /******************************************************
                          DELETE
  ******************************************************/

  function deleteBild($BildNr){
    return deleteSql("tab_Bilder","BildNr",$BildNr);
  }

  function deleteStellplatz($StellplatzNr){
    return deleteSql("tab_Stellplatz","StellplatzNr",$StellplatzNr);
  }

  function deleteMangel($MangelNr){
    return deleteSql("tab_Mangel","MangelNr",$MangelNr);
  }

  function deleteAusstattung($AusstattungsNr){
    return deleteSql("tab_Extra","AusstattungsNr",$AusstattungsNr);
  }

  function deleteMotorArt($MotorNr){
    return deleteSql("tab_MotorArt","MotorNr",$MotorNr);
  }

  function deleteMarke($MarkenNr){
    return deleteSql("tab_Marke","MarkenNr",$MarkenNr);
  }

  function deleteKlasse($KlassenNr){
    return deleteSql("tab_Klasse","KlassenNr",$KlassenNr);
  }

  /******************************************************
                         CONVERTER
  ******************************************************/

  function PLZToOrt($PLZ){
    return selectSql("tab_ort","ort","PLZ",$PLZ,"like");
  }

  function StellplatzNrToStellplatz($StellplatzNr){
    return selectSql("tab_Stellplatz","Stellplatz","StellplatzNr",$StellplatzNr,"=");
  }

  function ExtraNrToExtra($ExtraNr){
    return selectSql("tab_Extra","Ausstattung","AusstattungsNr",$ExtraNr,"=");
  }

  function MarkenNrToMarke($MarkenNr){
    return selectSql("tab_Marke","Marke","MarkenNr",$MarkenNr,"=");
  }

  function MangelNrToMangel($MaengelNr){
    return selectSql("tab_Mangel","Mangel","MangelNr",$MaengelNr,"=");
  }

  function KlassenNrToKlasse($KlassenNr){
    return selectSql("tab_Klasse","Klasse","KlassenNr",$KlassenNr,"=");
  }

  function AutoNrToBilder($AutoNr){
    return selectSqlArray("tab_Bilder","BildNr, BildPfad","AutoNr",$AutoNr,"=");
  }

  function AutoNrToAuto($AutoNr){
	  return convertAutoToArray($AutoNr);
  }

  function BenutzernameToWatchlist($Benutzername){
    return convertWatchlistToArray($Benutzername);
  }

  function AutoNrToExtras($AutoNr){
    return selectSqlArray("AutoNrToExtras","AusstattungsNr, Ausstattung","AutoNr",$AutoNr,"=");
  }

  function AutoNrToMangel($AutoNr){
    return selectSqlArray("AutoNrToMangel","MangelNr,Mangel","AutoNr",$AutoNr,"=");
  }

  function AutoNrToMotor($AutoNr){
    return selectSqlArray("AutoNrToMotor","MotorNr,Motor","AutoNr",$AutoNr,"=");
  }

  /******************************************************
                      INTERNE FUNKTIONEN
  ******************************************************/

  function errorHappend($errorMsg){
    $sql = "insert into tab_Fehlermeldung(Fehlermeldung) values('".$errorMsg."')";
    return sqlInsertOrUpdate($sql);
  }

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

  function convertSelectToArray($sql){
    $_Values = sendToDatabase($sql);
    $Values = mysqli_fetch_array($_Values);
    return $Values;
  }

  function sqlInsertOrUpdate($sql){
    if(!sendToDatabase($sql)) return false;
    return true;
  }

  function convertAutoToArray($AutoNr){
    $sql = "select * from convertAutoToArray where AutoNr = ".$AutoNr;
    $_AutoInformationen = sendToDatabase($sql);
    $AutoInformationen = mysqli_fetch_array($_AutoInformationen);

    $AutoArray = array(
      "Stellplatz" => $AutoInformationen[0],
      "Marke" => $AutoInformationen[1],
      "Klasse" => $AutoInformationen[2],
      "Typ" => $AutoInformationen3[],
      "AutoNr" => $AutoInformationen[4],
      "Baujahr" => $AutoInformationen[5],
      "Farbe" => $AutoInformationen[6],
      "Kurzbeschreibung" => $AutoInformationen[7],
      "Detailbeschreibung" => $AutoInformationen[8],
      "KMLaufleistung" => $AutoInformationen[9],
      "Leistung" => $AutoInformationen[10],
      "Ausstattung" => AutoNrToExtras($AutoNr),
      "Mangel" => AutoNrToMangel($AutoNr),
      "Motoren" => AutoNrToMotor($AutoNr)
    )
    return $AutoArray;
  }

  function convertWatchlistToArray($Benutzername){
    $sql = "select AutoNr,Stueckzahl,Status from tab_Watchlist where Benutzername = '".$Benutzername."'";
    $_WatchlistObjekt = sendToDatabase($sql);
    $WatchlistArray = array();
    while($WatchlistObjekt = mysqli_fetch_array($_WatchlistObjekt)){
      $WatchlistAuto = array(
        "Status" => $WatchlistObjekt[2],
        "Stueckzahl" => $WatchlistObjekt[1],
        "Auto" => convertAutoToArray($WatchlistObjekt[0])
      );
      array_push($WatchlistArray,$WatchlistAuto);
    }
    return $WatchlistArray;
  }

  function convertTypToArray($sql){
    $typenArray = array();
    $_typ = sendToDatabase($sql);
    while($typ = mysqli_fetch_array($_typ)){
      $AutoTyp = array("TypNr" => $typ[0],"Typ" => $typ[1], "Marke" => $typ[2],"Klasse" => $typ[3]);
      array_push($typenArray, $AutoTyp);
    }
    return $typenArray;
  }

  function createProfile($Benutzername,$UserProfile){
    $profileArray = array(
      "Benutzername" => $Benutzername,
      "Vorname" => $UserProfile[1],
      "Nachname" => $UserProfile[2],
      "PLZ" => $UserProfile[3],
      "Adresse" => $UserProfile[4],
      "Job" => $UserProfile[5],
		  "Email" => $UserProfile[6]
    );
    return $profileArray;
  }

  function deleteSql($table,$key,$value){
    $sql = "delete from ".$table." where ".$key." = '".$value."'";
    return sqlInsertOrUpdate($sql);
  }

  function addSql($table,$Order,$values){
    $sql = "insert into ".$table."(".$Order.") values(".$values.")";
    return sqlInsertOrUpdate($sql);
  }

  function updateAutoSql($AutoNr,$Key,$Value){
    $sql = "update tab_Auto set ".$Key." = ".$Value." where AutoNr = ".$AutoNr;
    return sqlInsertOrUpdate($sql);
  }

  function selectSql($table,$value,$whereKey,$whereValue,$comparer){
    $sql = "select ".$value." from ".$table." where ".$whereKey." ".$comparer." '".$whereValue."'";
    return convertSelectToValue($sql);
  }

  function selectSqlArray($table,$value,$whereKey,$whereValue,$comparer){
    $sql = "select ".$value." from ".$table." where ".$whereKey." ".$comparer." ".$whereValue;
    return convertTableToArray($sql);
  }
?>
