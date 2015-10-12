<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Employee'), ['action' => 'edit', $employee->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Employee'), ['action' => 'delete', $employee->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employee->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Roles'), ['controller' => 'Roles', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Role'), ['controller' => 'Roles', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="employees view large-9 medium-8 columns content">
    <h3><?= h($employee->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Role') ?></th>
            <td><?= $employee->has('role') ? $this->Html->link($employee->role->name, ['controller' => 'Roles', 'action' => 'view', $employee->role->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Store') ?></th>
            <td><?= $employee->has('store') ? $this->Html->link($employee->store->name, ['controller' => 'Stores', 'action' => 'view', $employee->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= h($employee->email) ?></td>
        </tr>
        <tr>
            <th><?= __('Phone Number') ?></th>
            <td><?= h($employee->phone_number) ?></td>
        </tr>
        <tr>
            <th><?= __('Zip Code') ?></th>
            <td><?= h($employee->zip_code) ?></td>
        </tr>
        <tr>
            <th><?= __('Address 1') ?></th>
            <td><?= h($employee->address_1) ?></td>
        </tr>
        <tr>
            <th><?= __('Address 2') ?></th>
            <td><?= h($employee->address_2) ?></td>
        </tr>
        <tr>
            <th><?= __('Address 3') ?></th>
            <td><?= h($employee->address_3) ?></td>
        </tr>
        <tr>
            <th><?= __('First Name') ?></th>
            <td><?= h($employee->first_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Last Name') ?></th>
            <td><?= h($employee->last_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($employee->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($employee->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($employee->created) ?></tr>
        </tr>
        <tr>
            <th><?= __('Updated') ?></th>
            <td><?= h($employee->updated) ?></tr>
        </tr>
        <tr>
            <th><?= __('Is Deleted') ?></th>
            <td><?= $employee->is_deleted ? __('Yes') : __('No'); ?></td>
         </tr>
    </table>
</div>
