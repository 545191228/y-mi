<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $this->beginPage(); ?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="css/ymi.admin-login.css" rel="stylesheet" type="text/css" media="all"/>
	<title>Y-MI管理系统</title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="login">
	<h2>Y-MI管理系统</h2>
	<div class="login-top">
		<h1>登录</h1>
	    <?php $form = ActiveForm::begin(['id' => 'login-form']) ?>
		<?= $form->field($model, 'user_name')->textInput(['placeholder'=>'登录用户名'])->label(false) ?>
		<?= $form->field($model, 'user_pwd')->passwordInput(['placeholder'=>'登陆密码'])->label(false) ?>
	    <div class="forgot">
	    	<?= Html::submitButton('登录') ?>
	    </div>
		<?php ActiveForm::end() ?>
	</div>
	<div class="login-bottom">
		<h3>Y-MI管理系统 &copy; 2016 by shp.name</h3>
	</div>
</div>	
<div class="copyright">
	<p>Copyright &copy; 2016 by shp.name All rights reserved.<a target="_blank" href="http://shp.name/">shp</a></p>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();?>