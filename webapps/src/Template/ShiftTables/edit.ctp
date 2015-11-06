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
                <?= $this->Flash->render() ?>
                <div class="box col-md-6">
                </div>
                <div class="box col-md-6" style="text-align: right;">
                    <a id="save" class="btn btn-info" href="#">
                        <i class="glyphicon glyphicon-pencil icon-white"></i>
                        一時保存
                    </a>
                    <a id="fixed" class="btn btn-success" href="#">
                        <i class="glyphicon glyphicon-ok icon-white"></i>
                        シフト確定
                    </a>
                </div>

                <div id="shift-calendar"></div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="opened" value="<?= $opened ?>" />
<input type="hidden" id="closed" value="<?= $closed ?>" />

<form id="fixed_form" name="fixed_form" method="post" action="/shift/fixed">
    <input id="fixed_year" name="fixed_year" type="hidden" value="" />
    <input id="fixed_month" name="fixed_month" type="hidden" value="" />
    <input id="fixed_shift" name="fixed_shift" type="hidden" value="" />
</form>

<?= $this->element('Popover/ShiftTables/input', ['employees' => $employees]); ?>

<?= $this->element('Script/ShiftTables/edit'); ?>
