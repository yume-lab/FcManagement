<?php $this->assign('title', 'シフト作成'); ?>

<?php // TODO: 暫定でここに ?>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>



<?php // TODO: 暫定でここに ?>
<script type="text/javascript">

    $(document).ready(function() {
        $('#external-events .people').each(function() {

            // store data so the calendar knows to render an event upon drop
            $(this).data('events', {
                title: $.trim($(this).text()), // use the element's text as the event title
            });

            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                helper: 'clone',
                revert: 'invalid',
                revertDuration: 0  //  original position after the drag
            });
        });

        $('#shift-calendar').fullCalendar({
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
            editable: true,
            droppable: true, // this allows things to be dropped onto the calendar
            drop: function(date, allDay) { // this function is called when something is dropped

                // retrieve the dropped element's stored Event Object
                var originalEventObject = $(this).data('events');

                // we need to copy it, so that multiple events don't have a reference to the same object
                var copiedEventObject = $.extend({}, originalEventObject);

                // assign it the date that was reported
                copiedEventObject.start = date;
                copiedEventObject.allDay = allDay;

                // render the event on the calendar
                // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                $('#shift-calendar').fullCalendar('renderEvent', copiedEventObject, true);

                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }

            },
            events: [
                {
                    title: 'Lunch',
                    start: '2015-10-12T12:00:00'
                },
                {
                    title: 'Birthday Party',
                    start: '2015-10-13T07:00:00'
                },
                {
                    title: 'Click for Google',
                    url: 'http://google.com/',
                    start: '2015-10-28'
                }
            ]
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
                <div class="people">test</div>
                <div class="people">test2</div>
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
