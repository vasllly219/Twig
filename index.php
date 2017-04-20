<?php
session_start();
if (!isset($_SESSION['user'])){die("<a href='register.php'>Войдите на сайт</a>");}
require __DIR__ . '/vendor/autoload.php';
//error_reporting(0);

$loader = new Twig_Loader_Filesystem('./template');
$twig = new Twig_Environment($loader, array('cache' => '.tmp/cache', 'auto_reload' => true, 'debug' => true)); //'auto_reload' = true //auto_reload = false
$twig->addExtension(new Twig_Extension_Debug());
$config = include 'config.php';

/**
 * Подключение к базе данных
 */
include 'lib/database/DataBase.php';

$db = DataBase::connect(
	$config['mysql']['host'],
	$config['mysql']['dbname'],
	$config['mysql']['user'],
	$config['mysql']['pass']
);
//echo '<h1>Пока не доделал ДЗ :(</h1>';
include 'lib/router/router.php';

?>
<a href="logout.php">Выход</a><br/>
