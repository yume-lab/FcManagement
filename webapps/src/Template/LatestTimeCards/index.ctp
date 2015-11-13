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
        <?= $this->Flash->render() ?>

        <h3 id="clock-large"></h3>

        <div class="box-inner">
            <div class="box-content">
                <div class="box-content">
                    <table id="employee-list" class="table responsive">
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
                            <tr class="employee-row"
                                data-id="<?= $employee->id ?>"
                                data-state="<?= empty($state) ? '' : $state['alias']; ?>">
                                <td>
                                    <h4 class="name">
                                        <?= $employee->last_name.' '.$employee->first_name; ?>
                                    </h4>
                                </td>
                                <td class="center">
                                    <h4>
                                        <?= $hasData ? date('Y/m/d', strtotime($info['time'])) : ''; ?>
                                    </h4>
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
                         <?= trim($state['name']); ?>
                    </button>
                <?php endforeach; ?>
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
         * 名前リンク押下時
         */
        $('.employee-row').on('click', function(e) {
            e.preventDefault();
            $this = $(this);
            $('#employee-name').html($this.find('.name').html()+'さん');
            $('#employee-id').val($this.data('id'));

            switchActionButton($this.data('state'));

            $('#time-card-modal').modal('show');
        });

        /**
         * ボタン押下時
         */
        $(buttonSelector).on('click', function(e) {
            e.preventDefault();
            showLoading();
            var parameter = {
                employeeId: $('#employee-id').val(),
                storeId: $('#store-id').val(),
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
            }).always(function(jqXHR, textStatus) {
                console.log(jqXHR, textStatus);
                switchActionButton(parameter.alias);
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
    });
</script>
