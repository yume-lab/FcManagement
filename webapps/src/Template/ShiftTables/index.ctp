<?php $this->assign('title', 'シフト作成'); ?>

<?php // TODO: 暫定でここに ?>
<script src="http://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>

<?php // TODO: 暫定でここに ?>
<script type="text/javascript">
    var $document = $(document);

    $document.ready(function() {
        var calendarSelector = '#shift-calendar';

        $document.on('change', '.popover-select', function() {
            var setId = $(this).data('set-id');
            $(setId).val($(this).val());
        });

        $document.on('click', '#save', function() {
            var events = $(calendarSelector).fullCalendar('clientEvents');
            var current = $(calendarSelector).fullCalendar('getDate');
            var parameter = {
                year: current.year(),
                month: current.month() + 1,
                shift: events
            };
            $.ajax({
                type: 'POST',
                url: '/api/shift/update',
                data: JSON.stringify(parameter),
                dataType: 'json',
                contentType: 'application/json',
                success: function(res, status) {
                    console.log(res);
                    console.log(status);
                },
                error: function(res, status) {
                    console.log(res);
                    console.log(status);
                }
            });
        });

        /**
         * シフト登録処理
         */
        $document.on('click', '#register', function() {
            var date = moment($('#data-day').val()).format('YYYY-MM-DD');
            var getTime = function(selector) {
                return moment(date + $(selector).val(), 'YYYY-MM-DDHH:mm');
            }

            var employeeId = $('#data-employeeId').val();
            var $employee = $('#employees').find('option').filter(function(row) {
                return employeeId === $(this).val();
            });
            var startTime = getTime('#data-startTime');
            var endTime = getTime('#data-endTime');

            var event = {
                title: $.trim($employee.html()),
                employeeId: $employee.val(),
                start: startTime,
                end: endTime
            };
            console.log(date);
            console.log(event);
            $(calendarSelector).fullCalendar('renderEvent', event);
            return false;
        });

        $(calendarSelector).fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            lang: 'ja',
            // 時間の書式
            timeFormat: 'H(:mm)',
            editable: true,
            timezone: 'Asia/Tokyo',
            eventLimit: true,
            displayEventEnd: true,
            dayClick: function(date, jsEvent, view) {
                // 日付クリックイベント. クリックされた日の拡大表示をする.
//                var $calendar = $(calendarSelector);
//                $calendar.fullCalendar('gotoDate', date);
//                $calendar.fullCalendar('changeView', 'agendaDay');
                // TODO: シフトなければポップアップ、あれば日付詳細
//                console.log(date);
//                console.log(jsEvent);
//                console.log(view);


                $('#data-day').val(date);
                $(this).popover({
                    html: true,
                    placement: function (context, source) {
                        var position = $(source).position();
                        return (position.left > 500) ? 'left' : 'right';
                    },
                    container: 'body',
                    title: function() {
                        return $('#popover-header').html(date.format('YYYY/MM/DD')).html();
                    },
                    content: function() {
                        return $('#popover-content').html();
                    }
                });
                $('.popover-select').change();
            },
            eventClick: function(calEvent, jsEvent, view) {
                // TODO
                // イベントクリックイベント. そのイベントの詳細をPopupで表示する.
                console.log(calEvent);
                console.log(jsEvent);
                console.log(view);
                console.log(calEvent.start)
            },
            viewRender: function(view, element) {
                if (view.name === 'month') {
                    var current = $(calendarSelector).fullCalendar('getDate');
                    var parameter = {
                        year: current.year(),
                        month: current.month() + 1
                    };
                    $(calendarSelector).fullCalendar({
                        events: '/api/shift'
                    });
                    console.log(parameter);
                }
            },
            drop: function(date, allDay) {
                // 従業員ドロップ時のイベント.
                var DEFAULT_HOUR_RANGE = 2;

                var source = $(this).data('event');
                var newEvent = $.extend({}, source);

                var start = date;
                var end = moment(start).add('hours', DEFAULT_HOUR_RANGE).format();

                newEvent.start = start;
                newEvent.end = end;
                newEvent.allDay = false;

                $(calendarSelector).fullCalendar('renderEvent', newEvent);
            },
            events: '/api/shift'
        });
    });

</script>

<div class="row">
    <div class="col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-calendar"></i> シフト表</h2>
            </div>

            <div class="box-content">
                <div class="box col-md-12">
                    <?= $this->Flash->render() ?>
                    <a id="save" class="btn btn-info" href="#">
                        <i class="glyphicon glyphicon-plus icon-white"></i>
                        保存する
                    </a>
                </div>

                <div id="shift-calendar"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-calendar"></i> シフト表プレビュー</h2>
            </div>

            <div class="box-content">

            </div>
        </div>
    </div>
</div>

<div id="popover-header" class="hide">
    <?php // 動的に日付いれる ?>
</div>
<div id="popover-content" class="hide col-md-5">
    <?= $this->Form->input('day', ['type' => 'hidden', 'id' => 'data-day']) ?>
    <?= $this->Form->input('employeeId', ['type' => 'hidden', 'id' => 'data-employeeId']) ?>
    <?= $this->Form->input('startTime', ['type' => 'hidden', 'id' => 'data-startTime']) ?>
    <?= $this->Form->input('endTime', ['type' => 'hidden', 'id' => 'data-endTime']) ?>

    <div class="form-group col-md-12 center">
        <h5>従業員</h5>
        <select id="employees" class="form-control popover-select" data-set-id="#data-employeeId">
            <?php foreach ($employees as $employee): ?>
                <option value="<?= $employee->id ?>">
                    <?= h($employee->last_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-md-12 center">
        <?php $start = ['9:00', '9:30', '10:00', '10:30']; ?>
        <?php $end = ['9:00', '9:30', '10:00', '10:30']; ?>
        <h5>時間</h5>
        <select id="startTime" class="form-control popover-select" data-set-id="#data-startTime">
            <?php foreach ($start as $time): ?>
                <option value="<?= $time ?>">
                    <?= h($time) ?>
                </option>
            <?php endforeach; ?>
        </select>
        〜
        <select id="endTime" class="form-control popover-select" data-set-id="#data-endTime">
            <?php foreach ($end as $time): ?>
                <option value="<?= $time ?>">
                    <?= h($time) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-md-12 center">
        <a href="#" class="btn btn-primary" id="register">登録</a>
    </div>
</div>
