<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Store Category'), ['action' => 'edit', $storeCategory->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Store Category'), ['action' => 'delete', $storeCategory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $storeCategory->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Store Categories'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store Category'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="storeCategories view large-9 medium-8 columns content">
    <h3><?= h($storeCategory->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Alias') ?></th>
            <td><?= h($storeCategory->alias) ?></td>
        </tr>
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($storeCategory->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($storeCategory->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($storeCategory->created) ?></tr>
        </tr>
        <tr>
            <th><?= __('Updated') ?></th>
            <td><?= h($storeCategory->updated) ?></tr>
        </tr>
        <tr>
            <th><?= __('Is Deleted') ?></th>
            <td><?= $storeCategory->is_deleted ? __('Yes') : __('No'); ?></td>
         </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Stores') ?></h4>
        <?php if (!empty($storeCategory->stores)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Store Category Id') ?></th>
                <th><?= __('Name') ?></th>
                <th><?= __('Phone Number') ?></th>
                <th><?= __('Zip Code') ?></th>
                <th><?= __('Address 1') ?></th>
                <th><?= __('Address 2') ?></th>
                <th><?= __('Address 3') ?></th>
                <th><?= __('Opened') ?></th>
                <th><?= __('Closed') ?></th>
                <th><?= __('Is Deleted') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Updated') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($storeCategory->stores as $stores): ?>
            <tr>
                <td><?= h($stores->id) ?></td>
                <td><?= h($stores->store_category_id) ?></td>
                <td><?= h($stores->name) ?></td>
                <td><?= h($stores->phone_number) ?></td>
                <td><?= h($stores->zip_code) ?></td>
                <td><?= h($stores->address_1) ?></td>
                <td><?= h($stores->address_2) ?></td>
                <td><?= h($stores->address_3) ?></td>
                <td><?= h($stores->opened) ?></td>
                <td><?= h($stores->closed) ?></td>
                <td><?= h($stores->is_deleted) ?></td>
                <td><?= h($stores->created) ?></td>
                <td><?= h($stores->updated) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Stores', 'action' => 'view', $stores->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Stores', 'action' => 'edit', $stores->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Stores', 'action' => 'delete', $stores->id], ['confirm' => __('Are you sure you want to delete # {0}?', $stores->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
