/**
 * シフト編集エリアのスクリプト
 */

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
     * 従業員エリアのデータ
     */
    var resources = (function() {
        return $.parseJSON($('#resources').val()) || [];
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

        var $popover = $(popoverSelector);
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
            year: current.format('YYYY'),
            month: current.format('MM'),
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
            year: current.format('YYYY'),
            month: current.format('MM'),
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
        timeFormat: 'HH:mm',
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
        resources: resources
    });
});
