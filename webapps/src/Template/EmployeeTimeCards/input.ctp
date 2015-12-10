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

<?= $this->element('Dialog/EmployeeTimeCards/actions', ['states' => $states]); ?>
<?= $this->element('Dialog/EmployeeTimeCards/fail_token'); ?>

<?= $this->Html->script('moment.min.js'); ?>
<?= $this->Html->script('EmployeeTimeCards/input.js'); ?>

