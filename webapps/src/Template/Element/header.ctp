<?php
/**
 * ヘッダーエレメント
 */
?>

<div class="navbar navbar-default" role="navigation">
    <div class="navbar-inner">
        <button type="button" class="navbar-toggle pull-left animated flip">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">
            <!-- TODO: ロゴ動的
            <img alt="Charisma Logo" src="img/logo20.png" class="hidden-xs"/>
            -->
            <span>アゲラーシステム</span></a>

        <!-- user dropdown starts -->
        <div class="btn-group pull-right">
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <i class="glyphicon glyphicon-user"></i><span class="hidden-sm hidden-xs"> admin</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">Profile</a></li>
                <li class="divider"></li>
                <li><a href="@routes.Authentication.logout()">ログアウト</a></li>
            </ul>
        </div>
        <!-- user dropdown ends -->

        <div class="btn-group pull-right theme-container tada">
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              <i class="glyphicon glyphicon-tint"></i><span
                class="hidden-sm hidden-xs"> 店舗選択</span>
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" id="themes">
              <li><a data-value="classic" href="#"><i class="whitespace"></i> てすと１</a></li>
              <li><a data-value="classic" href="#"><i class="whitespace"></i> てすと２</a></li>
            </ul>
        </div>

        <ul class="collapse navbar-collapse nav navbar-nav top-menu">
            <li><a href="#"><i class="glyphicon glyphicon-globe"></i> 出退勤入力を表示</a></li>
        </ul>

    </div>
</div>
