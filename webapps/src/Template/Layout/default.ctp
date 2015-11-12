<?php
/**
 * アゲラー管理システムの共通テンプレート
 */

?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= CHARISMA_ROOT ?>/img/favicon.ico">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->element('Charisma/header') ?>
    <?= $this->Html->css('styles.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <div id="loading"></div>

    <div id="header">
        <?= $this->element('header') ?>
    </div>

    <div id="contents">
        <div class="ch-container">
            <div class="row">
                <div id="sidebar" class="col-sm-2 col-lg-2">
                    <?= $this->element('sidebar') ?>
                </div>

                <div id="content" class="col-lg-10 col-sm-10">
                    <?= $this->fetch('content') ?>
                </div>
            </div>
            <hr>

            <?= $this->element('footer') ?>
        </div>
    </div>

    <?= $this->element('Charisma/footer') ?>
    <?= $this->element('Script/Common/util') ?>
</body>
</html>
