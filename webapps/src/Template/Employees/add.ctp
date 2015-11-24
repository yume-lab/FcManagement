<?php $this->assign('title', '従業員追加'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-user"></i> 従業員情報追加</h2>
            </div>
            <div class="box-content">
                <?= $this->Flash->render() ?>
                <ul class="nav nav-tabs" id="employeeTab">
                    <li class="active">
                        <a href="#required">基本情報</a>
                    </li>
                    <li>
                        <a href="#option">住所・その他</a>
                    </li>
                </ul>

                <?= $this->Form->create($employee) ?>
                <div id="employeeTabContent" class="tab-content">
                    <div class="tab-pane active" id="required">
                        <fieldset>
                            <legend>必須情報</legend>
                            <?php // 必須情報エリア ?>
                            <?php
                                echo $this->Form->input('role_id', ['label' => '役割', 'options' => $roles]);
                                echo $this->Form->input('store_id', ['type' => 'hidden', 'value' => $storeId]);
                                echo $this->Form->input('last_name', ['label' => '苗字']);
                                echo $this->Form->input('first_name', ['label' => '名前']);
                            ?>

                            <legend>時給</legend>
                            <?php // 時給エリア ?>
                            <?php
                                echo $this->Form->input('employee_salary.amount', ['label' => '金額']);
                                echo $this->Form->input('employee_salary.is_deleted', [
                                    'type' => 'hidden', 'value' => 0
                                ]);
                            ?>
                        </fieldset>
                    </div>
                    <div class="tab-pane" id="option">
                        <fieldset>
                            <legend>連絡先</legend>
                            <?php
                                echo $this->Form->input('phone_number', ['label' => '電話番号']);
                                echo $this->Form->input('email', ['label' => 'メールアドレス']);
                            ?>
                            <legend>住所</legend>
                            <?php
                                echo $this->Form->input('zip_code', ['label' => '郵便番号']);
                                echo $this->Form->input('address_1', ['label' => '都道府県']);
                                echo $this->Form->input('address_2', ['label' => '市区町村']);
                                echo $this->Form->input('address_3', ['label' => 'その他']);
                            ?>
                        </fieldset>
                    </div>
                </div>

                <p class="center col-md-5">
                    <?= $this->Html->link(__('一覧に戻る'), $previous, ['class' => 'btn btn-default']) ?>
                    <?= $this->Form->button(__('登録する'), ['class' => 'btn btn-info']) ?>
                </p>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        /**
         * タブ作動
         */
        $('#employeeTab a:first').tab('show');
        $('#employeeTab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
