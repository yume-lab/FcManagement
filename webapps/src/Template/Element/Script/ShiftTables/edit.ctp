<?php
/**
 * TODO: 外部JS化が文字化けして難しかった
 * シフト作成のjavascript.
 */

$tempSaveSuccessMessage = 'シフトを一時保存しました';
?>

<?php // 一時保存の完了通知用 ?>
<button id="notice" class="noty hide"
        data-noty-options="{&quot;text&quot;:&quot;<?= $tempSaveSuccessMessage ?>&quot;,&quot;layout&quot;:&quot;top&quot;,&quot;type&quot;:&quot;information&quot;}">
</button>

<script type="text/javascript">
    (function($) {
        var $body = $('body');

        var MODE = {
            REGISTER : 0,
            UPDATE : 1
        }

        $(document).ready(function() {
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
                    return date + ' ' + $.data($body, name);
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

                console.log(event);
                $(calendarSelector).fullCalendar('renderEvent', event);
                destroyPopover();
                $.removeData($body);
            }

            var removeEvent = function(eventId) {
                if (eventId) {
                    $(calendarSelector).fullCalendar('removeEvents', eventId);
                }
                destroyPopover();
            }

            $(document).on('change', '.popover-select', function() {
                var name = $(this).data('set-name');
                $.data($body, name, $(this).val());
            });

            $(document).on('click', '#save', function() {
                var current = $(calendarSelector).fullCalendar('getDate');
                var events = $(calendarSelector).fullCalendar('clientEvents', function(event) {
                    var compareFormat = 'YYYYMM';
                    var startYm = moment(event.start).format(compareFormat);
                    var endYm = moment(event.end).format(compareFormat);
                    var ym = moment(current).format(compareFormat);
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
                return false;
            });

            /**
             * シフト更新処理
             */
            $(document).on('click', '#update', function() {
                var eventId = $.data($body, 'data-eventId');
                removeEvent(eventId);
                addNewEvent();
                return false;
            });

            /**
             * シフト更新処理
             */
            $(document).on('click', '#remove', function() {
                var eventId = $.data($body, 'data-eventId');
                removeEvent(eventId);
                $.removeData($body);
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
                    var ym = moment(current).format(compareFormat);
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
                selectable: true,
                allDaySlot: false,
                minTime: $('#opened').val(),
                maxTime: $('#closed').val(),
                select: function(start, end, jsEvent, view) {
                    jsEvent.preventDefault();
                    jsEvent.stopPropagation();

                    var $calendar = $(calendarSelector);

                    if (view.name === 'month') {
                        // 月表示の場合、シフト登録済みなら日付詳細へ
                        var compareFormat = 'YYYYMMDD';
                        var current = start;
                        var events = $calendar.fullCalendar('clientEvents', function(event) {
                            var eStart = moment(event.start).format(compareFormat);
                            var now = moment(current).format(compareFormat);
                            return now == eStart;
                        });
                        if (events.length) {
                            destroyPopover();
                            $calendar.fullCalendar('gotoDate', current);
                            $calendar.fullCalendar('changeView', 'agendaDay');
                            return;
                        }
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
                                    title: shift.title,
                                    start: shift.start,
                                    end: shift.end,
                                    employeeId: shift.employeeId,
                                });
                            });
                            console.log(events);
                            hideLoading();
                            callback(events);
                        }
                    });
                }
            });
        });
    })(jQuery);

</script>
