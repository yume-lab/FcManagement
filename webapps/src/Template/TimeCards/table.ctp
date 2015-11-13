<table class="table table-bordered responsive">
    <thead>
    <tr>
        <th>日付</th>
        <th>開始時間</th>
        <th>終了時間</th>
        <th>休憩開始</th>
        <th>休憩終了</th>
        <th>総労働時間</th>
        <th>実時間</th>
    </tr>
    </thead>
    <tbody>

        <?php
            $now = date('Y-m-d');
            $year = date('Y', strtotime($now));
            $month = date('m', strtotime($now));
            $end = date('t', strtotime($now));
        ?>

        <?php for ($day = 1; $day <= $end; $day++): ?>
            <?php
                $timestamp = mktime(0, 0, 0, $month, $day, $year);
                $dayOfWeek = date('w', $timestamp);
            ?>
            <tr class="<?= $this->TimeCard->dayClass($dayOfWeek); ?>">
                <td><?= $day.$this->TimeCard->dayOfWeekString($dayOfWeek); ?></td>
                <td>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>