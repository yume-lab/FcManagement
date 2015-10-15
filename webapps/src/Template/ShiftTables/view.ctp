<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Shift Table'), ['action' => 'edit', $shiftTable->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Shift Table'), ['action' => 'delete', $shiftTable->id], ['confirm' => __('Are you sure you want to delete # {0}?', $shiftTable->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Shift Tables'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Shift Table'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="shiftTables view large-9 medium-8 columns content">
    <h3><?= h($shiftTable->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Store') ?></th>
            <td><?= $shiftTable->has('store') ? $this->Html->link($shiftTable->store->name, ['controller' => 'Stores', 'action' => 'view', $shiftTable->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($shiftTable->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Year') ?></th>
            <td><?= $this->Number->format($shiftTable->year) ?></td>
        </tr>
        <tr>
            <th><?= __('Month') ?></th>
            <td><?= $this->Number->format($shiftTable->month) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($shiftTable->created) ?></tr>
        </tr>
        <tr>
            <th><?= __('Updated') ?></th>
            <td><?= h($shiftTable->updated) ?></tr>
        </tr>
        <tr>
            <th><?= __('Is Deleted') ?></th>
            <td><?= $shiftTable->is_deleted ? __('Yes') : __('No'); ?></td>
         </tr>
    </table>
    <div class="row">
        <h4><?= __('Body') ?></h4>
        <?= $this->Text->autoParagraph(h($shiftTable->body)); ?>
    </div>
</div>
