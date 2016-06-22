<?php

//Fehleranzeige konfigurieren
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

//Die Adresse des Proxy-Servers
$server_adress = "noscio.eu/xIdent";

//Falls bei PHP die Funktion 'getallheaders' nicht existiert, wird sie einfach neu definiert
if (!function_exists('getallheaders')) 
{ 
    function getallheaders() 
    { 
           $headers = ''; 
       foreach ($_SERVER as $name => $value)
       { 
           if (substr($name, 0, 5) == 'HTTP_') 
           { 
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
           } 
       } 
       return $headers; 
    } 
} 

//Die URL des Proxy-Skriptes
$xIdent_url = "http://".$server_adress."/test.php?u=";

//Für Debugging
$logHeaders = FALSE;

//Für Bilder, Videos... geeignet
$binary = false;

//Seite, zu der alles umgeleitet wird
$site = $_GET['u'];

//Führende / löschen
$site = preg_replace("/^\/+/",'',$site);

//ggf. HTTP:// hinzufügen
if(!startsWith($site,"http")) {
	$site = "http://".$site;
}

//Falls die Datei auf dem Server existiert, wird diese aufgerufen
//geht noch nicht
/*$path = parse_url($site, PHP_URL_PATH);
echo $path;

if(file_exists($path)) {
	header("HTTP/1.1 303 See Other");
	header("Location: ".$server_adress."/".$path);
	exit();
}
else {
	#echo $path;
}*/

//Ein neuer Anfrageversuch, z.B. falls Antwort doch binary
newtry:

//CURL wird initialisiert
$ch = curl_init();

//Kein Cache
curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);

//POST-Daten werden weitergeleitet
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($_POST));
}

//Anfrage URL wird gesetzt
curl_setopt($ch, CURLOPT_URL, $site);

//Wir brauchen die HTTP Antwort-Header
curl_setopt($ch, CURLOPT_HEADER, TRUE);

//Header, die der Client an den Proxy gesendet hat
$headers = getallheaders();

/* Translate some headers to make the remote party think we actually browsing that site. */
$extraHeaders = array();
if (isset($headers['Referer'])) 
{
    $extraHeaders[] = 'Referer: '. str_replace($proxyDomain, $remoteDomain, $headers['Referer']);
}
if (isset($headers['Origin'])) 
{
    $extraHeaders[] = 'Origin: '. str_replace($proxyDomain, $remoteDomain, $headers['Origin']);
}

//Header des Clients mit einigen Modifikationen setzen
curl_setopt($ch, CURLOPT_HTTPHEADER, $extraHeaders);

//Cookies weiterleiten
if (isset($headers['Cookie']))
{
    curl_setopt($ch, CURLOPT_COOKIE, $headers['Cookie']);
}

//Wir brauchen natürlich auch die Antwort
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

//ggf. Header loggen
if ($logHeaders)
{
    $f = fopen("headers.txt", "a");
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
    curl_setopt($ch, CURLOPT_STDERR, $f);
}

//Weiterleitungen folgen
//geht nicht
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

//URL und Domain sichern
$last_url = $site;
$last_domain = parse_url($last_url, PHP_URL_SCHEME)."://".parse_url($last_url, PHP_URL_HOST);

//Falls eine binäre Antwort erwartet wird
if($binary) {
	$ch2 = curl_init ($site);

	//Kein Cache
	curl_setopt($ch2, CURLOPT_FRESH_CONNECT, TRUE);

	//Header stören dabei nur
	curl_setopt($ch2, CURLOPT_HEADER, 0);
	
	//Wir brauchen hier natürlich auch eine Antwort
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);

	//Die Antwort ist binär
	curl_setopt($ch2, CURLOPT_BINARYTRANSFER,1);

	//Anfrage ausführen und speichern
	$image=curl_exec($ch2);

	//Content-Type auslesen
	$contentType = curl_getinfo($ch2, CURLINFO_CONTENT_TYPE);

	//CURL schließen
	curl_close ($ch2);

	//Falls kein Content-Type HTTP 404
	if(strlen($contentType)<1) {
		header('Content-Type: text/html');
		echo '404 Not found';
		exit();
	}

	//Header für Content-Type setzen
	header('Content-Type: '.$contentType);
	
	//Daten an den Client zurückgeben und das Skript beenden
	echo $image;
	exit();
}

$response = curl_exec($ch);

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);

//HTML-Sonderzeichen
$response = html_entity_decode($response);

$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
if(!$binary) {
if(!isText($contentType)) {
	$binary = true;
	goto newtry;
}
else {
	$binary = false;
}
}

//TODO: Variable benennen
$l = "no_url";
if(preg_match("/Location: (.*)/", $header, $r)==1){
    $l = trim($r[1]);
    $site = $l;
    //$site = $xIdent_url.$last_domain."/".$site;
    #echo "!!".$site."!!";
    goto newtry;
    
}
if(strcmp($l,"no_url")!=0) {
    $site = $l;
    goto newtry;
}
/*print_r($header);
#header('Content-Type: '.$contentType);
$r = explode('\r\n', $header);
var_dump($r);
foreach($r as $header_line) {
header($header_line);
}*/

if(preg_match("/Set-Cookie: (.*)/", $header, $r2)==1){
    $cookie = trim($r2[1]);
    header("Set-Cookie: ".$cookie);
}

header("Content-Type: ".$contentType);

//Alle Links suchen

$response = modifyHTML($response,$last_domain,$xIdent_url);

//geht noch nicht
//$response = modifyCSS($response,$last_domain,$xIdent_url);

#print_r($header);
$body = substr($response, $header_size);

curl_close($ch);

$body = str_replace("&","&amp",$body);

echo $body;

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}
function modifyHTML($html,$site,$xIdent_url) {
	$html_tags = array("href", "link", "src", "action");
	foreach($html_tags as $html_tag) {
		preg_match_all("/".$html_tag."([ \n\r]*)=([ \n\r]*)\"([^\"]*)\"/m",$html, $treffer);
		for ($i = 0; $i < count($treffer[3]); $i++) {
			$url_old = $treffer[0][$i];
			$new_h = modifyURL($treffer[3][$i],$site,$xIdent_url);
			$url_new = str_replace($treffer[3][$i],$new_h,$url_old);
			$html = str_replace($url_old,$url_new,$html);
		}
		preg_match_all("/".$html_tag."([ \n\r]*)=([ \n\r]*)\'([^\']*)\'/m",$html, $treffer);
		for ($i = 0; $i < count($treffer[3]); $i++) {
			$url_old = $treffer[0][$i];
			$new_h = modifyURL($treffer[3][$i],$site,$xIdent_url);
			$url_new = str_replace($treffer[3][$i],$new_h,$url_old);
			$html = str_replace($url_old,$url_new,$html);
		}
	}
	return $html;
}
function modifyCSS($css,$site,$xIdent_url) {
	$css_tags = array("background-image: url(");
	foreach($css_tags as $css_tag) {
		preg_match_all("/".$css_tag."([ \n\r]*)\"([^\"]*)\"/m",$css, $treffer);
		for ($i = 0; $i < count($treffer[3]); $i++) {
			$url_old = $treffer[0][$i];
			$new_h = modifyURL($treffer[3][$i],$site,$xIdent_url);
			$url_new = str_replace($treffer[3][$i],$new_h,$url_old);
			$css = str_replace($url_old,$url_new,$css);
		}
		preg_match_all("/".$css_tag."([ \n\r]*)\'([^\']*)\'/m",$css, $treffer);
		for ($i = 0; $i < count($treffer[3]); $i++) {
			$url_old = $treffer[0][$i];
			$new_h = modifyURL($treffer[3][$i],$site,$xIdent_url);
			$url_new = str_replace($treffer[3][$i],$new_h,$url_old);
			$css = str_replace($url_old,$url_new,$css);
		}
	}
	return $css;
}
function modifyURL($old_url,$site,$xIdent_url) {
	$relative = true;
	$old_url_mod = removeSlashesAtStart($old_url);
	preg_match('@^(?:http://)?(?:https://)?([^/]+)@i',$old_url_mod, $treffer);
	$host = $treffer[1];
	//echo $old_url."|".$host.";";
	if(startsWith($old_url,"http://")) {
		$relative = false;
	}
	if(startsWith($old_url,"https://")) {
		$relative = false;
	}
	if(validateDomain($host)) {
		$relative = false;
			//echo $old_url."|".$host.";";
	}
	if($relative) {
		$old_url = $site."/".$old_url;
	}
	$old_url = $xIdent_url.$old_url;
	$old_url = preg_replace('~/{2,}~', '/', $old_url);
	$old_url = str_replace('http:/','http://',$old_url);
	$old_url = str_replace('https:/','https://',$old_url);
	return $old_url;
}
function isText($contentType) {
$ct_text_array = array("text", "javascript");
	foreach($ct_text_array as $ct) {
		if(contains($ct,$contentType)) {
			return true;
		}
	}
	//echo $contentType.";";
	return false;
}
function validateDomain($domain) {
	if ( gethostbyname($domain) != $domain ) {
  return true;
 }
	return false;
}
//Prüft, ob eine Zeichenkette eine andere enthält
function contains($needle, $haystack)
{
    return strpos($haystack, $needle) !== false;
}
function removeSlashesAtStart($uri) {
$uri = ltrim($uri, '/');
return $uri;
}
?>
