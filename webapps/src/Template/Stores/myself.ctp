<?php $this->assign('title', '店舗編集'); ?>
<?php
$rested_times = $store->store_setting->rested_times;
$rested_times = empty($rested_times) ? [] : $rested_times;
$rested_times = json_decode($rested_times, true);
?>
<style>
    .form-control {
        display: inline;
        width: 20%;
    }
    .address-area {
        display: table;
        width: 100%;
    }
    .address-area .item {
        display: table-cell;
        padding-right: 0.5em;
    }
    .address-area .item input {
        width: 100%;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-home"></i> <?= h($store->name) ?>店舗編集</h2>
            </div>
            <div class="box-content">
                <?= $this->Flash->render() ?>
                <ul class="nav nav-tabs" id="storeTab">
                    <li class="active">
                        <a href="#required">基本情報</a>
                    </li>
                    <li>
                        <a href="#salary">給与関連</a>
                    </li>
                    <li>
                        <a href="#other">その他</a>
                    </li>
                </ul>

                <?= $this->Form->create($store) ?>
                <div id="storeTabContent" class="tab-content">
                    <div class="tab-pane active" id="required">
                        <fieldset>
                            <legend>必須情報</legend>
                            <?php
                                echo $this->Form->input('name',
                                    ['label' => '店舗名', 'placeholder' => '例:駅前店']);
                                echo $this->Form->input('phone_number',
                                    ['label' => '電話番号', 'placeholder' => '例:09012345678']);
                            ?>
                            <legend>営業時間</legend>
                            <?php
                                echo $this->Form->input('opened',
                                    ['label' => '開始', 'interval' => $interval]);
                                echo $this->Form->input('closed',
                                    ['label' => '終了', 'interval' => $interval]);
                            ?>
                            <legend>所在地</legend>
                            <?php
                                echo $this->Form->input('zip_code',
                                    ['label' => '郵便番号', 'placeholder' => '例:1234567']);
                            ?>
                            <div class="address-area">
                                <div class="item">
                                    <?php echo $this->Form->input('address_1',
                                        ['label' => '都道府県', 'placeholder' => '例:埼玉県']); ?>
                                </div>
                                <div class="item">
                                    <?php echo $this->Form->input('address_2',
                                        ['label' => '市区町村', 'placeholder' => '例:川越市']); ?>
                                </div>
                                <div class="item">
                                    <?php echo $this->Form->input('address_3',
                                        ['label' => 'その他', 'placeholder' => '例:城下町10-1']); ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane" id="salary">
                        <fieldset>
                            <legend>時給</legend>
                            <?php echo $this->Form->input('store_setting.default_hour_pay',
                                ['label' => '基本時給']); ?>
                            <legend>研修設定</legend>
                            <?php
                                echo $this->Form->input('store_setting.training_hour_pay',
                                    ['label' => '時給']);
                                echo $this->Form->input('store_setting.training_hour',
                                    ['label' => '期間(時間入力)']);
                            ?>
                        </fieldset>
                    </div>
                    <div class="tab-pane" id="other">
                        <fieldset>
                            <legend>交通費</legend>
                            <?php
                                echo $this->Form->input('store_setting.default_fare',
                                    ['label' => '支給金額（1勤務あたり）']); ?>
                            <legend>休憩設定</legend>
                            <div id="break-settings">
                                <?php foreach ($rested_times as $info): ?>
                                    <div class="rested-time-row">
                                        総労働
                                        <input class="form-control" type="number" name="worked"
                                               value="<?= ($info['worked'] / 60) ?>">
                                        時間で、
                                        <input class="form-control" type="number" name="rested"
                                               value="<?= $info['rested'] ?>">
                                        分の休憩

                                        <a class="btn btn-danger btn-sm delete-row" href="#">
                                            <i class="glyphicon glyphicon-trash icon-white"></i>
                                        </a>
                                    </div>
                                <?php endforeach; ?>

                                <div id="base" style="display: none;">
                                    <div class="rested-time-row">
                                        総労働
                                        <input class="form-control" type="number" name="worked" value="0">
                                        時間で、
                                        <input class="form-control" type="number" name="rested" value="0">
                                        分の休憩

                                        <a class="btn btn-danger btn-sm delete-row" href="#">
                                            <i class="glyphicon glyphicon-trash icon-white"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <a id="add-rested-row" class="btn btn-warning btn-sm" href="#">
                                <i class="glyphicon glyphicon-plus icon-white"></i>
                            </a>
                            <?php
                                echo $this->Form->input('store_setting.rested_times',
                                    ['type' => 'hidden', 'id' => 'rested_times']); ?>
                        </fieldset>
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
        $('#storeTab a:first').tab('show');
        $('#storeTab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        /**
         * サブミット時
         */
        $('form').submit(function() {
            var restedTimeValues = [];
            $('#break-settings').children('.rested-time-row').each(function() {
                var worked = $(this).find('[name="worked"]').val();
                var rested = $(this).find('[name="rested"]').val();
                // 時間は分に直す
                restedTimeValues.push({
                    worked: worked * 60,
                    rested: rested
                });
            });
            $('#rested_times').val(JSON.stringify(restedTimeValues));
            return true;
        });

        var addRestedTimeRow = function() {
            $('#break-settings').append($('#base').html());
        }

        /**
         * 休憩設定の追加
         */
        $(document).on('click', '#add-rested-row', function() {
            addRestedTimeRow();
            return false;
        });
        /**
         * 休憩設定の1行削除
         */
        $(document).on('click', '.delete-row', function() {
            $(this).closest('.rested-time-row').remove();
            return false;
        });
    });
</script>
