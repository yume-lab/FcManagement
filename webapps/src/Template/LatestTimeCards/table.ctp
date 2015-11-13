
<?php
// TODO: ヘルパーで
function getClass($alias) {
    $class = 'default';
    switch ($alias) {
        case '/in':
            $class = 'success';
            break;
        case '/out':
            $class = 'danger';
            break;
        case '/break_in':
            $class = 'primary';
            break;
        case '/break_out':
            $class = 'warning';
            break;
    }
    return $class;
}

// TODO: ヘルパーで
function getIcon($alias) {
    $icon = '';
    switch ($alias) {
        case '/in':
            $icon = 'glyphicon-arrow-left';
            break;
        case '/out':
            $icon = 'glyphicon-arrow-right';
            break;
        case '/break_in':
            $icon = 'glyphicon-chevron-left';
            break;
        case '/break_out':
            $icon = 'glyphicon-chevron-right';
            break;
    }
    return $icon;
}
?>


<table id="employee-list" class="table responsive">
    <thead>
    <tr>
        <th>氏名</th>
        <th>最終出勤日</th>
        <th>状態</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($employees as $employee) :?>
        <?php
            $info = isset($data[$employee->id]) ? $data[$employee->id] : [];
            $hasData = !empty($info);
            $state = $hasData ? $states[$info['time_card_state_id']] : [];
        ?>
        <tr class="employee-row"
            data-id="<?= $employee->id ?>"
            data-state="<?= empty($state) ? '' : $state['alias']; ?>">
            <td>
                <h4 class="name">
                    <?= $employee->last_name.' '.$employee->first_name; ?>
                </h4>
            </td>
            <td class="center">
                <h4>
                    <?= $hasData ? date('Y/m/d', strtotime($info['time'])) : ''; ?>
                </h4>
            </td>
            <td class="center">
                <?php // TODO このあたり全体はヘルパーにする ?>
                <?php if (!empty($state)): ?>
                    <h4>
                        <span class="label label-<?= getClass($state['alias']); ?>">
                            <?= trim($state['label']); ?>
                        </span>
                    </h4>
                <?php else: ?>
                    <h4>
                        <span class="label label-default">
                            未出勤
                        </span>
                    </h4>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>