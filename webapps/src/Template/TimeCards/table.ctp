<h3 class="center-block">
    <?= $showMonth ?>
</h3>
<ul class="pagination col-md-12">
    <li id="prev" style="float: left;">
        <a href="#" data-target="<?= $prev ?>">&lt; 前月</a>
    </li>
    <li id="next" style="float: right;">
        <a href="#" data-target="<?= $next ?>">次月 &gt;</a>
    </li>
</ul>

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
            $year = date('Y', strtotime($current));
            $month = date('m', strtotime($current));
            $end = date('t', strtotime($current));
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
                        $hasBreak = !empty($data['/break_all']);
                    ?>
                    <td><?= $data['/in'] ?></td>
                    <td><?= $data['/out'] ?></td>
                    <td><?= $hasBreak ? $data['/break_in'] : '' ?></td>
                    <td><?= $hasBreak ? $data['/break_out'] : '' ?></td>
                    <td><?= $data['/all'] ?></td>
                    <td><?= $data['/real'] ?></td>
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
