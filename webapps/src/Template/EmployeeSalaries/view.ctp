<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Employee Salary'), ['action' => 'edit', $employeeSalary->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Employee Salary'), ['action' => 'delete', $employeeSalary->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeSalary->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Employee Salaries'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee Salary'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="employeeSalaries view large-9 medium-8 columns content">
    <h3><?= h($employeeSalary->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($employeeSalary->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Store Id') ?></th>
            <td><?= $this->Number->format($employeeSalary->store_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Employee Id') ?></th>
            <td><?= $this->Number->format($employeeSalary->employee_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Amount') ?></th>
            <td><?= $this->Number->format($employeeSalary->amount) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($employeeSalary->created) ?></tr>
        </tr>
        <tr>
            <th><?= __('Updated') ?></th>
            <td><?= h($employeeSalary->updated) ?></tr>
        </tr>
        <tr>
            <th><?= __('Is Deleted') ?></th>
            <td><?= $employeeSalary->is_deleted ? __('Yes') : __('No'); ?></td>
         </tr>
    </table>
</div>
