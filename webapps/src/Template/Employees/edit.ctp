<?php $this->assign('title', 'パート編集'); ?>

<div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2><i class="glyphicon glyphicon-user"></i> パート編集</h2>
    </div>
    <div class="box-content">
        <?= $this->Form->create($employee) ?>
        <fieldset>
            <legend><?= __('Edit Employee') ?></legend>

            <table class="table table-striped table-bordered bootstrap-datatable responsive">
                <thead>
                <tr>
                    <th>ID</th>
                    <td>test</td>
                </tr>
                </thead>
            </table>


                    <?php
            echo $this->Form->input('role_id', ['options' => $roles]);
            echo $this->Form->input('store_id', ['options' => $stores]);
            echo $this->Form->input('email');
            echo $this->Form->input('phone_number');
            echo $this->Form->input('zip_code');
            echo $this->Form->input('address_1');
            echo $this->Form->input('address_2');
            echo $this->Form->input('address_3');
            echo $this->Form->input('first_name');
            echo $this->Form->input('last_name');
            echo $this->Form->input('name');
            echo $this->Form->input('is_deleted');
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
