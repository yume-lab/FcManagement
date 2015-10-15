<?php $this->assign('title', 'シフト作成'); ?>

<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Shift Table'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="shiftTables index large-9 medium-8 columns content">
    <h3><?= __('Shift Tables') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('store_id') ?></th>
                <th><?= $this->Paginator->sort('year') ?></th>
                <th><?= $this->Paginator->sort('month') ?></th>
                <th><?= $this->Paginator->sort('is_deleted') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('updated') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($shiftTables as $shiftTable): ?>
            <tr>
                <td><?= $this->Number->format($shiftTable->id) ?></td>
                <td><?= $shiftTable->has('store') ? $this->Html->link($shiftTable->store->name, ['controller' => 'Stores', 'action' => 'view', $shiftTable->store->id]) : '' ?></td>
                <td><?= $this->Number->format($shiftTable->year) ?></td>
                <td><?= $this->Number->format($shiftTable->month) ?></td>
                <td><?= h($shiftTable->is_deleted) ?></td>
                <td><?= h($shiftTable->created) ?></td>
                <td><?= h($shiftTable->updated) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $shiftTable->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $shiftTable->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $shiftTable->id], ['confirm' => __('Are you sure you want to delete # {0}?', $shiftTable->id)]) ?>
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
