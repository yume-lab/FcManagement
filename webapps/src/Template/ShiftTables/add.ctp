<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Shift Tables'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="shiftTables form large-9 medium-8 columns content">
    <?= $this->Form->create($shiftTable) ?>
    <fieldset>
        <legend><?= __('Add Shift Table') ?></legend>
        <?php
            echo $this->Form->input('store_id', ['options' => $stores]);
            echo $this->Form->input('year');
            echo $this->Form->input('month');
            echo $this->Form->input('body');
            echo $this->Form->input('is_deleted');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
