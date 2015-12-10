<?php $this->assign('title', 'タイムカード'); ?>

<?= $this->element('Notice/show_top', ['message' => '勤怠打刻が完了しました！']); ?>

<?= $this->Html->css('EmployeeTimeCards/input.css'); ?>

<div class="row">
    <div class="box center col-md-6">
        <div style="text-align: right;">
            <a href="/">管理画面に戻る</a>
        </div>

        <div class="alert alert-info">
            タイムカード<br/>
            ご自身のお名前を探して、クリックしてください。
        </div>

        <h3 id="clock-large"></h3>

        <div class="box-inner">
            <div class="box-content">
                <div id="table-area" class="box-content">
                    <?php // ajaxでロードされます. ?>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="token" value="<?= $token; ?>"/>

<?php // TODO: 作りが気に入らない ?>
<?php // 出勤表示ダイアログ ?>
<div class="modal fade" id="time-card-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 id="employee-name"></h4>
                <input type="hidden" id="employee-id" value=""/>
            </div>
            <div class="modal-body">
                <h2 id="clock"></h2>
                <div id="break-input-area" class="box center-block" style="display: none;">
                    休憩時間：
                    <input id="break-time" type="text" name="rested_minutes"
                           value="15" class="form-control" style="width: 50%; display: inline-block;">
                </div>
            </div>
            <div class="modal-footer center">
                <?php foreach ($states as $state): ?>
                    <?= $this->TimeCard->button($state); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php // トークン不正のダイアログ ?>
<div class="modal fade" id="fail-request-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>不正なアクセスです。</h4>
            </div>
            <div class="modal-body">
                ログインをやり直してください。
            </div>
            <div class="modal-footer center">
                <a href="/" class="btn btn-warning">TOPへ</a>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script('moment.min.js'); ?>
<?= $this->Html->script('EmployeeTimeCards/input.js'); ?>

