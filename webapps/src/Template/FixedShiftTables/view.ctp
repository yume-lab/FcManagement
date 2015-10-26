<?php // TODO: とにかく作り直し. ?>

<?php // $this->assign('title', date('Y年m月', strtotime($data['target_ym'])) . 'シフト表' ); ?>

<title><?= date('Y年m月', strtotime($data['target_ym'])) ?></title>

<link href='/sc/lib/fullcalendar.min.css' rel='stylesheet' />
<link href='/sc/lib/fullcalendar.print.css' rel='stylesheet' media='print' />
<link href='/sc/scheduler.min.css' rel='stylesheet' />
<script src='/sc/lib/moment.min.js'></script>
<script src='/sc/lib/jquery.min.js'></script>
<script src='/sc/lib/fullcalendar.min.js'></script>
<script src='/sc/scheduler.min.js'></script>
<?= $this->Html->script(CHARISMA_BOWER.'/fullcalendar/dist/lang-all.js') ?>

<?= var_dump($data); ?>
<?php
    $resources = [];
    foreach ($employees as $employee) {
        $tmp = [
            'id' => $employee->id,
            'title' => $employee->first_name,
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
        $events[] = $s;
    }

    $events = json_encode($events);
?>

<script>

    $(function() {
        $('#calendar').fullCalendar({
            now: '<?= date('Y-m-01', strtotime($data['target_ym'])); ?>',
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
<style>

    body {
        margin: 0;
        padding: 0;
        font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
        font-size: 14px;
    }

    #calendar {
        max-width: 1200px;
        margin: 50px auto;
    }

</style>

<div id='calendar'></div>
