<?php
    /**
     * 時間の差を返します.
     * @param $start
     * @param $end
     * @return 時間の差(1時間単位)
     */
    function calcTimeDiff($start, $end) {
        return (strtotime($end) - strtotime($start)) / (60*60);
    }
?>

<div>
    <?= $employee->last_name.' '.$employee->first_name ?>さん
</div>

<table class="table table-bordered responsive">
    <thead>
    <tr>
        <th>日付</th>
        <th>開始時間</th>
        <th>終了時間</th>
        <th>休憩開始</th>
        <th>休憩終了</th>
        <th>総労働時間</th>
        <th>実労働時間</th>
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
                $key = 'day-'.(($day < 10) ? '0'.$day : $day);
                $hasData = isset($matrix[$key]);
            ?>
            <tr class="<?= $this->TimeCard->dayClass($dayOfWeek); ?>">
                <td><?= $day.$this->TimeCard->dayOfWeekString($dayOfWeek); ?></td>
                <?php if ($hasData): ?>
                    <?php
                        $data = $matrix[$key];
                        $allTime = round(calcTimeDiff($data['/in'], $data['/out']), 1);

                        $hasBreak = isset($data['/break_in']) && isset($data['/break_out']);
                        $breakAll = $hasBreak
                            ? round(calcTimeDiff($data['/break_in'], $data['/break_out']), 1)
                            : 0;
                    ?>
                    <td><?= $data['/in'] ?></td>
                    <td><?= $data['/out'] ?></td>
                    <td><?= $hasBreak ? $data['/break_in'] : '' ?></td>
                    <td><?= $hasBreak ? $data['/break_out'] : '' ?></td>
                    <td><?= $allTime ?></td>
                    <td><?= $allTime - $breakAll ?></td>
                <?php else: ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                <?php endif; ?>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>