<?php
$f3=require 'lib/base.php';

$f3->config('config/config.ini');
$f3->config('config/routes.ini');

$f3->LANGUAGE = $f3->get('sitelang');




// Check if a language is set in the session, otherwise default to the config setting
//$language = $f3->exists('SESSION.sitelang') ? $f3->get('SESSION.sitelang') : $f3->get('LANGUAGE');
//die($language);
//$language = 'sp';


//die($language);
// Load the appropriate language file based on the session or config setting
//$f3->config('dict/' . $language . '.php');
//die($language);
// Load the appropriate language file based on the session or config setting
//$f3->config('dict/' . $f3->get('LANGUAGE') . '.php');

//$this->f3->config('dict/' . $this->f3->get('SESSION.sitelang') . '.php');

$f3->set('ONERROR',function($f3){
  echo \Template::instance()->render('error.html');
  $e = $f3->get('EXCEPTION');
  // There isn't an exception when calling `Base->error()`.
  if (!$e instanceof Throwable) {
	$logger = new Log('logs/'.date("Ymd").'error.log');
	$logger->write( $f3->get('ERROR.code') . ": ". $f3->get('ERROR.text'). " trace: ". $f3->get('ERROR.trace'),'r'  );
  }
});

$f3->logger = new Log('logs/'.date("Ymd").'.log');

$f3->session = new Session();

//die('hola'.'   '.$f3->get('SESSION.language'));
// Check if the user language is set and load the appropriate language config
$language = $f3->get('SESSION.language') ?? 'en'; // Default to English if no language is set
//$f3->set('LANGUAGE', $language);
$f3->config("config/setup_$language.cfg");

//die('hola');
$f3->run();