<?php
	use yii\helpers\Url;
?>

<!--begin::Footer-->
<footer class="app-footer">
    <!--begin::To the end-->
    <div class="float-end d-none d-sm-inline">
        <a href="<?= Yii::$app->getUrlManager()->createUrl('speed-test') ?>" class="link-label">
            <?= Yii::t('app', 'Speed Test') ?>
        </a> 
        <span id="clock"></span>
        |
        <span id="txtProcessTime"></span>
    </div>
    <!--end::To the end-->
    <!--begin::Copyright-->
    <strong>
        Copyright © 2025&nbsp;
        <a href="#" class="text-decoration-none">Invexa</a>.
    </strong>
        All rights reserved.
    <!--end::Copyright-->
</footer>
<!--end::Footer-->
<script type="text/javascript">
    $(function()
    {
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
