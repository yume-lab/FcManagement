<?php $this->assign('title', '確定シフト一覧'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-user"></i> 従業員一覧</h2>
            </div>
            <div class="box-content">
                <?= $this->Flash->render() ?>
                <table class="table table-striped table-bordered bootstrap-datatable responsive">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>年月</th>
                        <th>確認用ID</th>
                        <th>作成日</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($fixedShiftTables as $row): ?>
                        <tr>
                            <td><?= $this->Number->format($row->id) ?></td>
                            <td><?= h($row->target_ym) ?></td>
                            <td>
                                <a href="/fixed/view/<?= h($row->hash) ?>" target="_blank">
                                    <?= h($row->hash) ?>
                                </a>
                            </td>
                            <td><?= h(date('Y/m/d H:i:s', strtotime($row->created))) ?></td>
                            <td><?= h(empty($row->is_deleted) ? '有効' : '無効') ?></td>
                            <td class="actions center">
                                <a class="btn btn-primary btn-sm" target="_blank"
                                   href="/fixed/download/<?= $row->hash ?>">
                                    <i class="glyphicon glyphicon-download icon-white"></i>
                                    PDFダウンロード
                                </a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="col-md-12">
                    <div class="dataTables_info">
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
    </div>
</div>
