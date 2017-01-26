<?php
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
?>
