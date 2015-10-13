<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New User Store'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Roles'), ['controller' => 'Roles', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Role'), ['controller' => 'Roles', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="userStores index large-9 medium-8 columns content">
    <h3><?= __('User Stores') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('user_id') ?></th>
                <th><?= $this->Paginator->sort('store_id') ?></th>
                <th><?= $this->Paginator->sort('role_id') ?></th>
                <th><?= $this->Paginator->sort('is_deleted') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('updated') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($userStores as $userStore): ?>
            <tr>
                <td><?= $this->Number->format($userStore->id) ?></td>
                <td><?= $userStore->has('user') ? $this->Html->link($userStore->user->id, ['controller' => 'Users', 'action' => 'view', $userStore->user->id]) : '' ?></td>
                <td><?= $userStore->has('store') ? $this->Html->link($userStore->store->name, ['controller' => 'Stores', 'action' => 'view', $userStore->store->id]) : '' ?></td>
                <td><?= $userStore->has('role') ? $this->Html->link($userStore->role->name, ['controller' => 'Roles', 'action' => 'view', $userStore->role->id]) : '' ?></td>
                <td><?= h($userStore->is_deleted) ?></td>
                <td><?= h($userStore->created) ?></td>
                <td><?= h($userStore->updated) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $userStore->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $userStore->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $userStore->id], ['confirm' => __('Are you sure you want to delete # {0}?', $userStore->id)]) ?>
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
