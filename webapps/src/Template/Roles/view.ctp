<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Role'), ['action' => 'edit', $role->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Role'), ['action' => 'delete', $role->id], ['confirm' => __('Are you sure you want to delete # {0}?', $role->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Roles'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Role'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List User Stores'), ['controller' => 'UserStores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User Store'), ['controller' => 'UserStores', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="roles view large-9 medium-8 columns content">
    <h3><?= h($role->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Alias') ?></th>
            <td><?= h($role->alias) ?></td>
        </tr>
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($role->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($role->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($role->created) ?></tr>
        </tr>
        <tr>
            <th><?= __('Updated') ?></th>
            <td><?= h($role->updated) ?></tr>
        </tr>
        <tr>
            <th><?= __('Is Deleted') ?></th>
            <td><?= $role->is_deleted ? __('Yes') : __('No'); ?></td>
         </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Employees') ?></h4>
        <?php if (!empty($role->employees)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Role Id') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Email') ?></th>
                <th><?= __('Phone Number') ?></th>
                <th><?= __('Zip Code') ?></th>
                <th><?= __('Address 1') ?></th>
                <th><?= __('Address 2') ?></th>
                <th><?= __('Address 3') ?></th>
                <th><?= __('First Name') ?></th>
                <th><?= __('Last Name') ?></th>
                <th><?= __('Is Deleted') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Updated') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($role->employees as $employees): ?>
            <tr>
                <td><?= h($employees->id) ?></td>
                <td><?= h($employees->role_id) ?></td>
                <td><?= h($employees->store_id) ?></td>
                <td><?= h($employees->email) ?></td>
                <td><?= h($employees->phone_number) ?></td>
                <td><?= h($employees->zip_code) ?></td>
                <td><?= h($employees->address_1) ?></td>
                <td><?= h($employees->address_2) ?></td>
                <td><?= h($employees->address_3) ?></td>
                <td><?= h($employees->first_name) ?></td>
                <td><?= h($employees->last_name) ?></td>
                <td><?= h($employees->is_deleted) ?></td>
                <td><?= h($employees->created) ?></td>
                <td><?= h($employees->updated) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Employees', 'action' => 'view', $employees->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Employees', 'action' => 'edit', $employees->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Employees', 'action' => 'delete', $employees->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employees->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related User Stores') ?></h4>
        <?php if (!empty($role->user_stores)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('User Id') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Role Id') ?></th>
                <th><?= __('Is Deleted') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Updated') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($role->user_stores as $userStores): ?>
            <tr>
                <td><?= h($userStores->id) ?></td>
                <td><?= h($userStores->user_id) ?></td>
                <td><?= h($userStores->store_id) ?></td>
                <td><?= h($userStores->role_id) ?></td>
                <td><?= h($userStores->is_deleted) ?></td>
                <td><?= h($userStores->created) ?></td>
                <td><?= h($userStores->updated) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'UserStores', 'action' => 'view', $userStores->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'UserStores', 'action' => 'edit', $userStores->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'UserStores', 'action' => 'delete', $userStores->id], ['confirm' => __('Are you sure you want to delete # {0}?', $userStores->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
