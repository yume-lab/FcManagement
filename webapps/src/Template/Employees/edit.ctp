<?php $this->assign('title', 'パート編集'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-user"></i> パート編集</h2>
            </div>
            <div class="box-content">
                <?= $this->Flash->render() ?>
                <ul class="nav nav-tabs" id="employeeTab">
                    <li class="active">
                        <a href="#required">必須情報</a>
                    </li>
                    <li>
                        <a href="#option">住所・その他</a>
                    </li>
                    <li>
                        <a href="#hourly_pay">時給編集</a>
                    </li>
                </ul>

                <?= $this->Form->create($employee) ?>
                <div id="employeeTabContent" class="tab-content">
                    <div class="tab-pane active" id="required">
                        <?php // 必須情報エリア ?>
                        <?php
                            echo $this->Form->input('role_id', ['label' => '役割', 'options' => $roles]);
                            echo $this->Form->input('store_id', [
                                'label' => '所属店舗', 'options' => $stores, 'disabled' => 'disabled']);
                        ?>
                        <?php
                            echo $this->Form->input('last_name', ['label' => '苗字']);
                            echo $this->Form->input('first_name', ['label' => '名前']);
                        ?>
                    </div>
                    <div class="tab-pane" id="option">
                        <?php // その他情報エリア ?>
                        <?php
                            echo $this->Form->input('phone_number', ['label' => '電話番号']);
                            echo $this->Form->input('email', ['label' => 'メールアドレス']);
                            echo $this->Form->input('zip_code', ['label' => '郵便番号']);
                            echo $this->Form->input('address_1', ['label' => '都道府県']);
                            echo $this->Form->input('address_2', ['label' => '市区町村']);
                            echo $this->Form->input('address_3', ['label' => 'その他']);
                        ?>
                    </div>
                    <div class="tab-pane" id="hourly_pay">
                        <?php // TODO: 時給編集エリア ?>
                        <p>作成中..</p>
                    </div>
                </div>

                <p class="center col-md-5">
                    <?= $this->Form->button(__('更新する'), ['class' => 'btn btn-primary']) ?>
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
