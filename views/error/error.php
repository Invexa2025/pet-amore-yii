<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>

<?php
    $statusCode = 500;

	if (isset($exception->statusCode))
	{
		$statusCode = $exception->statusCode;
	}
?>

<div class="error-page container-fluid">
	<div class="col-sm-12">
		<div class="m-1 text-center">
			<?php if (404 == $statusCode): ?>
				<div class="error-detail-div" id="404">
					<img id="cruise" class="error-icon my-5 py-2" src="<?= Yii::getAlias('@web') ?>/images/invexa-logo.png" height="150"></img>
					<h3 class="font-weight-bold mt-3">
						<?= Yii::t('app', 'Page not found') ?>
					</h3>
					<p class="text-center"><?= Yii::t('app', 'The page you requested is not found.') ?><br><?= Yii::t('app', 'Please contact support team if you think this is server error.') ?></p>
				</div>
			<?php elseif (500 == $statusCode): ?>
				<div class="error-detail-div" id="500">
					<h3 class="font-weight-bold">
						<?= Yii::t('app', 'Server Side Error') ?>
					</h3>
					<pre id="errorMessagePlaceholder"><?= Yii::t('app', 'Please try again later or you may contact the developer.') ?></pre>
				</div>
			<?php else: ?>
				<div class="error-detail-div">
					<h3 class="font-weight-bold">
						<?= Yii::t('app', 'Unknown Error') ?>
					</h3>
					<pre id="errorMessagePlaceholder"><?= Yii::t('app', 'Please try again later or you may contact the developer.') ?></pre>
				</div>
			<?php endif; ?>
			<a class="btn btn-primary" href="<?= Yii::$app->getUrlManager()->createUrl('dashboard') ?>"><?= Yii::t('app', 'Back to Home') ?></a>
		</div>
	</div>
</div>
