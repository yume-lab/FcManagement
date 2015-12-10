<?php $this->assign('title', '勤怠一覧'); ?>

<?= $this->element('Notice/show_top', ['message' => '勤怠データを更新しました。']); ?>

<?= $this->Html->css('EmployeeTimeCards/index.css'); ?>
<?= $this->Html->css('EmployeeTimeCards/table.css'); ?>

<div class="row">
    <?php // 従業員エリア ?>
    <div class="col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-user"></i> 従業員一覧</h2>
            </div>
            <div class="box-content">
                <select id="employee-list" class="form-control">
                    <option value=""></option>
                    <?php foreach ($employees as $employee) :?>
                        <option value="<?= $employee->id ?>">
                            <?= trim($employee->last_name).' '.trim($employee->first_name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <?php // 勤務表エリア ?>
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-book"></i> 月別勤務表</h2>
            </div>
            <div id="time-table" class="box-content">
                <p>
                    従業員の方を選択してください。
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script('moment.min.js'); ?>
<?= $this->Html->script('EmployeeTimeCards/index.js'); ?>

