/**
 * シフトプレビューのスクリプト
 */
$(function() {

    /**
     * 従業員エリアのデータ
     */
    var resources = (function() {
        return $.parseJSON($('#resources').val()) || [];
    })();

    /**
     * シフトデータ
     */
    var events = (function() {
        return $.parseJSON($('#events').val()) || [];
    })();

    $('#fix-shift-table').fullCalendar({
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        now: $('#currentMonth').val(),
        header: {
            right: false
        },
        slotLabelFormat: {
            month: [
                'D',
                'ddd'
            ]
        },
        editable: false,
        lang: 'ja',
        timeFormat: 'H(:mm)',
        defaultView: 'timelineMonth',
        displayEventEnd: true,
        resourceAreaWidth: '8em',
        resourceLabelText: '従業員',
        resources: resources,
        events: events
    });
});
