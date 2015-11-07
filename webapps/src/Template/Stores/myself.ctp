<?php $this->assign('title', '店舗編集'); ?>
<style>
    .form-control {
        display: inline;
        width: 20%;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-home"></i> 店舗編集</h2>
            </div>
            <div class="box-content">
                <?= $this->Flash->render() ?>
                <?= $this->Form->create($store) ?>
                <fieldset>
                    <legend><?= $store->name ?>店</legend>
                    <h4>営業時間</h4>
                    <?php
                    echo $this->Form->input('opened', ['label' => '開始', 'interval' => $interval]);
                    echo $this->Form->input('closed', ['label' => '終了', 'interval' => $interval]);
                    ?>
                </fieldset>
                <p class="center col-md-5">
                    <?= $this->Form->button(__('更新する'), ['class' => 'btn btn-primary']) ?>
                </p>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
