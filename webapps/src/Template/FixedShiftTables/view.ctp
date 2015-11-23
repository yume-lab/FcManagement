<?php $this->assign('title', date('Y年m月', strtotime($data['target_ym'])) . 'シフト表' ); ?>

<?= $this->element('Calendar/assets'); ?>

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
        $('#fix-shift-table').fullCalendar({
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
            resourceLabelText: '従業員',
            resources: <?= $resources ?>,
            events: <?= $events ?>
        });

    });

</script>

<div id="fix-shift-table"></div>
