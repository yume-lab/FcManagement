<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Employee Time Card'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="employeeTimeCards index large-9 medium-8 columns content">
    <h3><?= __('Employee Time Cards') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('store_id') ?></th>
                <th><?= $this->Paginator->sort('employee_id') ?></th>
                <th><?= $this->Paginator->sort('worked_date') ?></th>
                <th><?= $this->Paginator->sort('start_time') ?></th>
                <th><?= $this->Paginator->sort('end_time') ?></th>
                <th><?= $this->Paginator->sort('break_start_time') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employeeTimeCards as $employeeTimeCard): ?>
            <tr>
                <td><?= $this->Number->format($employeeTimeCard->id) ?></td>
                <td><?= $employeeTimeCard->has('store') ? $this->Html->link($employeeTimeCard->store->name, ['controller' => 'Stores', 'action' => 'view', $employeeTimeCard->store->id]) : '' ?></td>
                <td><?= $employeeTimeCard->has('employee') ? $this->Html->link($employeeTimeCard->employee->name, ['controller' => 'Employees', 'action' => 'view', $employeeTimeCard->employee->id]) : '' ?></td>
                <td><?= h($employeeTimeCard->worked_date) ?></td>
                <td><?= h($employeeTimeCard->start_time) ?></td>
                <td><?= h($employeeTimeCard->end_time) ?></td>
                <td><?= h($employeeTimeCard->break_start_time) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $employeeTimeCard->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $employeeTimeCard->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $employeeTimeCard->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeTimeCard->id)]) ?>
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
