<?php $this->assign('title', 'タイムカード'); ?>
<style>
    #time-card-modal #clock {
        text-align: center;
    }
</style>
<div class="row">
    <div class="box center col-md-6">
        <div class="alert alert-info">
            タイムカード
        </div>
        <?= $this->Flash->render() ?>

        <h3 id="clock-large"></h3>

        <div class="box-inner">
            <div class="box-content">
                <div class="box-content">
                    <table class="table responsive">
                        <thead>
                        <tr>
                            <th>氏名</th>
                            <th>最終出勤日</th>
                            <th>状態</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($employees as $employee) :?>
                            <tr>
                                <td>
                                    <a href="#" class="link-name" data-id="<?= $employee->id ?>">
                                        <?= $employee->last_name.' '.$employee->first_name; ?>
                                    </a>
                                </td>
                                <td class="center">2012/01/01</td>
                                <td class="center">
                                    <span class="label-success label label-default">Active</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="store-id" value="<?= $storeId; ?>"/>

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
            <div class="modal-footer">
                <button class="btn btn-lg col-md-4 action-button btn-success" data-alias="/in">
                    <i class="glyphicon glyphicon-arrow-left"></i> 出勤</button>

                <button class="btn btn-lg col-md-4 action-button btn-danger" data-alias="/out">
                    <i class="glyphicon glyphicon-arrow-right"></i> 退勤</button>
                <button class="btn btn-lg col-md-4 action-button btn-primary" data-alias="/break_in">
                    <i class="glyphicon glyphicon-chevron-left"></i> 休憩IN</button>

                <button class="btn btn-lg col-md-4 action-button btn-warning" data-alias="/break_out">
                    <i class="glyphicon glyphicon-chevron-right"></i> 休憩OUT</button>

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
        $('.link-name').on('click', function(e) {
            e.preventDefault();
            $('#employee-name').html($(this).html()+'さん');
            $('#employee-id').val($(this).data('id'));
            $('#time-card-modal').modal('show');
        });

        $('.action-button').on('click', function(e) {
            e.preventDefault();
            showLoading();
            var parameter = {
                employeeId: $('#employee-id').val(),
                storeId: $('#store-id').val(),
                alias: $(this).data('alias'),
                updated: moment().format('YYYY-MM-DD HH:mm:ss')
            };
            console.log(parameter);
            $.ajax({
                type: 'POST',
                url: '/api/time-card/write',
                data: JSON.stringify(parameter),
                dataType: 'json',
                contentType: 'application/json',
            }).always(function(jqXHR, textStatus) {
                console.log(jqXHR, textStatus);
                hideLoading();
                $('#notice').trigger('click');
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
    });
</script>
