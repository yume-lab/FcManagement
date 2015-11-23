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
                <?= $this->TimeCard->status($state); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>