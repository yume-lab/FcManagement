<style>
    .time-input {
        display: none;
    }
    .time-input input {
        height: 30px;
    }
</style>

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
        <th>操作</th>
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
                $ymd = date('Ymd', $timestamp);
                $hasData = isset($records[$ymd]);
            ?>
            <tr class="time-row <?= $this->TimeCard->dayClass($dayOfWeek); ?>"
                style="height: 47px;"
                data-ymd="<?= $ymd; ?>">
                <td><?= $day.$this->TimeCard->dayOfWeekString($dayOfWeek); ?></td>
                <?php if ($hasData): ?>
                    <?php
                        $data = $records[$ymd];
                    ?>
                    <td>
                        <?= $this->TimeCard->editableTime($times, $data['start_time'], '/start'); ?>
                    </td>
                    <td>
                        <?= $this->TimeCard->editableTime($times, $data['end_time'], '/end'); ?>
                    </td>
                    <td>
                        <?= $this->TimeCard->editableTime(
                            $times, $data['break_start_time'], '/break/start'); ?>
                    </td>
                    <td>
                        <?= $this->TimeCard->editableTime(
                            $times, $data['break_end_time'], '/break/end'); ?>
                    </td>
                    <td>
                        <?= $this->TimeCard->formatHour($data['work_minute']); ?>
                    </td>
                    <td>
                        <?= $this->TimeCard->formatHour($data['real_minute']); ?>
                    </td>
                    <td>
                        <?php // 編集ボタンを押したら、更新部分が表示されます. ?>
                        <span class="editable-button">
                            <a class="btn btn-primary btn-sm" href="#">
                                <i class="glyphicon glyphicon-edit icon-white"></i>
                            </a>
                        </span>
                        <span class="editable-actions" style="display: none;">
                            <a class="btn btn-success btn-sm update" href="#">
                                <i class="glyphicon glyphicon-edit icon-white"></i>
                            </a>
                            <a class="btn btn-danger btn-sm cancel" href="#">
                                <i class="glyphicon glyphicon-trash icon-white"></i>
                            </a>
                        </span>
                    </td>
                <?php else: ?>
                    <td>
                        <?= $this->TimeCard->editableTime($times, '', '/start'); ?>
                    </td>
                    <td>
                        <?= $this->TimeCard->editableTime($times, '', '/end'); ?>
                    </td>
                    <td>
                        <?= $this->TimeCard->editableTime($times, '', '/break/start'); ?>
                    </td>
                    <td>
                        <?= $this->TimeCard->editableTime($times, '', '/break/end'); ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                        <span class="editable-button">
                            <a class="btn btn-info btn-sm" href="#">
                                <i class="glyphicon glyphicon-plus icon-white"></i>
                            </a>
                        </span>
                        <span class="editable-actions" style="display: none;">
                            <a class="btn btn-info btn-sm update" href="#">
                                <i class="glyphicon glyphicon-edit icon-white"></i>
                            </a>
                            <a class="btn btn-danger btn-sm cancel" href="#">
                                <i class="glyphicon glyphicon-trash icon-white"></i>
                            </a>
                        </span>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>
