<?php

/**
 * Второй уровень понимания роутеров.
 * Сделаем более универсальным
 * Пример урла: /?/{controller}/{action}/{param1}/{value1}/{param2}/{value2}/
 * /?/book/update/id/1/
 */
$params = [];
$pathList = preg_split('/\//', $_SERVER['REQUEST_URI'], -1, PREG_SPLIT_NO_EMPTY);
$temp = array_search('5_2', $pathList);
for ($i=0; $i < $temp + 2 ; $i++) {
	array_shift($pathList);
}
// Значение по умолчанию
if (count($pathList) < 2) {
	$pathList = ['task', 'list'];
}
if (count($pathList) >= 2) {
	$controller = array_shift($pathList);
	$action = array_shift($pathList);
	foreach ($pathList as $i => $value) {
		if ($i % 2 == 0 && isset($pathList[$i + 1])) {
			$params[$pathList[$i]] = $pathList[$i + 1];
		}
	}
	$controllerText = $controller . 'Controller';
	//echo $controllerText;
	$controllerFile = 'controller/' . ucfirst($controllerText) . '.php';
	if (is_file($controllerFile)) {
		include $controllerFile;
		if (class_exists($controllerText)) {
			$controller = new $controllerText($db, $twig);
			$action = ($_SERVER['REQUEST_METHOD'] == 'POST' ? 'post' : 'get').ucfirst($action);
			if (method_exists($controller, $action)) {
				$controller->$action($params, $_POST);
			}
		}
	}
}
