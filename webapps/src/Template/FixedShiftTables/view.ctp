<?php $this->assign('title', date('Y年m月', strtotime($data['target_ym'])) . 'シフト表' ); ?>

<?= $this->element('Calendar/assets'); ?>
<?= $this->Html->css('ShiftTables/timeline.css'); ?>
<?= $this->Html->css('FixedShiftTables/view.css'); ?>

<div id="fix-shift-table"></div>

<input type="hidden" id="currentMonth" value="<?= date('Y-m-d', strtotime($data['target_ym'].'01')); ?>" />
<input type="hidden" id="resources" value='<?= $resources ?>' />
<input type="hidden" id="events" value='<?= $events ?>' />

<?= $this->Html->script('FixedShiftTables/view.js'); ?>