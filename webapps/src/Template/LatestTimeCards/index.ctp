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
                                    <a href="#" class="link-name">
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

<?php // TODO: 作りが気に入らない ?>
<?php // 出勤表示ダイアログ ?>
<div class="modal fade" id="time-card-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 id="employee-name"></h4>
            </div>
            <div class="modal-body">
                <h2 id="clock">tests</h2>
            </div>
            <div class="modal-footer">
                <button class="btn btn-lg col-md-4 btn-success">
                    <i class="glyphicon glyphicon-arrow-left"></i> 出勤</button>

                <!--
                <button class="btn btn-lg col-md-4 center-block btn-danger">
                    <i class="glyphicon glyphicon-arrow-right"></i> 退勤</button>
                <button class="btn btn-lg col-md-4 btn-primary">
                    <i class="glyphicon glyphicon-chevron-left"></i> 休憩IN</button>

                <button class="btn btn-lg col-md-4 btn-warning">
                    <i class="glyphicon glyphicon-chevron-right"></i> 休憩OUT</button>

                    -->
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
            $modal.find('#employee-name').html($(this).html()+'さん');
            $modal.modal('show');
        });

        setInterval(function() {
            var now = moment();
            $modal.find('#clock').html(now.format('HH:mm:ss'));
        }, 1000);
    });
</script>
