<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

$server_adress = "10.0.15.133";

//FROM STACKOVERFLOW
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
//ENDE


$xIdent_url = "http://".$server_adress."/test.php?u=";

/* Set it true for debugging. */
$logHeaders = FALSE;
$binary = false;

/* Site to forward requests to.  */
$site = 'https://lima-city.de/';
$site = $_GET['u'];
#echo $site;

// Delete / at beginning
$site = preg_replace("/^\/+/",'',$site);

if(!startsWith($site,"http")) {
	$site = "http://".$site;
}

//$path = parse_url($site, PHP_URL_PATH);
//echo $path;

/*if(file_exists($path)) {
	header("HTTP/1.1 303 See Other");
	header("Location: ".$server_adress."/".$path);
	exit();
}
else {
	#echo $path;
}*/

/* Domains to use when rewriting some headers. */
#$remoteDomain = 'remotesite.domain.tld';
#$proxyDomain = 'proxysite.tld';

$request = $_SERVER['REQUEST_URI'];

newtry:

$ch = curl_init();

/* If there was a POST request, then forward that as well.*/
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($_POST));
}
curl_setopt($ch, CURLOPT_URL, $site);
curl_setopt($ch, CURLOPT_HEADER, TRUE);

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

/* Forward cookie as it came.  */
curl_setopt($ch, CURLOPT_HTTPHEADER, $extraHeaders);
if (isset($headers['Cookie']))
{
    curl_setopt($ch, CURLOPT_COOKIE, $headers['Cookie']);
}
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

if ($logHeaders)
{
    $f = fopen("headers.txt", "a");
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
    curl_setopt($ch, CURLOPT_STDERR, $f);
}
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$last_url = $site;
$last_domain = parse_url($last_url, PHP_URL_SCHEME)."://".parse_url($last_url, PHP_URL_HOST);
//echo $last_url;

if($binary) {
	$ch2 = curl_init ($site);
	curl_setopt($ch2, CURLOPT_HEADER, 0);
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch2, CURLOPT_BINARYTRANSFER,1);
	$image=curl_exec($ch2);
	$contentType = curl_getinfo($ch2, CURLINFO_CONTENT_TYPE);
	curl_close ($ch2);
	header('Content-Type: '.$contentType);
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
    $site = $xIdent_url.$site;
    goto newtry;
    
}
if(strcmp($l,"no_url")!=0) {
    $site = $l;
    goto newtry;
}

header('Content-Type: '.$contentType);

//Alle Links suchen

$response = modifyHTML($response,$last_domain,$xIdent_url);


$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
print_r($header);
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
