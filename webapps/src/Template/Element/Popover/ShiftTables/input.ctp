<?php
/**
 * シフト作成のポップアップ.
 * @param $employees 従業員一覧
 */
?>
<div id="popover-header" class="hide">
    <?php // 動的に日付いれる ?>
</div>
<div id="popover-content" class="hide col-md-5">
    <div class="form-group col-md-12 center">
        <h5>従業員</h5>
        <select id="employees" class="form-control popover-select" data-set-name="data-employeeId">
            <?php foreach ($employees as $employee): ?>
                <option value="<?= $employee->id ?>">
                    <?= h($employee->last_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-md-12 center">
        <?php
        // TODO: サーバーで作る.
        $times = [];
        for ($i = 0; $i < 48; $i++) {
            $time = date("H:i", strtotime("+". $i * 30 ." minute",(3600)));
            $times[] = $time;
        }
        ?>

        <?php $start = $times; ?>
        <?php $end = $times; ?>
        <h5>時間</h5>
        <select id="startTime" class="form-control popover-select" data-set-name="data-startTime">
            <?php foreach ($start as $time): ?>
                <option value="<?= $time ?>">
                    <?= h($time) ?>
                </option>
            <?php endforeach; ?>
        </select>
        〜
        <select id="endTime" class="form-control popover-select" data-set-name="data-endTime">
            <?php foreach ($end as $time): ?>
                <option value="<?= $time ?>">
                    <?= h($time) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div id="btn-register" class="form-group col-md-12 center" style="display: none;">
        <a href="#" class="btn btn-primary" id="register">登録</a>
    </div>

    <div id="btn-update" class="form-group col-md-12 center" style="display: none;">
        <a href="#" class="btn btn-danger" id="remove">削除</a>
        <a href="#" class="btn btn-success" id="update">更新</a>
    </div>
</div>
