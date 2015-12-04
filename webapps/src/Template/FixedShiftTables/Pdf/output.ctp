<?php $this->assign('title', date('Y年m月', strtotime($data['target_ym'])) . 'シフト表' ); ?>
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
    body {
        zoom:0.8;
    }
    body, html, #contents {
        width: 96%;
    }
    .fc-timeline-event .fc-content {
        white-space: pre-line;
    }
    div {
        overflow: visible !important;
    }
    #fix-shift-table {
    }
    .fc-time-area col {
        width: 1em !important;
    }

    /*
        .fc-rows td.fc-widget-content div,
        .fc-event-container {
            height: 40px !important;
        }
        .fc-timeline-event,
        .fc-timeline-event .fc-time {
            padding: 0;
        }
    */
</style>

<?php
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

$events = [];
$shift = json_decode($data['body']);
foreach ($shift as $s) {
    $s = (array) $s;
    $s['resourceId'] = $s['employeeId'];
    unset($s['title']);
    $events[] = $s;
}

$events = json_encode($events);
?>

<script>

    $(function() {
        $('#fix-shift-table').fullCalendar({
            schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
            now: '<?= date('Y-m-d', strtotime($data['target_ym'].'01')); ?>',
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
            resourceAreaWidth: '8%',
            resourceLabelText: '従業員',
            resources: <?= $resources ?>,
            events: <?= $events ?>
        });

    });

</script>

<div id="fix-shift-table"></div>
