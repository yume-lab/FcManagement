<?php // ログアウト確認ダイアログ ?>
<div class="modal fade" id="time-card-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>ログアウト確認</h3>
            </div>
            <div class="modal-body">
                <p>セキュリティのため、一度ログアウトします。</p>
                <p>作業中のデータは失われます。タイムカードを表示しますか？</p>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-default" data-dismiss="modal">キャンセル</a>
                <a href="/time-card" class="btn btn-warning">タイムカード表示</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $(document).on('click', '#show-time-card', function(e) {
            e.preventDefault();
            $('#time-card-modal').modal('show');
        });
    });
</script
