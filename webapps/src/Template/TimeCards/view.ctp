<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Time Card'), ['action' => 'edit', $timeCard->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Time Card'), ['action' => 'delete', $timeCard->id], ['confirm' => __('Are you sure you want to delete # {0}?', $timeCard->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Time Cards'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Time Card'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="timeCards view large-9 medium-8 columns content">
    <h3><?= h($timeCard->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Store') ?></th>
            <td><?= $timeCard->has('store') ? $this->Html->link($timeCard->store->name, ['controller' => 'Stores', 'action' => 'view', $timeCard->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Employee') ?></th>
            <td><?= $timeCard->has('employee') ? $this->Html->link($timeCard->employee->name, ['controller' => 'Employees', 'action' => 'view', $timeCard->employee->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($timeCard->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Target Ym') ?></th>
            <td><?= $this->Number->format($timeCard->target_ym) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($timeCard->created) ?></tr>
        </tr>
        <tr>
            <th><?= __('Updated') ?></th>
            <td><?= h($timeCard->updated) ?></tr>
        </tr>
        <tr>
            <th><?= __('Is Deleted') ?></th>
            <td><?= $timeCard->is_deleted ? __('Yes') : __('No'); ?></td>
         </tr>
    </table>
    <div class="row">
        <h4><?= __('Body') ?></h4>
        <?= $this->Text->autoParagraph(h($timeCard->body)); ?>
    </div>
</div>
