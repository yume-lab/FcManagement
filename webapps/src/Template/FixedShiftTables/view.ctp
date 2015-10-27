<?php // TODO: とにかく作り直し. ?>

<?php // $this->assign('title', date('Y年m月', strtotime($data['target_ym'])) . 'シフト表' ); ?>

<title><?= date('Y年m月', strtotime($data['target_ym'])) ?></title>

<?= $this->Html->css(CHARISMA_CSS.'/bootstrap-spacelab.min.css') ?>
<?= $this->Html->css(CHARISMA_CSS.'/charisma-app.css') ?>

<link href='/sc/lib/fullcalendar.min.css' rel='stylesheet' />
<link href='/sc/lib/fullcalendar.print.css' rel='stylesheet' media='print' />
<link href='/sc/scheduler.min.css' rel='stylesheet' />
<script src='/sc/lib/moment.min.js'></script>
<script src='/sc/lib/jquery.min.js'></script>
<script src='/sc/lib/fullcalendar.min.js'></script>
<script src='/sc/scheduler.min.js'></script>
<?= $this->Html->script(CHARISMA_BOWER.'/fullcalendar/dist/lang-all.js') ?>

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

<style>
    #calendar {
        max-width: 1200px;
        margin: 20px auto;
    }
    .fc-timeline-event .fc-content {
        white-space: pre-line;
    }
</style>

<script>

    $(function() {
        $('#calendar').fullCalendar({
            now: '<?= date('Y-m-d', strtotime($data['target_ym'].'01')); ?>',
            header: {
                right: false
            },
            editable: false,
            lang: 'ja',
            timeFormat: 'H(:mm)',
            defaultView: 'timelineMonth',
            displayEventEnd: true,
            resourceAreaWidth: '8%',
            resourceLabelText: 'パート',
            resources: <?= $resources ?>,
            events: <?= $events ?>
        });

    });

</script>

<div id='calendar'></div>
