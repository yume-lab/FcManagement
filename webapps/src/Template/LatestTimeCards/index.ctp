<?php $this->assign('title', 'タイムカード'); ?>

<?= $this->element('Notice/show_top', ['message' => '勤怠打刻が完了しました！']); ?>

<style>
    #time-card-modal #clock {
        text-align: center;
    }
    #clock-large {
        height: 30px;
    }
    #employee-list .employee-row {
        cursor: pointer;
    }
    #employee-list .employee-row:hover {
        background-color: #f2dede;
    }
</style>
<div class="row">
    <div class="box center col-md-6">
        <div class="alert alert-info">
            タイムカード
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
                <a href="/" class="btn btn-info">TOPへ</a>
            </div>
        </div>
    </div>
</div>

<?php
$base = '/vendor/calendar';
echo $this->Html->script($base.'/lib/moment.min.js');
?>
<script type="text/javascript">
    $(function () {
        var $modal = $('#time-card-modal');
        var buttonSelector = '.action-button';
        /**
         * 従業員行クリック時
         */
        $(document).on('click', '.employee-row', function(e) {
            e.preventDefault();
            $this = $(this);
            $('#employee-name').html($this.find('.name').html()+'さん');
            $('#employee-id').val($this.data('id'));

            switchActionButton($this.data('state'));
            $modal.modal('show');
        });

        /**
         * ボタン押下時
         */
        $(document).on('click', buttonSelector, function(e) {
            e.preventDefault();
            showLoading();
            var parameter = {
                token: $('#token').val(),
                employeeId: $('#employee-id').val(),
                alias: $(this).data('alias'),
                time: moment().format('YYYY-MM-DD HH:mm:ss')
            };
            console.log(parameter);
            $.ajax({
                type: 'POST',
                url: '/api/time-card/write',
                data: JSON.stringify(parameter),
                dataType: 'json',
                contentType: 'application/json',
            }).done(function( data, textStatus, jqXHR) {
                console.log(data, jqXHR, textStatus);
                switchActionButton(parameter.alias);
                loadTable();
                $('#notice').trigger('click');
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
                showErrorDialog();
            }).always(function(jqXHR, textStatus) {
                console.log(jqXHR, textStatus);
                hideLoading();
            });

        });

        /**
         * デジタル時計
         */
        setInterval(function() {
            var now = moment();
            $modal.find('#clock').html(now.format('HH:mm:ss'));
            $('#clock-large').html(now.format('YYYY年MM月DD日 HH:mm:ss'));
        }, 1000);

        /**
         * 従業員一覧部分を表示します.
         */
        function loadTable() {
            showLoading();
            var parameter = {
                token: $('#token').val()
            };
            console.log(parameter);
            $.ajax({
                type: 'GET',
                url: '/api/time-card/table',
                data: parameter,
                dataType: 'html'
            }).done(function(data, textStatus, jqXHR ) {
                console.log(jqXHR, textStatus);
                $('#table-area').html(data);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
                showErrorDialog();
            }).always(function(jqXHR, textStatus) {
                console.log(jqXHR, textStatus);
                hideLoading();
            });
        }

        /**
         * ボタンの表示切り替えを行います.
         */
        function switchActionButton(alias) {
            var showAlias = getShowButton(alias);
            $(buttonSelector).hide();
            $(buttonSelector).each(function() {
                if (0 <= $.inArray($(this).data('alias'), showAlias)) {
                    $(this).css('display', 'inline-block');
                }
            });
        }

        /**
         * 不正ログイン時のダイアログを表示します.
         */
        function showErrorDialog() {
            // 閉じられないようにする.
            $('#fail-request-modal').modal({backdrop: 'static', keyboard: false});
        }

        /**
         * 表示するボタンを返します.
         * @param alias
         * @return array
         */
        function getShowButton(alias) {
            var matrix = {
                'default': [
                    '/in'
                ],
                '/in': [
                    '/out',
                    '/break_in'
                ],
                '/out': [
                    '/in'
                ],
                '/break_in': [
                    '/break_out'
                ],
                '/break_out': [
                    '/out'
                ]
            };

            alias = alias || 'default';
            return matrix[alias];
        }
        loadTable();
    });
</script>
