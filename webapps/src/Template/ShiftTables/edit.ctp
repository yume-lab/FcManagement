<?php $this->assign('title', 'シフト作成'); ?>

<?= $this->element('Assets/calendar'); ?>
<?= $this->Html->css('ShiftTables/timeline.css') ?>
<?= $this->Html->css('ShiftTables/edit.css') ?>
<?= $this->element('Notice/show_top', ['message' => 'シフトを一時保存しました']); ?>

<div class="row">
    <div class="col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-calendar"></i> シフト表</h2>
            </div>

            <div id="calandar-wrapper" class="box-content">
                <?= $this->Flash->render() ?>
                <div class="col-md-12">
                    <div class="alert alert-info">
                        「月合計時間」は、総労働 (実働時間)の順番で記載されています。<br/>
                        例： 8 (7.5)
                    </div>
                </div>

                <div class="col-md-12 text-right">
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
<input type="hidden" id="interval" value="<?= $interval ?>" />
<?php // JSON文字列が格納されるため、valueではシングルクォートで囲む ?>
<input type="hidden" id="break-range" value='<?= $break ?>' />
<input type="hidden" id="resources" value='<?= $resources ?>' />

<form id="fixed_form" name="fixed_form" method="post" action="/shift/fixed">
    <input id="fixed_year" name="fixed_year" type="hidden" value="" />
    <input id="fixed_month" name="fixed_month" type="hidden" value="" />
    <input id="fixed_shift" name="fixed_shift" type="hidden" value="" />
</form>

<?= $this->element('Popover/ShiftTables/input', ['employees' => json_decode($resources, true)]); ?>

<?= $this->Html->script('ShiftTables/edit.js') ?>
