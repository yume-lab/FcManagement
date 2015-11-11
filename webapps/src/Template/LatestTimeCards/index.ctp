<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Latest Time Card'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Time Card States'), ['controller' => 'TimeCardStates', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Time Card State'), ['controller' => 'TimeCardStates', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="latestTimeCards index large-9 medium-8 columns content">
    <h3><?= __('Latest Time Cards') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('store_id') ?></th>
                <th><?= $this->Paginator->sort('employee_id') ?></th>
                <th><?= $this->Paginator->sort('time_card_state_id') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('updated') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($latestTimeCards as $latestTimeCard): ?>
            <tr>
                <td><?= $this->Number->format($latestTimeCard->id) ?></td>
                <td><?= $latestTimeCard->has('store') ? $this->Html->link($latestTimeCard->store->name, ['controller' => 'Stores', 'action' => 'view', $latestTimeCard->store->id]) : '' ?></td>
                <td><?= $latestTimeCard->has('employee') ? $this->Html->link($latestTimeCard->employee->name, ['controller' => 'Employees', 'action' => 'view', $latestTimeCard->employee->id]) : '' ?></td>
                <td><?= $latestTimeCard->has('time_card_state') ? $this->Html->link($latestTimeCard->time_card_state->name, ['controller' => 'TimeCardStates', 'action' => 'view', $latestTimeCard->time_card_state->id]) : '' ?></td>
                <td><?= h($latestTimeCard->created) ?></td>
                <td><?= h($latestTimeCard->updated) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $latestTimeCard->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $latestTimeCard->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $latestTimeCard->id], ['confirm' => __('Are you sure you want to delete # {0}?', $latestTimeCard->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
