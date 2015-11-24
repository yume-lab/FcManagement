<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Employee Time Card'), ['action' => 'edit', $employeeTimeCard->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Employee Time Card'), ['action' => 'delete', $employeeTimeCard->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeTimeCard->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Employee Time Cards'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee Time Card'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="employeeTimeCards view large-9 medium-8 columns content">
    <h3><?= h($employeeTimeCard->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Store') ?></th>
            <td><?= $employeeTimeCard->has('store') ? $this->Html->link($employeeTimeCard->store->name, ['controller' => 'Stores', 'action' => 'view', $employeeTimeCard->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Employee') ?></th>
            <td><?= $employeeTimeCard->has('employee') ? $this->Html->link($employeeTimeCard->employee->name, ['controller' => 'Employees', 'action' => 'view', $employeeTimeCard->employee->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Worked Date') ?></th>
            <td><?= h($employeeTimeCard->worked_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($employeeTimeCard->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Work Minute') ?></th>
            <td><?= $this->Number->format($employeeTimeCard->work_minute) ?></td>
        </tr>
        <tr>
            <th><?= __('Break Minute') ?></th>
            <td><?= $this->Number->format($employeeTimeCard->break_minute) ?></td>
        </tr>
        <tr>
            <th><?= __('Real Minute') ?></th>
            <td><?= $this->Number->format($employeeTimeCard->real_minute) ?></td>
        </tr>
        <tr>
            <th><?= __('Total Work Minute') ?></th>
            <td><?= $this->Number->format($employeeTimeCard->total_work_minute) ?></td>
        </tr>
        <tr>
            <th><?= __('Total Break Minute') ?></th>
            <td><?= $this->Number->format($employeeTimeCard->total_break_minute) ?></td>
        </tr>
        <tr>
            <th><?= __('Total Real Minute') ?></th>
            <td><?= $this->Number->format($employeeTimeCard->total_real_minute) ?></td>
        </tr>
        <tr>
            <th><?= __('Amount') ?></th>
            <td><?= $this->Number->format($employeeTimeCard->amount) ?></td>
        </tr>
        <tr>
            <th><?= __('Start Time') ?></th>
            <td><?= h($employeeTimeCard->start_time) ?></tr>
        </tr>
        <tr>
            <th><?= __('End Time') ?></th>
            <td><?= h($employeeTimeCard->end_time) ?></tr>
        </tr>
        <tr>
            <th><?= __('Break Start Time') ?></th>
            <td><?= h($employeeTimeCard->break_start_time) ?></tr>
        </tr>
        <tr>
            <th><?= __('Break End Time') ?></th>
            <td><?= h($employeeTimeCard->break_end_time) ?></tr>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($employeeTimeCard->created) ?></tr>
        </tr>
        <tr>
            <th><?= __('Updated') ?></th>
            <td><?= h($employeeTimeCard->updated) ?></tr>
        </tr>
        <tr>
            <th><?= __('Is Deleted') ?></th>
            <td><?= $employeeTimeCard->is_deleted ? __('Yes') : __('No'); ?></td>
         </tr>
    </table>
</div>
