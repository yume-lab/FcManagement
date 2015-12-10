<?php $this->assign('title', date('Y年m月', strtotime($data['target_ym'])) . 'シフト表' ); ?>

<?= $this->element('Assets/calendar'); ?>
<?= $this->Html->css('FixedShiftTables/prepare.css') ?>

<div id="fix-shift-table"></div>

<input type="hidden" id="currentMonth" value="<?= date('Y-m-d', strtotime($data['target_ym'].'01')); ?>" />
<input type="hidden" id="resources" value='<?= $resources ?>' />
<input type="hidden" id="events" value='<?= $events ?>' />

<?= $this->Html->script('FixedShiftTables/view.js'); ?>
