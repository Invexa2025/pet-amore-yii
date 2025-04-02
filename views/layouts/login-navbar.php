<?php
    use yii\helpers\Url;
?>

<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body p-0">
    <div class="sidebar-brand" style="border: none;">
        <!--begin::Brand Link-->
        <a href="<?= Yii::$app->getUrlManager()->createUrl('dashboard') ?>" class="brand-link">
            <!--begin::Brand Image-->
            <img src="<?=Yii::getAlias('@web') . '/images/invexa-logo.png' ?>" alt="Invexa Logo" class="brand-image shadow"/>
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">Invexa</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
				<a class="nav-link dropdown-toggle" href="#" id="navbar-dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">English (UK)</a>
				<div class="dropdown-menu dropdown-menu-right shadow animate slideIn" aria-labelledby="navbar-dropdown">
					<a class="dropdown-item" href="#">English (UK)</a>
				</div>
			</li>
        </ul>
        <!--end::End Navbar Links-->
    </div>
  <!--end::Container-->
</nav>
<!--end::Header-->