<?php
use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?php Yii::$app->language ?>">
<head>
    <meta charset="<?php Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-with, initial-scale=1">
    <?php Html::csrfMetaTags() ?>
    <title><?php Html::encode($this->title)?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?= $content ?>

<?php $this->endBidy() ?>
</body>
</html>
<?php $this->endPage();?>
