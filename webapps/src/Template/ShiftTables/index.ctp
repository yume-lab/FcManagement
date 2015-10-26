<?php $this->assign('title', 'シフト作成'); ?>

<?php // TODO: 暫定でここに ?>
<script src="http://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>


<div class="row">
    <div class="col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-calendar"></i> シフト表</h2>
            </div>

            <div class="box-content">
                <div class="box col-md-12">
                    <?= $this->Flash->render() ?>
                    <a id="save" class="btn btn-info" href="#">
                        <i class="glyphicon glyphicon-plus icon-white"></i>
                        一時保存
                    </a>
                </div>

                <div id="shift-calendar"></div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="opened" value="<?= $opened ?>" />
<input type="hidden" id="closed" value="<?= $closed ?>" />
<?= $this->element('Popover/ShiftTables/input', ['employees' => $employees]); ?>

<?= $this->element('Script/ShiftTables/index'); ?>
