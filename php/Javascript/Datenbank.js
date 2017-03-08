/****************************************************************
                        OVERVIEW
 ----------------------------------------------------------------
  AUFRUFBARE FUNKTIONEN
 ----------------------------------------------------------------
    - login(username,password)
    - register(username,password,email,vorname,nachname,plz,adresse)
    - getWarenkorb()
    - deleteWarenkorb()
    - getAllAutos()
    - search(suchbegriff)
 ----------------------------------------------------------------
  INHALT VERÄNDERNDE INTERNE FUNKTIONEN
 ----------------------------------------------------------------
    - registerFailed()
    - registerSuccess()
    - loginFailed()
    - loginSuccess()
    - warenkorbFailed()
    - showWarenkorb()
    - deleteWarenkorbFailed()
    - deleteWarenkorbSuccess()
    - showAutos(Autos)
    - searchFailed()
****************************************************************/


/*****************************************************
        AUFRUFBARE FUNKTIONEN
*****************************************************/

function login(username,password){
  var data = new FormData();
  data.append('username', username);
  data.append('password', password);
  var loginData = askThePhpScript('login',data,loginCallback);
}

function register(Benutzername,Passwort,Email,Vorname,Nachname,Plz,Adresse){
  var data = new FormData();
  data.append('username', Benutzername);
  data.append('passwort', Passwort);
  data.append('email', Email);
  data.append('vorname', Vorname);
  data.append('nachname', Nachname);
  data.append('plz', Plz);
  data.append('adresse', Adresse);
  var register = askThePhpScript('register',data,registerCallback);
}

function getWarenkorb(){
  askThePhpScript('getWarenkorb',"",getWarenkorbCallback);
}

function deleteWarenkorb(){
  askThePhpScript('deleteWarenkorb',"",deleteWarenkorbCallback);
}

function getAllAutos(){
  askThePhpScript('getAllAutos',"",getAllAutosCallback);
}

function search(suchbegriff){
  var data = new FormData();
  data.append('suchbegriff',suchbegriff);
  askThePhpScript('search',data,searchCallback);
}

/*****************************************************
		    INHALT VERÄNDERNDE INTERNE FUNKTIONEN
*****************************************************/

function registerFailed(){
	var text = document.getElementById("registerMessage");
	text.innerHTML = "Registrierung Fehlgeschlagen";
}

function registerSuccess(){
	var text = document.getElementById("registerMessage");
	text.innerHTML = "Erfolgreich Registriert";
	window.refresh();
}

function loginFailed(){
	var text = document.getElementById("loginMessage");
	text.innerHTML = "Login Fehlgeschlagen";
}

function loginSuccess(){
	var text = document.getElementById("loginMessage");
	text.innerHTML = "Erfolgreich Eingeloggt";
	window.refresh();
}

function warenkorbFailed(){
  var text = document.getElementById("warenkorbMessage");
  text.innerHTML = "Warenkorb konnte nicht geladen werden";
}

function showWarenkorb(){
  ///
}

function deleteWarenkorbFailed(){
  var text = document.getElementById("warenkorbMessage");
  text.innerHTML = "Warenkorb konnte nicht gelöscht werden";
}

function deleteWarenkorbSuccess(){
  var text = document.getElementById("warenkorbMessage");
  text.innerHTML = "Warenkorb wurde gelöscht";
}

function showAutos(Autos){
  ///
}

function searchFailed(){
  ///
}

/*****************************************************
                  CALLBACK FUNKTIONEN
*****************************************************/

function registerCallback(responseText){
	if(responseText == "false"){
		registerFailed();
		return;
	}
	registerSuccess();
}

function loginCallback(responseText){
  if(responseText == "false"){
    loginFailed();
    return;
  }
  loginSuccess();
}

function getWarenkorbCallback(responseText){
  if(responseText == "false"){
    warenkorbFailed();
    return;
  }
  showWarenkorb(responseText);
}

function deleteWarenkorbCallback(responseText){
  if(responseText == "false"){
    deleteWarenkorbFailed();
    return;
  }
  deleteWarenkorbSuccess();
}

function getAllAutosCallback(responseText){
  showAutos(responseText);
}

function searchCallback(responseText){
  if(responseText == "false"){
    searchFailed();
    return;
  }
}

/*****************************************************
                  INTERNE FUNKTIONEN
*****************************************************/

function askThePhpScript(call,data,callback){
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'JsToPhP.php?call='+call, true);
  xhr.onload = function () {
	  callback(this.responseText);
  };
  xhr.send(data);
}
