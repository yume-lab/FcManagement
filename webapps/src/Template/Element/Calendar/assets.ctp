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
