<?php $this->assign('title', 'タイムカード'); ?>

<?php
    // TODO: ヘルパーで
    function getClass($alias) {
        $class = 'default';
        switch ($alias) {
            case '/in':
                $class = 'success';
                break;
            case '/out':
                $class = 'danger';
                break;
            case '/break_in':
                $class = 'primary';
                break;
            case '/break_out':
                $class = 'warning';
                break;
        }
        return $class;
    }

    // TODO: ヘルパーで
    function getIcon($alias) {
        $icon = '';
        switch ($alias) {
            case '/in':
                $icon = 'glyphicon-arrow-left';
                break;
            case '/out':
                $icon = 'glyphicon-arrow-right';
                break;
            case '/break_in':
                $icon = 'glyphicon-chevron-left';
                break;
            case '/break_out':
                $icon = 'glyphicon-chevron-right';
                break;
        }
        return $icon;
    }
?>

<style>
    #time-card-modal #clock {
        text-align: center;
    }
    #clock-large {
        height: 30px;
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
                            <?php
                                $info = isset($data[$employee->id]) ? $data[$employee->id] : [];
                                $hasData = !empty($info);
                                $state = $hasData ? $states[$info['time_card_state_id']] : [];
                            ?>
                            <tr>
                                <td>
                                    <a href="#" class="link-name"
                                       data-id="<?= $employee->id ?>"
                                       data-state="<?= empty($state) ? '' : $state['alias']; ?>">
                                        <?= $employee->last_name.' '.$employee->first_name; ?>
                                    </a>
                                </td>
                                <td class="center">
                                    <?= $hasData ? date('Y/m/d', strtotime($info['modified'])) : ''; ?>
                                </td>
                                <td class="center">
                                    <?php // TODO このあたり全体はヘルパーにする ?>
                                    <?php if (!empty($state)): ?>
                                        <h4>
                                            <span class="label label-<?= getClass($state['alias']); ?>">
                                                <?= trim($state['label']); ?>
                                            </span>
                                        </h4>
                                    <?php else: ?>
                                        <h4>
                                            <span class="label label-default">
                                                未出勤
                                            </span>
                                        </h4>
                                    <?php endif; ?>
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
            <div class="modal-footer center">
                <?php foreach ($states as $state): ?>
                    <button class="btn btn-lg col-md-4 center-block
                                action-button btn-<?= getClass($state['alias']); ?>"
                            data-alias="<?= $state['alias'] ?>">
                        <i class="glyphicon <?= getIcon($state['alias']); ?>"></i>
                         <?= $state['name'] ?>
                    </button>
                <?php endforeach; ?>

                <!--
                <button class="btn btn-lg col-md-4 action-button btn-success" data-alias="/in">
                    <i class="glyphicon glyphicon-arrow-left"></i> 出勤</button>

                <button class="btn btn-lg col-md-4 action-button btn-danger" data-alias="/out">
                    <i class="glyphicon glyphicon-arrow-right"></i> 退勤</button>
                <button class="btn btn-lg col-md-4 action-button btn-primary" data-alias="/break_in">
                    <i class="glyphicon glyphicon-chevron-left"></i> 休憩IN</button>

                <button class="btn btn-lg col-md-4 action-button btn-warning" data-alias="/break_out">
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
        /**
         * 名前リンク押下時
         */
        $('.link-name').on('click', function(e) {
            e.preventDefault();
            $this = $(this);
            $('#employee-name').html($this.html()+'さん');
            $('#employee-id').val($this.data('id'));

            var showAlias = getShowButton($this.data('state'));
            $('.action-button').hide();
            $('.action-button').each(function() {
                if ($.inArray($(this).data('alias'), showAlias) >= 0) {
                    $(this).show();
                }
            });

            $('#time-card-modal').modal('show');
        });

        /**
         * ボタン押下時
         */
        $('.action-button').on('click', function(e) {
            e.preventDefault();
            showLoading();
            var parameter = {
                employeeId: $('#employee-id').val(),
                storeId: $('#store-id').val(),
                alias: $(this).data('alias')
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

        /**
         * 表示するボタンを返します.
         * @param alias
         * @return array
         */
        function getShowButton(alias) {
            var result = [];
            if (!alias) {
                result.push('/in');
            } else if (alias === '/in') {
                result.push('/out');
                result.push('/break_in');
            } else if (alias === '/out') {
                result.push('/in');
            } else if (alias === '/break_in') {
                result.push('/break_out');
            } else if (alias === '/break_out') {
                result.push('/out');
            }
            return result;
        }
    });
</script>
