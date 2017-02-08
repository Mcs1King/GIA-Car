<?php
    /***********************************************************
                            OVERVIEW
    ------------------------------------------------------------
     FORMATIERUNGS FUNKTIONEN
    ------------------------------------------------------------
        - outputFunctionTest(FunctionName, Return)
        - generateRandomString(length)
    ------------------------------------------------------------
     TESTEN DER USER-MANAGEMENT FUNKTIONEN
    ------------------------------------------------------------
        - Datenbank Verbindung
        - User Erstellen
        - Login
        - Überprüfung ob Eingeloggt
    ------------------------------------------------------------
     TESTEN DER HINZUFÜGE FUNKTIONEN
    ------------------------------------------------------------
        - Extras Hinzufügen
        - Mangel Hinzufügen
        - Motor Hinzufügen
        - Motor Hinzufügen
        - Marke Hinzufügen
        - Klasse Hinzufügen
        - Typ Hinzufügen
        - Ort Hinzufügen
        - Auto Hinzufügen
        - Bild Hinzufügen
    ------------------------------------------------------------
     TESTEN DER SETTER
    ------------------------------------------------------------
        - Mangel Setzen
        - Stellplatz Setzen
        - Ausstattung Setzen
        - Motor Setzen
        - Farbe Setzen
        - Kurzbeschreibung Setzen
        - Detailbeschreibung Setzen
        - KMLaufleistung Setzen
        - Leistung Setzen
        - Baujahr Setzen
    ------------------------------------------------------------
     TESTEN DER GET ABFRAGEN
    ------------------------------------------------------------
        - Get Marken
        - Get Extras
        - Get Stellplatz
        - Get Klassen
        - Get Typen
        - Get Motoren
    ------------------------------------------------------------
     TESTEN DER DELETE BEFEHLE
    ------------------------------------------------------------		
		- delete Bild
        - delete Stellplatz
        - delete Mangel
        - delete Ausstattung
        - delete Motorart
        - delete Marke
		- delete Klasse
    ***********************************************************/

    include('DatenbankConnection.php');

    /******************************************************
                    FORMATIERUNGS FUNKTIONEN
    ******************************************************/
    function outputFunctionTest($FunctionName,$Return){
        $fontColor = "green";
        $Result = "Funktioniert";
        if($Return == false){ 
            $fontColor = "red";
            $Result = "Fehler";
        }
        echo "<tr><td>".$FunctionName."</td><td style='color: ".$fontColor."'>".$Result."</td></tr>";
    }

    function generateRandomString($length) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
?>

<html>
<table>
    <tr>
        <th>Funktion</th>
        <th>Result</th>
    </tr>
    <?php
        /******************************************************
                TESTEN DER USER-MANAGEMENT FUNKTIONEN
        ******************************************************/
        $Benutzername = generateRandomString(20);
        outputFunctionTest("Datenbank Verbindung",connect());
        outputFunctionTest("User Erstellen", createUser($Benutzername,"Test","test@test.de","test","test","48155","teststraße 3","Kunde"));
        outputFunctionTest("Login", login($Benutzername,"Test"));
        outputFunctionTest("Überprüfung ob Eingeloggt", isLoggedIn());

        /******************************************************
                    TESTEN DER HINZUFÜGE FUNKTIONEN
        ******************************************************/
        outputFunctionTest("Extras Hinzufügen", addExtra("Test"));
        outputFunctionTest("Mangel Hinzufügen", addMangel("Test"));
        outputFunctionTest("Motor Hinzufügen", addMotor("Test"));
        outputFunctionTest("Marke Hinzufügen", addMarke("Test"));
        outputFunctionTest("Klasse Hinzufügen", addKlasse("Test"));
        outputFunctionTest("Typ Hinzufügen", addTyp("Test",1,1));
        outputFunctionTest("Auto Hinzufügen", addAuto(1,"Gelb","Test","Test",1,1981,109,203999,array(1),array(1),array(1)));
        outputFunctionTest("Bild Hinzufügen", addBild(2,"./Test.png"));
    
        /******************************************************
                            TESTEN DER SETTER
        ******************************************************/
        outputFunctionTest("Mangel Setzen",setMangel(2,1));
        outputFunctionTest("Stellplatz Setzen", setStellplatz(2,1));
        outputFunctionTest("Ausstattung Setzen", setAusstattung(2,1));
        outputFunctionTest("Motor Setzen", setMotoren(2,1));
        outputFunctionTest("Farbe Setzen", setFarbe(2,1));
        outputFunctionTest("Kurzbeschreibung Setzen", setKurzbeschreibung(2,1));
        outputFunctionTest("Detailbeschreibung Setzen", setDetailbeschreibung(2,1));
        outputFunctionTest("KMLaufleistung Setzen", setKMLaufleistung(2,1));
        outputFunctionTest("Leistung Setzen", setLeistung(2,100));
        outputFunctionTest("Baujahr Setzen", setBaujahr(2,2000));

        /******************************************************
                        TESTEN DER GET ABFRAGEN
        ******************************************************/
        outputFunctionTest("Get Marken", getMarken());
        outputFunctionTest("Get Extras", getExtras());
        outputFunctionTest("Get Stellplatz", getStellplaetze());
        outputFunctionTest("Get Klassen", getKlassen());
        outputFunctionTest("Get Typen", getTypen());
        outputFunctionTest("Get Motoren", getMotoren());
		
		/******************************************************
                        TESTEN DER DELETE BEFEHLE
        ******************************************************/
		
		outputFunctionTest("Bild Löschen", deleteBild(6));
        outputFunctionTest("Stellplatz Löschen", deleteStellplatz(2));
        outputFunctionTest("Mangel Löschen", deleteMangel(2));
        outputFunctionTest("Ausstattung Löschen", deleteAusstattung(3));
        outputFunctionTest("Motorart Löschen", deleteMotorArt(2));
        outputFunctionTest("Marke Löschen", deleteMarke(2));
		outputFunctionTest("Klasse Löschen", deleteKlasse(3));

    ?>
</table>
<br>
<h1>Fehlermeldungen</h1>
<?php
    foreach($_SESSION['Errors'] as $Error){
        echo $Error."<br>";
    }
?>
</html>
