<?php $this->assign('title', 'マイアカウント'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-user"></i> マイアカウント編集</h2>
            </div>
            <div class="box-content">
                <?= $this->Flash->render() ?>
                <?= $this->Form->create($user) ?>
                <fieldset>
                    <?php
                    echo $this->Form->input('email', ['label' => 'メールアドレス']);
                    echo $this->Form->input('password', ['label' => 'パスワード']);
                    echo $this->Form->input('last_name', ['label' => '苗字']);
                    echo $this->Form->input('first_name', ['label' => '名前']);
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
