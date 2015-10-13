<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User Store'), ['action' => 'edit', $userStore->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete User Store'), ['action' => 'delete', $userStore->id], ['confirm' => __('Are you sure you want to delete # {0}?', $userStore->id)]) ?> </li>
        <li><?= $this->Html->link(__('List User Stores'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User Store'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Roles'), ['controller' => 'Roles', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Role'), ['controller' => 'Roles', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="userStores view large-9 medium-8 columns content">
    <h3><?= h($userStore->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('User') ?></th>
            <td><?= $userStore->has('user') ? $this->Html->link($userStore->user->id, ['controller' => 'Users', 'action' => 'view', $userStore->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Store') ?></th>
            <td><?= $userStore->has('store') ? $this->Html->link($userStore->store->name, ['controller' => 'Stores', 'action' => 'view', $userStore->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Role') ?></th>
            <td><?= $userStore->has('role') ? $this->Html->link($userStore->role->name, ['controller' => 'Roles', 'action' => 'view', $userStore->role->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($userStore->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($userStore->created) ?></tr>
        </tr>
        <tr>
            <th><?= __('Updated') ?></th>
            <td><?= h($userStore->updated) ?></tr>
        </tr>
        <tr>
            <th><?= __('Is Deleted') ?></th>
            <td><?= $userStore->is_deleted ? __('Yes') : __('No'); ?></td>
         </tr>
    </table>
</div>
