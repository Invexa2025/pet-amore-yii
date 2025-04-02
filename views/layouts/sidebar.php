<?php
    use yii\helpers\Url;
?>

<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="<?= Yii::$app->getUrlManager()->createUrl('dashboard') ?>" class="brand-link">
            <!--begin::Brand Image-->
            <img src="<?=Yii::getAlias('@web') . '/images/invexa-logo.png' ?>" alt="Invexa Logo" class="brand-image opacity-75 shadow"/>
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">Invexa</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
        <!--begin::Sidebar Menu-->
        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
        </ul>
        <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->

<script type="text/javascript">
	$(function() {
        generateApps();
    });

    function generateApps() {
        const controllerId = "<?= Yii::$app->controller->id ?>";
        const apps = <?= json_encode(Yii::$app->session['APPS']); ?>;
        const roleType = "<?= Yii::$app->session['ROLE_TYPE']; ?>";
        const isAdminDashboard = () => roleType == "SSA" || roleType == "SA";
        let url = '';
		let name = '';
		let appCode = '';
		let parentCode = '';

        $('.sidebar-menu').append(`
            <li class="nav-item">
                <a href=${(isAdminDashboard()) ? "<?= Yii::$app->getUrlManager()->createUrl('admin-dashboard') ?>" : "<?= Yii::$app->getUrlManager()->createUrl('dashboard') ?>"} class="nav-link ${(controllerId == "dashboard" ? "active" : '')}">
                    <p>${"<?= Yii::t('app', 'Dashboard') ?>"}</p>
                </a>
            </li>
        `);

        apps.forEach(app => {
            if (app.is_menu != 1) return;

            url = app.url;
            const createUrl = (url) => "<?= Yii::$app->getUrlManager()->createUrl('') ?>" + url;
            name = app.name;    
            appCode = app.app_code;
            parentCode = app.parent_code;

            if (parentCode == 0) {
                if (url) {
                    $('.sidebar-menu').append(`
                        <li class="nav-item">
                            <a href="${createUrl(url)}" class="nav-link ${(controllerId == url ? "active" : '')}">
                                <p>${name}</p>
                            </a>
                        </li>
                    `);
                } else {
                    $('.sidebar-menu').append(`
                        <li class="nav-item">
                            <a class="nav-link ${(controllerId == url ? "active" : '')}"">
                                <p>${name}<i class="nav-arrow bi bi-chevron-right"></i></p>
                            </a>
                            <ul id="menu_${appCode}" class="nav nav-treeview sub-1">
                            </ul>
                        </li>
                    `);
                }
            } else {
                if (url) {
                    if ($('ul#menu_' + parentCode).hasClass('sub-1')) {
                        $('ul#menu_' + parentCode).append(`
                            <li class="nav-item">
                                <a href="${createUrl(url)}" class="nav-link ${(controllerId == url ? "active" : '')}">
                                    <p>${name}</p>
                                </a>
                            </li>
                        `);
                    } else {
                        $('ul#menu_' + parentCode).append(`
                            <li class="nav-item">
                                <a class="nav-link ${(controllerId == url ? "active" : '')}"">
                                    <p>${name}<i class="right fas fa-angle-left"></i></p>
                                </a>
                            </li>
                        `);
                    }
                } else {
                    $('ul#menu_' + parentCode).append(`
                        <li class="nav-item">
                            <a class="nav-link ${(controllerId == url ? "active" : '')}">
                                <p>${name}<i class="nav-arrow bi bi-chevron-right"></i></p>
                            </a>
                            <ul id="menu_${appCode}" class="nav nav-treeview sub-1">
                            </ul>
                        </li>
                    `);
                }
            }
        });
    }
</script>
