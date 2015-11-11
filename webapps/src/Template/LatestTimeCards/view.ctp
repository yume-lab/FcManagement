<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Latest Time Card'), ['action' => 'edit', $latestTimeCard->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Latest Time Card'), ['action' => 'delete', $latestTimeCard->id], ['confirm' => __('Are you sure you want to delete # {0}?', $latestTimeCard->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Latest Time Cards'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Latest Time Card'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Time Card States'), ['controller' => 'TimeCardStates', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Time Card State'), ['controller' => 'TimeCardStates', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="latestTimeCards view large-9 medium-8 columns content">
    <h3><?= h($latestTimeCard->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Store') ?></th>
            <td><?= $latestTimeCard->has('store') ? $this->Html->link($latestTimeCard->store->name, ['controller' => 'Stores', 'action' => 'view', $latestTimeCard->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Employee') ?></th>
            <td><?= $latestTimeCard->has('employee') ? $this->Html->link($latestTimeCard->employee->name, ['controller' => 'Employees', 'action' => 'view', $latestTimeCard->employee->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Time Card State') ?></th>
            <td><?= $latestTimeCard->has('time_card_state') ? $this->Html->link($latestTimeCard->time_card_state->name, ['controller' => 'TimeCardStates', 'action' => 'view', $latestTimeCard->time_card_state->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($latestTimeCard->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($latestTimeCard->created) ?></tr>
        </tr>
        <tr>
            <th><?= __('Updated') ?></th>
            <td><?= h($latestTimeCard->updated) ?></tr>
        </tr>
    </table>
</div>
