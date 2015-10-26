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
        <h5>時間</h5>
        <select id="startTime" class="form-control popover-select" data-set-name="data-startTime">
            <?php foreach ($times as $start): ?>
                <option value="<?= $start ?>">
                    <?= h($start) ?>
                </option>
            <?php endforeach; ?>
        </select>
        〜
        <select id="endTime" class="form-control popover-select" data-set-name="data-endTime">
            <?php foreach ($times as $end): ?>
                <option value="<?= $end ?>">
                    <?= h($end) ?>
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
