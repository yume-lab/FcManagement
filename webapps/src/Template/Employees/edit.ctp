<?php $this->assign('title', 'パート編集'); ?>

<div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2><i class="glyphicon glyphicon-user"></i> パート編集</h2>
    </div>
    <div class="box-content">
        <?= $this->Flash->render() ?>
        <?= $this->Form->create($employee) ?>
        <fieldset>
            <legend>店舗情報</legend>
            <?php
                echo $this->Form->input('role_id', ['label' => '役割', 'options' => $roles]);
                echo $this->Form->input('store_id', [
                    'label' => '所属店舗', 'options' => $stores, 'disabled' => 'disabled']);
            ?>
            <legend>従業員情報</legend>
            <?php
                echo $this->Form->input('email', ['label' => 'メールアドレス']);
                echo $this->Form->input('phone_number', ['label' => '電話番号']);
                echo $this->Form->input('zip_code', ['label' => '郵便番号']);
                echo $this->Form->input('address_1', ['label' => '都道府県']);
                echo $this->Form->input('address_2', ['label' => '市区町村']);
                echo $this->Form->input('address_3', ['label' => 'その他']);
                echo $this->Form->input('last_name', ['label' => '苗字']);
                echo $this->Form->input('first_name', ['label' => '名前']);
            ?>
        </fieldset>
        <p class="center col-md-5">
            <?= $this->Html->link(__('一覧に戻る'), $previous, ['class' => 'btn btn-default']) ?>
            <?= $this->Form->button(__('更新する'), ['class' => 'btn btn-primary']) ?>
        </p>
        <?= $this->Form->end() ?>
    </div>
</div>
