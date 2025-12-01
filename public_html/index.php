<?php
$f3=require 'lib/base.php';
$f3->config('config/config.ini');
$f3->config('config/routes.ini');
loadEnv(dirname(__DIR__) . '/variables.env');
$f3->LANGUAGE = $f3->get('sitelang');
$f3->set('ONERROR',function($f3){
  echo \Template::instance()->render('error.html');
  $e = $f3->get('EXCEPTION');
  if (!$e instanceof Throwable) {
	$logger = new Log('logs/'.date("Ymd").'error.log');
	$logger->write( $f3->get('ERROR.code') . ": ". $f3->get('ERROR.text'). " trace: ". $f3->get('ERROR.trace'),'r'  );
  }
});
$f3->logger = new Log('logs/'.date("Ymd").'.log');
$f3->session = new Session();
$language = $f3->get('SESSION.language') ?? 'en'; // Default to English if no language is set
$f3->config("config/setup_$language.cfg");
$f3->run();

function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        throw new Exception("Environment file not found: $filePath");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Skip comments
        [$name, $value] = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
        putenv("$name=$value");
    }
}