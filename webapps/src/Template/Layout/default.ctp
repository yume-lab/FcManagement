<?php
/**
 * アゲラー管理システムの共通テンプレート
 */

?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= CHARISMA_ROOT ?>/img/favicon.ico">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->element('Charisma/header') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>

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
                    <div class="row">
                        <div class="box col-md-12">
                            <?= $this->fetch('content') ?>
                        </div>
                    </div>
                </div>

                <div id="footer" class="col-lg-10 col-sm-10">
                    <?= $this->element('footer') ?>
                </div>
            </div>
        </div>
    </div>

    <?= $this->element('Charisma/footer') ?>
</body>
</html>
