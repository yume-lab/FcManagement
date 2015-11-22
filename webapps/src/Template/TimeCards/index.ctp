<?php $this->assign('title', '勤怠一覧'); ?>

<style>
    #employee-list .employee-row {
        cursor: pointer;
    }
    #employee-list .employee-row:hover {
        background-color: #f2dede;
    }
    #employee-list .current {
        background-color: #f2dede;
    }

    #time-table .sunday {
        background-color: #f2dede;
        color: #b94a48;
    }
    #time-table .saturday {
        background-color: #d9edf7;
        color: #3a87ad;
    }
</style>
<div class="row">
    <?php // 従業員エリア ?>
    <div class="col-md-2">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-user"></i> パート一覧</h2>
            </div>
            <div class="box-content">
                <table id="employee-list" class="table responsive">
                    <thead>
                    <tr>
                        <th>氏名</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($employees as $employee) :?>
                        <tr class="employee-row" data-id="<?= $employee->id ?>">
                            <td>
                                <?= $employee->last_name.' '.$employee->first_name; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php // 勤務表エリア ?>
    <div class="col-md-10">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-book"></i> 月別勤務表</h2>
            </div>
            <div id="time-table" class="box-content">
                <p>
                    左メニューから、従業員の方を選択してください。
                </p>
            </div>
        </div>
    </div>
</div>

<?php
$base = '/vendor/calendar';
echo $this->Html->script($base.'/lib/moment.min.js');
?>
<script type="text/javascript">
    $(function() {
        var DATE_FORMAT = 'YYYYMM';

        /**
         * 従業員行クリック時
         */
        $(document).on('click', '.employee-row', function(e) {
            e.preventDefault();
            $('.employee-row').removeClass('current');
            $(this).addClass('current');
            loadEmployees($(this).data('id'), moment().format(DATE_FORMAT));
        });

        /**
         * 次月、前月クリック時
         */
        $(document).on('click', '.pagination a', function(e) {
            var employeeId = $('#employee-list').find('.current').data('id');
            var target = $(this).data('target');
            loadEmployees(employeeId, target);
            return false;
        });

        /**
         * 更新クリック時
         */
        $(document).on('click', '.editable-button a', function(e) {
            var $this = $(this);
            $('.time-label').show();
            $('.time-input').hide();
            $('.editable-button').show();
            $('.editable-actions').hide();

            var $parent = $this.parents('.time-row');
            $parent.find('.time-label').hide();
            $parent.find('.time-input').show();

            $parent.find('.editable-button').hide();
            $parent.find('.editable-actions').show();
            return false;
        });

        /**
         * 編集取消クリック時
         */
        $(document).on('click', '.editable-actions .cancel', function(e) {
            $('.time-label').show();
            $('.time-input').hide();
            $('.editable-button').show();
            $('.editable-actions').hide();
            return false;
        });

        /**
         * 編集の更新クリック時
         */
        $(document).on('click', '.editable-actions .update', function(e) {
            var $this = $(this);
            var $parent = $this.parents('.time-row');
            var ymd = $parent.data('ymd');

            return false;
        });

        /**
         * 勤務表の表示
         */
        function loadEmployees(employeeId, target) {
            showLoading();
            var parameter = {
                employeeId: employeeId,
                target_ym: target
            };
            console.log(parameter);
            $.ajax({
                type: 'GET',
                url: '/api/time-cards/table',
                data: parameter,
                dataType: 'html'
            }).done(function(data, textStatus, jqXHR ) {
                console.log(jqXHR, textStatus);
                $('#time-table').html(data);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }).always(function(jqXHR, textStatus) {
                console.log(jqXHR, textStatus);
                hideLoading();
            });
        }
    });
</script>