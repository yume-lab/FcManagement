<?php $this->assign('title', 'パート一覧'); ?>

<div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2><i class="glyphicon glyphicon-user"></i> パート一覧</h2>
    </div>
    <div class="box-content">
        <div class="box col-md-12">
            <a class="btn btn-primary" href="/employees/add">
                <i class="glyphicon glyphicon-plus icon-white"></i>
                新規追加
            </a>
        </div>
        <table class="table table-striped table-bordered bootstrap-datatable responsive">
            <thead>
            <tr>
                <th>ID</th>
                <th>氏名</th>
                <th>メールアドレス</th>
                <th>所属店舗</th>
                <th>役割</th>
                <th>データ操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?= $this->Number->format($employee->id) ?></td>
                    <td><?= h($employee->last_name . ' ' . $employee->first_name) ?></td>
                    <td><?= h($employee->email) ?></td>
                    <td><?= h($employee->has('store') ? $employee->store->name : '') ?></td>
                    <td><?= h($employee->has('role') ? $employee->role->name : '') ?></td>
                    <td class="actions center">
                        <a class="btn btn-success btn-sm" href="/employees/view/<?= $employee->id ?>">
                            <i class="glyphicon glyphicon-zoom-in icon-white"></i>
                            詳細
                        </a>
                        <a class="btn btn-info btn-sm" href="/employees/edit/<?= $employee->id ?>">
                            <i class="glyphicon glyphicon-edit icon-white"></i>
                            更新
                        </a>
                        <a class="btn btn-danger btn-sm" href="/employees/delete/<?= $employee->id ?>">
                            <i class="glyphicon glyphicon-trash icon-white"></i>
                            削除
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="col-md-12">
            <div class="dataTables_info" id="DataTables_Table_0_info">
                <?= $this->Paginator->counter('{{pages}}ページ中 {{page}}ページ目 ') ?>
            </div>
        </div>
        <div class="col-md-12 center-block">
            <div class="dataTables_paginate paging_bootstrap pagination">
                <ul class="pagination">
                    <?= $this->Paginator->prev('< ' . __('前')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('次') . ' >') ?>
                </ul>
            </div>
        </div>
    </div>
</div>
