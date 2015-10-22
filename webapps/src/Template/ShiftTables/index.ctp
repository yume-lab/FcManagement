<?php $this->assign('title', 'シフト作成'); ?>

<?php // TODO: 暫定でここに ?>
<script src="http://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>

<?php // TODO: 暫定でここに ?>
<script type="text/javascript">
    var $document = $(document);
    var $body = $('body');
    var MODE = {
        REGISTER : 0,
        UPDATE : 1,
    };

    $document.ready(function() {
        var calendarSelector = '#shift-calendar';

        var $popover;

        var destroyPopover = function() {
            if ($popover) {
                $popover.popover('destroy');
            }
        }

        var showEventPopover = function($target, event, mode) {
            var current = event.start;

            $.data($body, 'data-time', current);
            $.data($body, 'data-eventId', event._id);

            destroyPopover();
            $target.popover({
                html: true,
                placement: function (context, source) {
                    var position = $(source).position();
//                    return (position.left > 500) ? 'left' : 'right';
                    return (position.top < 110) ? 'bottom' : 'top';
                },
                container: 'body',
                trigger: 'click',
                title: function() {
                    return $('#popover-header').html(current.format('YYYY/MM/DD')).html();
                },
                content: function() {
                    var $registerButtonArea = $('#btn-register');
                    var $updateButtonArea = $('#btn-update');
                    $registerButtonArea.hide();
                    $updateButtonArea.hide();

                    switch (mode) {
                        case MODE.REGISTER:
                            $registerButtonArea.show();
                            break;
                        case MODE.UPDATE:
                            $updateButtonArea.show();
                            break;
                    }
                    return $('#popover-content').html();
                }
            });
            $popover = $target.popover('show');

            $('.popover #employees').val((event.employeeId || ''));
            $('.popover #startTime').val(moment(event.start).format('HH:mm'));
            $('.popover #endTime').val(moment(event.end).format('HH:mm'));

            $('.popover-select').change();
        }

        var addNewEvent = function() {
            var date = moment($.data($body, 'data-time')).format('YYYY-MM-DD');
            var getTime = function(name) {
                return moment(date + $.data($body, name), 'YYYY-MM-DDHH:mm');
            }

            var employeeId = $.data($body, 'data-employeeId');
            var $employee = $('#employees').find('option').filter(function(row) {
                return employeeId === $(this).val();
            });
            var startTime = getTime('data-startTime');
            var endTime = getTime('data-endTime');

            var event = {
                title: $.trim($employee.html()),
                employeeId: $employee.val(),
                start: startTime,
                end: endTime
            };
            $(calendarSelector).fullCalendar('renderEvent', event);
            destroyPopover();
            $.removeData($body);
        }

        var removeEvent = function(eventId) {
            if (eventId) {
                $(calendarSelector).fullCalendar('removeEvents', eventId);
            }
            destroyPopover();
            $.removeData($body);
        }

        $document.on('change', '.popover-select', function() {
            var name = $(this).data('set-name');
            $.data($body, name, $(this).val());
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
            addNewEvent();
            return false;
        });

        /**
         * シフト更新処理
         */
        $document.on('click', '#update', function() {
            var eventId = $.data($body, 'data-eventId');
            removeEvent(eventId);
            addNewEvent();
            return false;
        });

        /**
         * シフト更新処理
         */
        $document.on('click', '#remove', function() {
            var eventId = $.data($body, 'data-eventId');
            removeEvent(eventId);
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
            eventLimit: 4,
            displayEventEnd: true,
            firstDay: 1,
            selectable: true,
            allDaySlot: false,
            select: function(start, end, jsEvent, view) {
                jsEvent.preventDefault();
                jsEvent.stopPropagation();

                var $calendar = $(calendarSelector);

                var compareFormat = 'YYYYMMDD';
                var events = $calendar.fullCalendar('clientEvents', function(event) {
                    var eStart = moment(event.start).format(compareFormat);
                    var now = moment(start).format(compareFormat);
                    return now == eStart;
                });

                var isDayDetail = (view.name === 'month' && events.length);
                if (isDayDetail) {
                    destroyPopover();
                    $calendar.fullCalendar('gotoDate', start);
                    $calendar.fullCalendar('changeView', 'agendaDay');
                    return;
                }

                var event = {
                    employeeId: '',
                    start: start,
                    end: end
                };
                showEventPopover($(jsEvent.target), event, MODE.REGISTER);
            },
            eventClick: function(calEvent, jsEvent, view) {
                showEventPopover($(this), calEvent, MODE.UPDATE);
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


<?php // ポップアップ用 ?>
<div id="popover-header" class="hide">
    <?php // 動的に日付いれる ?>
</div>
<div id="popover-content" class="hide col-md-5">
    <div class="form-group col-md-12 center">
        <h5>従業員</h5>
        <select id="employees" class="form-control popover-select" data-set-name="data-employeeId">
            <?php foreach ($employees as $employee): ?>
                <option value="<?= $employee->id ?>">
                    <?= h($employee->last_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-md-12 center">
        <?php
        // TODO: サーバーで作る.
        $times = [];
        for ($i = 0; $i < 48; $i++) {
            $time = date("H:i", strtotime("+". $i * 30 ." minute",(3600)));
            $times[] = $time;
        }
        ?>

        <?php $start = $times; ?>
        <?php $end = $times; ?>
        <h5>時間</h5>
        <select id="startTime" class="form-control popover-select" data-set-name="data-startTime">
            <?php foreach ($start as $time): ?>
                <option value="<?= $time ?>">
                    <?= h($time) ?>
                </option>
            <?php endforeach; ?>
        </select>
        〜
        <select id="endTime" class="form-control popover-select" data-set-name="data-endTime">
            <?php foreach ($end as $time): ?>
                <option value="<?= $time ?>">
                    <?= h($time) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div id="btn-register" class="form-group col-md-12 center" style="display: none;">
        <a href="#" class="btn btn-primary" id="register">登録</a>
    </div>

    <div id="btn-update" class="form-group col-md-12 center" style="display: none;">
        <a href="#" class="btn btn-danger" id="remove">削除</a>
        <a href="#" class="btn btn-success" id="update">更新</a>
    </div>
</div>
