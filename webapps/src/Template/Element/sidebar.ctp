<?php
/**
 * サイドバーエレメント
 */
?>

<div class="sidebar-nav">
    <div class="nav-canvas">
        <div class="nav-sm nav nav-stacked">

        </div>
        <ul class="nav nav-pills nav-stacked main-menu">
            <li class="nav-header">管理</li>
            <li>
                <a class="ajax-link" href="/employees">
                    <i class="glyphicon glyphicon-user"></i>
                    <span> パート管理</span>
                </a>
            </li>
            <li>
                <a class="ajax-link" href="/time-cards">
                    <i class="glyphicon glyphicon-book"></i>
                    <span> 勤怠一覧</span>
                </a>
            </li>
            <li class="nav-header">シフト</li>
            <li>
                <a class="ajax-link" href="/shift">
                    <i class="glyphicon glyphicon-list-alt"></i>
                    <span> シフト作成</span>
                </a>
            </li>
            <li>
                <a class="ajax-link" href="/fixed">
                    <i class="glyphicon glyphicon-file"></i>
                    <span> 確定シフト一覧</span>
                </a>
            </li>
            <li class="nav-header">設定</li>
            <li>
                <a class="ajax-link" href="/stores/myself">
                    <i class="glyphicon glyphicon-home"></i>
                    <span> 店舗編集</span>
                </a>
            </li>

        </ul>
    </div>
</div>