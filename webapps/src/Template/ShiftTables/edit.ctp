<?php $this->assign('title', 'シフト作成'); ?>

<?= $this->element('Calendar/assets'); ?>

<?php
// TODO: APIにする
$resources = [];
foreach ($employees as $employee) {
    $tmp = [
        'id' => $employee->id,
        'title' => $employee->last_name,
    ];
    $resources[] = $tmp;
    unset($tmp);
}

$resources = json_encode($resources);
?>

<?php // 一時保存の完了通知用 ?>
<?= $this->element('Notice/show_top', ['message' => 'シフトを一時保存しました']); ?>

<style>
    .fc-timelineMonth-view  .fc-time-area .fc-widget-header .fc-cell-text {
        cursor: pointer;
    }
</style>

<script>

    $(document).ready(function() {
        var $body = $('body');

        var MODE = {
            REGISTER : 0,
            UPDATE : 1
        }

        var popoverSelector = '.popover';

        var calendarSelector = '#shift-calendar';

        /**
         * 休憩基本設定の情報
         */
        var restedTable = (function() {
            var range = $('#break-range').val();
            var restedInfo = $.parseJSON(range) || [];
            restedInfo.sort(function(one, two) {
                return parseInt(one.worked) - parseInt(two.worked);
            });
            return restedInfo;
        })();

        /**
         * Popoverの非表示処理.
         */
        var destroyPopover = function() {
            $(popoverSelector).each(function() {
                $(this).popover('destroy');
            });
        }

        /**
         * Popoverの表示.
         */
        var showEventPopover = function($target, event, mode) {
            var current = event.start;

            $.data($body, 'data-time', current);
            $.data($body, 'data-eventId', event._id);

            destroyPopover();
            $target.popover({
                html: true,
                placement: function (context, source) {
                    var position = $(source).position();
                    return (position.left > 500) ? 'left' : 'right';
//                    return (position.top < 110) ? 'bottom' : 'top';
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
            $target.popover('show');

            console.log(moment(event.start).format());

            $popover = $(popoverSelector);
            $popover.find('#employees').val((event.employeeId || ''));
            $popover.find('#startTime').val(moment(event.start).format('HH:mm'));
            $popover.find('#endTime').val(moment(event.end).format('HH:mm'));

            $('.popover-select').change();
        }

        /**
         * シフトの新規追加.
         */
        var addNewEvent = function() {
            var date = moment($.data($body, 'data-time')).format('YYYY-MM-DD');
            var getTime = function(name) {
                return date + ' ' + $.data($body, name);
            }

            var employeeId = $.data($body, 'data-employeeId');
            var $employee = $('#employees').find('option').filter(function(row) {
                return employeeId === $(this).val();
            });
            var startTime = getTime('data-startTime');
            var endTime = getTime('data-endTime');

            var event = {
//                title: $.trim($employee.html()),
                employeeId: $employee.val(),
                resourceId: $employee.val(),
                start: startTime,
                end: endTime
            };

            console.log(event);
            $(calendarSelector).fullCalendar('renderEvent', event);
            destroyPopover();
            $.removeData($body);
        }

        /**
         * シフトの削除.
         */
        var removeEvent = function(eventId) {
            if (eventId) {
                $(calendarSelector).fullCalendar('removeEvents', eventId);
            }
            destroyPopover();
        }

        /**
         * リソースエリア(従業員/時間)の更新.
         * これを呼び出して、リソーステキストのコールバックを呼び出す.
         */
        var refreshResources = function() {
            $(calendarSelector).fullCalendar('refetchResources');
        }

        /**
         * 表示する時間をまるめます.
         */
        var roundHour = function(src) {
            // 小数第二位まで残すため、100かけてまるめた後に、また100で割る
            var hour = src / 60;
            return Math.round(hour * 100) / 100;
        }

        /**
         * Popoverでの変更をDOMに関連付けする.
         */
        $(document).on('change', '.popover-select', function() {
            var name = $(this).data('set-name');
            $.data($body, name, $(this).val());
        });

        /**
         * 一時保存ボタン
         */
        $(document).on('click', '#save', function() {
            var current = $(calendarSelector).fullCalendar('getDate');
            console.log(current);
            var events = $(calendarSelector).fullCalendar('clientEvents', function(event) {
                var compareFormat = 'YYYYMM';
                var startYm = moment(event.start).format(compareFormat);
                var endYm = moment(event.end).format(compareFormat);
                var ym = current.format(compareFormat);
                return (startYm === ym && ym === endYm);
            });
            var parameter = {
                year: current.year(),
                month: current.month() + 1,
                shift: events
            };
            showLoading();
            $.ajax({
                type: 'POST',
                url: '/api/shift/update',
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
         * シフト登録処理
         */
        $(document).on('click', '#register', function() {
            addNewEvent();
            refreshResources();
            return false;
        });

        /**
         * シフト更新処理
         */
        $(document).on('click', '#update', function() {
            var eventId = $.data($body, 'data-eventId');
            removeEvent(eventId);
            addNewEvent();
            refreshResources();
            return false;
        });

        /**
         * シフト更新処理
         */
        $(document).on('click', '#remove', function() {
            var eventId = $.data($body, 'data-eventId');
            removeEvent(eventId);
            $.removeData($body);
            refreshResources();
            return false;
        });

        /**
         * シフト確定処理
         */
        $(document).on('click', '#fixed', function() {
            var current = $(calendarSelector).fullCalendar('getDate');
            var events = $(calendarSelector).fullCalendar('clientEvents', function(event) {
                var compareFormat = 'YYYYMM';
                var startYm = moment(event.start).format(compareFormat);
                var endYm = moment(event.end).format(compareFormat);
                var ym = current.format(compareFormat);
                return (startYm === ym && ym === endYm);
            });
            var data = {
                year: current.year(),
                month: current.month() + 1,
                shift: JSON.stringify(events)
            };

            console.log(data);
            $('#fixed_year').val(data.year);
            $('#fixed_month').val(data.month);
            $('#fixed_shift').val(data.shift);
            $('#fixed_form').submit();
        });

        /**
         * ポップオーバーのヘッダークリック時.
         * ポップオーバーの削除.
         */
        $(document).on('click', '.popover-title', function() {
            destroyPopover();
        });

        /**
         * 月表示から詳細を見れるように.
         * 同じセレクタでスタイルも上部に定義済み.
         */
        $(document).on('click',
            '.fc-timelineMonth-view  .fc-time-area .fc-widget-header .fc-cell-text', function() {
            var $calendar = $(calendarSelector);
            var date = $calendar.fullCalendar('getDate');
            // FIXME: フォーマット変わったらマズイ
            var day = $(this).text().split(' ')[0];
            date.date(day);

            console.log(date);
            $calendar.fullCalendar('gotoDate', date);
            $calendar.fullCalendar('changeView', 'timelineDay');
        });

        /**
         * シフト表エリア表示
         */
        $(calendarSelector).fullCalendar({
            schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'timelineMonth,timelineDay'
            },
            buttonText: {
                today: '今日',
                month: '月',
                week: '週',
                day: '日'
            },
            slotLabelFormat: {
                month: [
                    'D ddd'
                ],
                day: [
                    'H:mm'
                ]
            },
            views: {
                timelineDay: {
                    titleFormat: 'YYYY年MM月D日 ddd曜日'
                }
            },
            lang: 'ja',
            minTime: $('#opened').val(),
            maxTime: $('#closed').val(),
            slotMinutes: $('#interval').val(),
            snapMinutes: $('#interval').val(),
            selectable: true,
            editable: true,
            timezone: 'Asia/Tokyo',
            timeFormat: 'H(:mm)',
            defaultView: 'timelineMonth',
            displayEventEnd: true,
            viewRender: function(view) {
                destroyPopover();
            },
            eventDrop: function(event, delta, revertFunc, jsEvent, ui, view ) {
                console.log(event);
                var $employee = $('#employees').find('option').filter(function(row) {
                    return event.resourceId === $(this).val();
                });
                event.employeeId = $employee.val();
                refreshResources();
            },
            eventResize: function(event, delta, revertFunc, jsEvent, ui, view) {
                refreshResources();
            },
            select: function(start, end, jsEvent, view, resource) {
                jsEvent.preventDefault();
                jsEvent.stopPropagation();
                var event = {
                    employeeId: resource.id,
                    resourceId: resource.id,
                    start: start,
                    end: end
                };
                console.log(event);
                showEventPopover($(jsEvent.target), event, MODE.REGISTER);
            },
            eventClick: function(calEvent, jsEvent, view) {
                showEventPopover($(this), calEvent, MODE.UPDATE);
            },
            events: function(start, end, timezone, callback) {
                showLoading();
                $.ajax({
                    url: '/api/shift',
                    dataType: 'json',
                    data: {
                        start: start.format(),
                        end: end.format()
                    },
                    success: function(shifts) {
                        var events = [];
                        $.each(shifts, function(i, shift) {
                            events.push({
                                start: shift.start,
                                end: shift.end,
                                resourceId: shift.employeeId,
                                employeeId: shift.employeeId
                            });
                        });
                        console.log(events);
                        hideLoading();
                        callback(events);
                        refreshResources();
                    }
                });
            },
            resourceAreaWidth: '25%',
            resourceColumns: [
                {
                    labelText: '従業員',
                    field: 'title'
                },
                {
                    labelText: '月合計時間',
                    text: function(resource) {
                        console.log('text resource');
                        console.log(resource);
                        var events = $(calendarSelector).fullCalendar('clientEvents', function(event) {
                            return event.resourceId == resource.id;
                        });
                        console.log(events);

                        // 総労働時間と実働時間の計算
                        var worked = 0;
                        var rested = 0;
                        (events || []).forEach(function(event, index, arr) {
                            var dayWorked = event.end.diff(event.start, 'minutes');
                            var restedTimes = $.grep(restedTable, function(item, index) {
                                return (item.worked <= dayWorked);
                            }) || [];
                            var dayRested = 0;
                            if (restedTimes.length > 0) {
                                dayRested = restedTimes.pop().rested;
                            }
                            worked += parseInt(dayWorked);
                            rested += parseInt(dayRested);
                        });
                        return worked == 0 ? '0'
                            : roundHour(worked) + ' (' + roundHour(worked - rested) + ')';
                    }
                }
            ],
            resources: <?= $resources ?>
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
                <?= $this->Flash->render() ?>
                <div class="col-md-12" style="padding: 0.5em 0;">
                    <div class="alert alert-info">
                        「月合計時間」は、総労働 (実働時間)の順番で記載されています。<br/>
                        例： 8 (7.5)
                    </div>
                </div>

                <div class="col-md-12"
                     style="text-align: right;padding: 0.5em 0;">
                    <a id="save" class="btn btn-info" href="#">
                        <i class="glyphicon glyphicon-pencil icon-white"></i>
                        一時保存
                    </a>
                    <a id="fixed" class="btn btn-success" href="#">
                        <i class="glyphicon glyphicon-ok icon-white"></i>
                        シフト確定
                    </a>
                </div>

                <div id="shift-calendar"></div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="opened" value="<?= $opened ?>" />
<input type="hidden" id="closed" value="<?= $closed ?>" />
<input type="hidden" id="interval" value="<?= $interval ?>" />
<?php // JSON文字列が格納されるため、valueではシングルクォートで囲む ?>
<input type="hidden" id="break-range" value='<?= $break ?>' />

<form id="fixed_form" name="fixed_form" method="post" action="/shift/fixed">
    <input id="fixed_year" name="fixed_year" type="hidden" value="" />
    <input id="fixed_month" name="fixed_month" type="hidden" value="" />
    <input id="fixed_shift" name="fixed_shift" type="hidden" value="" />
</form>

<?= $this->element('Popover/ShiftTables/input', ['employees' => $employees]); ?>



