<?php $this->assign('title', 'パート一覧'); ?>

<style>
    #employee-list .employee-row {
        cursor: pointer;
    }
    #employee-list .employee-row:hover {
        background-color: #f2dede;
    }
</style>
<div class="row">
    <?php // 従業員エリア ?>
    <div class="col-md-2">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-user"></i> パート一覧</h2>
            </div>
            <div class="box-content">
                <table id="employee-list" class="table responsive">
                    <thead>
                    <tr>
                        <th>氏名</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($employees as $employee) :?>
                        <tr class="employee-row"
                            data-id="<?= $employee->id ?>"
                            data-state="<?= empty($state) ? '' : $state['alias']; ?>">
                            <td>
                                <?= $employee->last_name.' '.$employee->first_name; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php // 勤務表エリア ?>
    <div class="col-md-10">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-book"></i> 月別勤務表</h2>
            </div>
            <div id="time-table" class="box-content">
                <p>
                    左メニューから、従業員の方を選択してください。
                </p>
            </div>
        </div>
    </div>
</div>