<?php $this->assign('title', 'シフト作成'); ?>

<?php // TODO: 暫定でここに ?>
<script src="http://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>

<div class="box-header well" data-original-title="">
    <h2><i class="glyphicon glyphicon-calendar"></i> 従業員一覧</h2>
</div>
<div class="box-content">
    <table class="table table-striped table-bordered bootstrap-datatable responsive">
        <thead>
        <tr>
            <th>ID</th>
            <th>氏名</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>てすと</td>
            <td>1:00</td>
        </tr>
        </tbody>
    </table>
</div>

<div class="box-header well" data-original-title="">
    <h2><i class="glyphicon glyphicon-calendar"></i> シフト表</h2>
</div>
<div class="box-content">
    <div id="shift-calendar"></div>
</div>



<?php // TODO: 暫定でここに ?>
<script type="text/javascript">

    $(function() {
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
            // 日付クリックイベント
            dayClick: function (e) {
                console.log(e);
            },
            editable: true,
            droppable: true, // this allows things to be dropped onto the calendar
            drop: function() {
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
