<?php
/**
 * シフト表で使用するカレンダーライブラリ.
 */
echo $this->Html->css('fullcalendar.min.css');
echo $this->Html->css('scheduler.min.css');

echo $this->Html->script('moment.min.js');
echo $this->Html->script('fullcalendar.min.js');
echo $this->Html->script('scheduler.min.js');
echo $this->Html->script(BOWER_PATH.'/fullcalendar/dist/lang-all.js');

?>

<style>
    .fc-timeline-event .fc-content {
        white-space: pre-line;
    }
    .fc-rows td.fc-widget-content div,
    .fc-event-container {
        height: 40px !important;
    }
    .fc-timeline-event,
    .fc-timeline-event .fc-time {
        padding: 0;
    }
</style>
