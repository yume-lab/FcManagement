<?php $this->assign('title', 'シフト作成'); ?>

<?php // TODO: 暫定でここに ?>
<script src="http://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>

<?php // TODO: 暫定でここに ?>
<script type="text/javascript">

    $(document).ready(function() {
        var calendarSelector = '#shift-calendar';

        $('#save').on('click', function() {
            var events = $(calendarSelector).fullCalendar( 'clientEvents');
            console.log(events);
        });

        $('#employee-table .fc-event').each(function() {
            // ドラッグされた従業員を一時保存
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                backgroundColor: $(this).css('background-color')
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
                var $calendar = $(calendarSelector);
                $calendar.fullCalendar('gotoDate', date);
                $calendar.fullCalendar('changeView', 'agendaDay');
            },
            eventClick: function(calEvent, jsEvent, view) {
                // TODO
                // イベントクリックイベント. そのイベントの詳細をPopupで表示する.
                console.log(calEvent);
                console.log(jsEvent);
                console.log(view);
                console.log(calEvent.start)
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
<style>
    #employee-table .fc-event {
        margin: 10px 0;
        cursor: pointer;
        border-radius: 3px;
        background-color: #2fa4e7;
    }
</style>


<div class="row">
    <div class="col-md-2">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-calendar"></i> 従業員一覧</h2>
            </div>

            <div id="employee-table" class="box-content">
                <?php foreach ($employees as $employee): ?>
                    <div class="fc-event">
                        <div class="fc-event-inner">
                            <?= $employee->last_name ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
    <div class="col-md-10">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-calendar"></i> シフト表</h2>
            </div>

            <div class="box-content">
                <div class="box col-md-12">
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
