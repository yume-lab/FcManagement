<?php $this->assign('title', 'シフト作成'); ?>

<?php // TODO: 暫定でここに ?>
<script src="http://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>

<?php // TODO: 暫定でここに ?>
<script type="text/javascript">

    $(document).ready(function() {
        var calendarSelector = '#shift-calendar';

        $('#save').on('click', function() {
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

        $(document).on('click', '#register', function() {
            var date = moment($('#day').val()).format('YYYY-MM-DD');
            var $employee = $('#sel_employee option:selected');
            var event = {
                title: $.trim($employee.text()),
                id: $('#sel_employee').val(),
                start: moment(date + ' ' + $('#start option:selected').val()),
                end: moment(date + ' ' + $('#end option:selected').val()),
            };
            console.log(event);
            $(calendarSelector).fullCalendar('renderEvent', event);
            return false;
        });

        $('#employee-table .fc-event').each(function() {
            // ドラッグされた従業員を一時保存
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                backgroundColor: $(this).css('background-color'),
                id: $(this).data('employee-id')
            });

            $(this).draggable({
                zIndex: 999,
                revert: true,
                revertDuration: 0
            });
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
            droppable: true,
            timezone: 'Asia/Tokyo',
            eventLimit: true,
            displayEventEnd: true,
            dayClick: function(date, jsEvent, view) {
                // 日付クリックイベント. クリックされた日の拡大表示をする.
//                var $calendar = $(calendarSelector);
//                $calendar.fullCalendar('gotoDate', date);
//                $calendar.fullCalendar('changeView', 'agendaDay');
                // TODO: シフトなければポップアップ、あれば日付詳細
                console.log(date);
                console.log(jsEvent);
                console.log(view);

                $('#day').val(date);
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
    <?= $this->Form->input('day', ['type' => 'hidden', 'id' => 'day']) ?>

    <div class="form-group col-md-12 center">
        <h5 for="employee-id">従業員</h5>
        <select id="sel_employee" class="form-control">
            <?php foreach ($employees as $employee): ?>
                <option value="<?= $employee->id ?>">
                    <?= h($employee->last_name . ' ' . $employee->first_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-md-12 center">
        <?php $start = ['9:00', '9:30', '10:00', '10:30']; ?>
        <?php $end = ['9:00', '9:30', '10:00', '10:30']; ?>
        <h5 for="time">時間</h5>
        <select id="start" class="form-control">
            <?php foreach ($start as $time): ?>
                <option value="<?= $time ?>">
                    <?= h($time) ?>
                </option>
            <?php endforeach; ?>
        </select>
        〜
        <select id="end" class="form-control">
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
