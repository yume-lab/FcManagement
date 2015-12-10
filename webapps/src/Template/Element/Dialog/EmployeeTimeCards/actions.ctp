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

