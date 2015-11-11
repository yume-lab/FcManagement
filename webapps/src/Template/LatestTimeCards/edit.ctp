<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $latestTimeCard->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $latestTimeCard->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Latest Time Cards'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Time Card States'), ['controller' => 'TimeCardStates', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Time Card State'), ['controller' => 'TimeCardStates', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="latestTimeCards form large-9 medium-8 columns content">
    <?= $this->Form->create($latestTimeCard) ?>
    <fieldset>
        <legend><?= __('Edit Latest Time Card') ?></legend>
        <?php
            echo $this->Form->input('store_id', ['options' => $stores]);
            echo $this->Form->input('employee_id', ['options' => $employees]);
            echo $this->Form->input('time_card_state_id', ['options' => $timeCardStates]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
