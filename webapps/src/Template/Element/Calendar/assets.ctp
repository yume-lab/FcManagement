<?php
/**
 * シフト表で使用するカレンダーライブラリ.
 */
$base = '/vendor/calendar';

echo $this->Html->css($base.'/lib/fullcalendar.min.css');
echo $this->Html->css($base.'/scheduler.min.css');

echo $this->Html->script($base.'/lib/moment.min.js');
echo $this->Html->script($base.'/lib/fullcalendar.min.js');
echo $this->Html->script($base.'/scheduler.min.js');
echo $this->Html->script(CHARISMA_BOWER.'/fullcalendar/dist/lang-all.js');

?>

<style>
    .fc-timeline-event .fc-content {
        white-space: pre-line;
    }
    .fc-rows td.fc-widget-content div,
    .fc-content td.fc-widget-content div,
    .fc-event-container {
        height: 40px !important;
    }
    .fc-timeline-event,
    .fc-timeline-event .fc-time {
        padding: 0;
    }
</style>
