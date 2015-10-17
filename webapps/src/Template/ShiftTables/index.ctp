<?php $this->assign('title', 'シフト作成'); ?>

<?php // TODO: 暫定でここに ?>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>



<?php // TODO: 暫定でここに ?>
<script type="text/javascript">

    $(document).ready(function() {
        $('#external-events .people').each(function() {
            // ドラッグされた従業員を一時保存
            $(this).data('events', {
                title: $.trim($(this).text()), // use the element's text as the event title
                backgroundColor: $(this).css('background-color')
            });

            $(this).draggable({
                zIndex: 999,
                revert: true,
                revertDuration: 0
            });
        });

        var calendarSelector = '#shift-calendar';
        $(calendarSelector).fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            titleFormat: {
                month: 'YYYY年M月',
                week: "YYYY年M月D日",
                day: "YYYY年M月D日"
            },
            buttonText: {
                today: '今日',
                month: '月',
                week: '週',
                day: '日'
            },
            // 月名称
            monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            // 月略称
            monthNamesShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            // 曜日名称
            dayNames: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
            // 曜日略称
            dayNamesShort: ['日', '月', '火', '水', '木', '金', '土'],
            // 時間の書式
            timeFormat: 'H(:mm)',
            editable: true,
            droppable: true,
            timezone: 'Asia/Tokyo',
            eventLimit: true,
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
                var source = $(this).data('events');
                var newEvent = $.extend({}, source);

                newEvent.start = date;
                newEvent.allDay = false;

                $(calendarSelector).fullCalendar('renderEvent', newEvent);
            },
            // TODO: URLを指定して、シフトデータをAPIで取る
            events: '/api/shift'
        });
    });

</script>



<div class="row">
    <div class="col-md-2">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-calendar"></i> 従業員一覧</h2>
            </div>

            <div id="external-events" class="box-content">
                <div class="people" style="background-color: red;">たなか</div>
                <div class="people" style="background-color: blue;">さとう</div>
            </div>

        </div>
    </div>
    <div class="col-md-10">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-calendar"></i> シフト表</h2>
            </div>

            <div class="box-content">
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
