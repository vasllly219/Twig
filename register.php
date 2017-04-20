<?php
require_once 'function.php';
$flug = getFlug();
if (isPOST() && $flug <= 2) {
    if(auth(getParam('login'), getParam('password'))) {
        setError(0);
        header('Location: index.php');
        die;
    }
    if (registration(getParam('login'), getParam('password'))){
        setError(0);
        header('Location: index.php');
        die;
    }
    setError(1);
    logout();

}
if (isPOST() && $flug > 2 && $flug < 8) {
    if(checkCaptcha(getParam('captcha'))) {
        if(auth(getParam('login'), getParam('password'))) {
            setError(0);
            header('Location: index.php');
            die;
        }
        if (registration(getParam('login'), getParam('password'))){
            setError(0);
            header('Location: index.php');
            die;
        }
        setError(1);
        logout();
    } else {
    setError(1);
    logout();
    }
}
if ($flug >= 8){
  $flug = setError($flug);
  if (getTimeBlock() > 0){
    echo 'Ваш IP заблокирован, осталось ждать: ' . getTimeBlock() . ' секунд';
    die;
  } else {
    setError(0);
  }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Авторизация</title>
</head>
<body>
<div id="fullscreen_bg" class="fullscreen_bg"/>
<div class="container">
    <form class="form-signin" method="POST">
    	<h1 class="form-signin-heading text-muted">Авторизация*</h1>
    	<input type="text" name="login" class="form-control" placeholder="Login" required="" autofocus="">
    	<input type="password" name="password" class="form-control" placeholder="Password" required="" autofocus="">
    <?php if ($flug >= 2){?>
    <img src="captcha.php" /><br />
    <label for="captcha">Введите код с картинки</label><br />
    <input id="captcha" type="text" name="captcha" required="" autofocus="" /><br />
    <?php } if ($flug > 0){echo '<p>Такой пользователь уже существует в базе данных. (Введен не верный пароль)</p>';}?>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Авторизоваться</button>
    <p>* - если у вас нет аккаунта, введите логин и пароль и вы автоматически зарегистрируетесь.</p>
</form>
</div>
</body>
</html>
