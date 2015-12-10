<?php // 削除確認ダイアログ ?>
<div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>削除確認</h3>
            </div>
            <div class="modal-body">
                <p>対象データを削除しますか？</p>
            </div>
            <form class="delete-form" method="post" action="">
                <?= $this->Form->input('is_deleted', ['type' => 'hidden', 'value' => 1]) ?>
            </form>
            <div class="modal-footer">
                <a href="#" class="btn btn-default" data-dismiss="modal">キャンセル</a>
                <a href="#" class="btn btn-danger done-delete">削除する</a>
            </div>
        </div>
    </div>
</div>

