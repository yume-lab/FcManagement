<style>
    .time-input {
        display: none;
    }
    .time-input input {
        height: 30px;
    }
    table th {
        text-align: center;
    }
</style>

<table class="table-bordered responsive">
    <thead>
    <tr>
        <th>年月</th>
        <th>氏名</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?= $showMonth ?></td>
        <td><?= trim($employee->last_name).' '.trim($employee->first_name); ?></td>
    </tr>
    </tbody>
</table>

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
        <th rowspan="2">日付</th>
        <th colspan="2">タイムカード</th>
        <th colspan="2">実時刻</th>
        <th colspan="4">当日集約</th>
        <th rowspan="2">操作</th>
    </tr>
    <tr>
        <th>出勤</th>
        <th>退勤</th>
        <th>始業</th>
        <th>終業</th>
        <th>総労働(時.分)</th>
        <th>休憩(分)</th>
        <th>実労働(時.分)</th>
        <th>時給(円)</th>
    </tr>
    </thead>
    <tbody>

        <?php
            $year = date('Y', strtotime($current));
            $month = date('m', strtotime($current));
            $end = date('t', strtotime($current));

            $canEdit = ($year.$month) == date('Ym');
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
                        <?= $this->TimeCard->editableTime($oneStepTimes, $data['start_time'], '/start'); ?>
                    </td>
                    <td>
                        <?= $this->TimeCard->editableTime($oneStepTimes, $data['end_time'], '/end'); ?>
                    </td>
                    <?php // TODO: ?>
                    <td>
                        <?= $this->TimeCard->editableTime($oneStepTimes, $data['start_time'], '/start'); ?>
                    </td>
                    <td>
                        <?= $this->TimeCard->editableTime($oneStepTimes, $data['end_time'], '/end'); ?>
                    </td>

                    <td>
                        <?= $this->TimeCard->formatHour($data['work_minute']); ?>
                    </td>

                    <?php // TODO: ?>
                    <td>
                        <?= 10; ?>
                    </td>

                    <td>
                        <?= $this->TimeCard->formatHour($data['real_minute']); ?>
                    </td>

                    <td>
                        <?= h($data['hour_pay']); ?>
                    </td>
                    <td>
                        <?php // 編集ボタンを押したら、更新部分が表示されます. ?>
                        <?php if ($canEdit) :?>
                            <span class="editable-button">
                                <a class="btn btn-primary btn-sm" href="#">
                                    <i class="glyphicon glyphicon-edit icon-white"></i>
                                    編集
                                </a>
                            </span>
                            <span class="editable-actions" style="display: none;">
                                <a class="btn btn-info btn-sm update" href="#">
                                    <i class="glyphicon glyphicon-edit icon-white"></i>
                                    更新
                                </a>
                                <a class="btn btn-danger btn-sm cancel" href="#">
                                    <i class="glyphicon glyphicon-trash icon-white"></i>
                                    取消
                                </a>
                            </span>
                        <?php endif; ?>
                    </td>
                <?php else: ?>
                    <td>
                        <?= $this->TimeCard->editableTime($times, '', '/start'); ?>
                    </td>
                    <td>
                        <?= $this->TimeCard->editableTime($times, '', '/end'); ?>
                    </td>
                    <?php // TODO: ?>
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
                        <?php // 追加ボタンを押したら、更新部分が表示されます. ?>
                        <?php if ($canEdit) :?>
                            <span class="editable-button">
                                <a class="btn btn-warning btn-sm" href="#">
                                    <i class="glyphicon glyphicon-plus icon-white"></i>
                                    追加
                                </a>
                            </span>
                            <span class="editable-actions" style="display: none;">
                                <a class="btn btn-success btn-sm update" href="#">
                                    <i class="glyphicon glyphicon-edit icon-white"></i>
                                    登録
                                </a>
                                <a class="btn btn-danger btn-sm cancel" href="#">
                                    <i class="glyphicon glyphicon-trash icon-white"></i>
                                    取消
                                </a>
                            </span>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>
