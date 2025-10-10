<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'ACIS';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('favicon.ico','img/deped_logo.ico',array('type' => 'icon')); ?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <?= $this->Html->css([
        '/plugins/fontawesome-free/css/all.min',
        '/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min',
        '/plugins/select2/css/select2.min',
        '/plugins/select2-bootstrap4-theme/select2-bootstrap4.min',
        '/plugins/datatables-bs4/css/dataTables.bootstrap4.min',
        '/plugins/datatables-buttons/css/buttons.bootstrap4.min',
        '/plugins/datatables-responsive/css/responsive.bootstrap4.min',
        '/plugins/sweetalert2/sweetalert2.min',
        '/plugins/toastr/toastr.min',
        '/dist/css/adminlte.min',
        'style'
    ]) ?>

    <?= $this->Html->script([
        '/plugins/jquery/jquery.min',
        'app'
    ]);
    ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body class="sidebar-mini layout-fixed layout-footer-fixed layout-navbar-fixed" style="height: auto;" cz-shortcut-listen="true">
<?= $this->element('navbar') ?>
<?= $this->element('sidebar') ?>
<div class="content-wrapper">
    <?= $this->element('content-header') ?>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?= $this->fetch('content') ?>
            </div>
        </div>
    </section>
</div>
<?= $this->element('footer')?>
<?= $this->element('control-sidebar')?>

<?= $this->Html->script([
    '/plugins/bootstrap/js/bootstrap.bundle.min',
    '/plugins/sweetalert2/sweetalert2.min',
    '/plugins/select2/js/select2.full.min',
    '/plugins/toastr/toastr.min',
    '/plugins/moment/moment.min',
    '/plugins/inputmask/jquery.inputmask.min',
    '/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min',
    '/plugins/datatables/jquery.dataTables.min',
    '/plugins/datatables-bs4/js/dataTables.bootstrap4.min',
    '/plugins/datatables-responsive/js/dataTables.responsive.min',
    '/plugins/datatables-responsive/js/responsive.bootstrap4.min',
    '/plugins/datatables-buttons/js/dataTables.buttons.min',
    '/plugins/datatables-buttons/js/buttons.bootstrap4.min',
    '/dist/js/adminlte.min'
]);
?>
<script>
    loadjs(JS_URL + 'master.js');
    loadjs(JS_URL + '<?= $this->fetch('title') ?>' + '.js');
</script>
</body>
</html>
