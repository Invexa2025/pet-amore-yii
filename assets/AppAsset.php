<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.min.css',
        'css/bootstrap-icon.min.css',
        'css/bootstrap-select.min.css',
        'css/bootstrap-table.min.css',
        'css/bootstrap-datetimepicker.min.css',
        'css/adminlte/adminlte.min.css',
        'css/sweetalert2.min.css',
        'css/custom.css',
    ];
    public $js = [
        'js/jquery.min.js',
        'js/popper.min.js',
        'js/moment.min.js',
        'js/bootstrap.min.js',
        'js/adminlte/adminlte.min.js',
        'js/parsley.min.js',
        'js/bootstrap-table.min.js',
        'js/bootstrap-datetimepicker.min.js',
        'js/bootstrap-modal-wrapper-factory.min.js',
        'js/bootstrap-table-wrapper.js',
        'js/bootstrap-select.min.js',
        'js/loadingoverlay.min.js',
        'js/sweetalert2.min.js',
        'js/jquery-ui.min.js',
        'js/network.js',
        'js/custom.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\bootstrap5\BootstrapAsset'
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
}
