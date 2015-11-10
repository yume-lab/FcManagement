<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Time Card'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="timeCards index large-9 medium-8 columns content">
    <h3><?= __('Time Cards') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('store_id') ?></th>
                <th><?= $this->Paginator->sort('employee_id') ?></th>
                <th><?= $this->Paginator->sort('target_ym') ?></th>
                <th><?= $this->Paginator->sort('is_deleted') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('updated') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($timeCards as $timeCard): ?>
            <tr>
                <td><?= $this->Number->format($timeCard->id) ?></td>
                <td><?= $timeCard->has('store') ? $this->Html->link($timeCard->store->name, ['controller' => 'Stores', 'action' => 'view', $timeCard->store->id]) : '' ?></td>
                <td><?= $timeCard->has('employee') ? $this->Html->link($timeCard->employee->name, ['controller' => 'Employees', 'action' => 'view', $timeCard->employee->id]) : '' ?></td>
                <td><?= $this->Number->format($timeCard->target_ym) ?></td>
                <td><?= h($timeCard->is_deleted) ?></td>
                <td><?= h($timeCard->created) ?></td>
                <td><?= h($timeCard->updated) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $timeCard->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $timeCard->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $timeCard->id], ['confirm' => __('Are you sure you want to delete # {0}?', $timeCard->id)]) ?>
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
