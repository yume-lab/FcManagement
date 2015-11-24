<table id="employee-list" class="table responsive">
    <thead>
    <tr>
        <th>氏名</th>
        <th>状態</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record) :?>
        <?php
            $times = $record['EmployeeTimeCards'];
            $stateId = $times['time_card_state_id'];
            $hasState = !empty($stateId);
            $state = $hasState ? $states[$stateId] : [];
        ?>
        <tr class="employee-row"
            data-id="<?= $record['id'] ?>"
            data-path="<?= empty($state) ? '' : $state['path']; ?>">
            <td>
                <h4 class="name">
                    <?= $record['last_name'].' '.$record['first_name']; ?>
                </h4>
            </td>
            <td class="center">
                <?= $this->TimeCard->status($state); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>