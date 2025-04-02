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
        <?= $this->render('@app/views/layouts/navbar.php') ?>
        <?= $this->render('@app/views/layouts/sidebar.php') ?>
        <main class="app-main">
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6" id="breadcumb-menu-title">
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end" id="breadcrumb">
                            </ol>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <div class="app-content">
            <!--begin::Container-->
                <div class="container-fluid">
                    <?= $content ?>
                </div>
            <!--end::Container-->
            </div>
        </main>
        <?= $this->render('@app/views/layouts/footer.php') ?>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<script type="text/javascript">
    var _yiiControllerId = '<?php echo Yii::$app->controller->id; ?>';

    $(function()
    {
        $.breadcrumb(_yiiControllerId);
        
        if (typeof(Storage) !== "undefined") {
            // clear all storage before
            localStorage.removeItem('logout');
            localStorage.removeItem('login');
            
            // trigger refresh if there's new wo or update wo
            window.addEventListener('storage', function(e) 
            {
                if (localStorage.getItem('logout') === 'true')
                {
                    localStorage.setItem('logout', false);
                    location.reload();
                }

                if (localStorage.getItem('login') === 'true')
                {
                    localStorage.setItem('login', false);
                    location.reload();
                }
            });	
        }
    });
</script>