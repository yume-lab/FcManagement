<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $employeeTimeCard->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $employeeTimeCard->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Employee Time Cards'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="employeeTimeCards form large-9 medium-8 columns content">
    <?= $this->Form->create($employeeTimeCard) ?>
    <fieldset>
        <legend><?= __('Edit Employee Time Card') ?></legend>
        <?php
            echo $this->Form->input('store_id', ['options' => $stores]);
            echo $this->Form->input('employee_id', ['options' => $employees]);
            echo $this->Form->input('worked_date');
            echo $this->Form->input('start_time');
            echo $this->Form->input('end_time');
            echo $this->Form->input('break_start_time');
            echo $this->Form->input('break_end_time');
            echo $this->Form->input('work_minute');
            echo $this->Form->input('break_minute');
            echo $this->Form->input('real_minute');
            echo $this->Form->input('total_work_minute');
            echo $this->Form->input('total_break_minute');
            echo $this->Form->input('total_real_minute');
            echo $this->Form->input('amount');
            echo $this->Form->input('is_deleted');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
