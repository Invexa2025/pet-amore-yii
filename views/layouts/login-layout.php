<?php

    /** @var yii\web\View $this */
    /** @var string $content */

    use app\assets\AppAsset;
    use app\widgets\Alert;
    use yii\helpers\Html;
    use yii\helpers\Url;

    AppAsset::register($this);

    $language = 'en-US';
    $overlayImageUrl = Url::to('@web/images/loading-bar.gif');

    $this->registerCsrfMetaTags();
    $this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
    $this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="<?php echo Yii::$app->getUrlManager()->getBaseUrl() ?>/images/invexa-logo.png" type="image/x-icon">
    <?php $this->head() ?>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary sidebar-open app-loaded">
<?php $this->beginBody() ?>
    <div id="overlay-img" data-overlay-image="<?= $overlayImageUrl ?>"></div>
    <?= $this->render('@app/views/layouts/env.php') ?>
    <div class="app-wrapper">
        <?= $this->render('@app/views/layouts/login-navbar.php') ?>
        <main class="app-main">
            <div class="app-content">
                <!--begin::Container-->
                <div class="container-fluid">
                    <?= $content ?>
                </div>
                <!--end::Container-->
            </div>
        </main>
        <?= $this->render('@app/views/layouts/login-footer.php') ?>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<script type="text/javascript">
    $(function()
    {
        if (typeof(Storage) !== "undefined") {
            localStorage.setItem('logout', true);
        }
        
        $('#txtProcessTime').html('<?= sprintf('%0.3f', Yii::getLogger()->getElapsedTime()) ?>' + 's');

		// clockDisplay();
		$.digitalClockSkeleton('clock');

		var lang = '<?php echo Yii::$app->language ?>';

		$(document).ajaxComplete(function(event, data, settings)
		{
			if (typeof data.responseJSON !== 'undefined')
			{
				processTime = data.responseJSON.processtime;

				$('#txtProcessTime').html(processTime + 's');
			}
		});
    });

    function clockDisplay()
	{
		let date = new Date();
		let s = date.toString();
		let zoneName = s.match(".*(\\((.*)\\))")[2];
		let dateDisplay = s.substring(0, (s.search(zoneName) - 1));

		document.getElementById('clock').innerHTML = dateDisplay;

		let refresh = 1000;
		let timeoutVal = setTimeout('clockDisplay()', refresh);
	}
</script>
