<?php
namespace App\View\Helper;

use Cake\View\Helper;

/**
 * Class TimeCardHelper
 * タイムカード周りで使用するヘルパー
 *
 * @package App\View\Helper
 */
class TimeCardHelper extends Helper {

    /**
     * 曜日定義.
     * @var array
     */
    private static $DAY_OF_WEEK_MAP = [
        '日', // 0
        '月', // 1
        '火', // 2
        '水', // 3
        '木', // 4
        '金', // 5
        '土'  // 6
    ];

    /**
     * 勤務表の曜日別classを取得します.
     * @param $dayOfWeek
     * @return string
     */
    public function dayClass($dayOfWeek) {
        $class = '';
        switch ($dayOfWeek) {
            case 0:
                $class = 'sunday';
                break;
            case 6:
                $class = 'saturday';
                break;
        }
        return $class;
    }

    /**
     * 曜日を表示します.
     * @param $dayOfWeek
     * @return string
     */
    public function dayOfWeekString($dayOfWeek) {

        return sprintf('(%s)', self::$DAY_OF_WEEK_MAP[$dayOfWeek]);
    }

    /**
     * 勤怠一覧の編集可能時間エリア
     * @param $times array 選択可能時間 Componentで構築したもの.
     * @param $data array 勤怠データ
     * @param $alias string 状態のエイリアス
     * @return string タグ
     */
    public function editableTime($times, $data, $alias) {
        $tagFormat = '
            <span class="time-label">
                %s
            </span>
            <span class="time-input">
                <select name="%s" class="form-control" style="height: 30px; width: auto;">
                    %s
                </select>
            </span>
        ';
        $value = $data[$alias];
        $values = [$value, $alias, $this->buildTimeOptions($times, $value)];
        return vsprintf($tagFormat, $values);
    }

    /**
     * 選択時間のoption要素を構築します.
     * @param $times array 時間配列
     * @param string $value 現在の値. options
     * @return string <option>タグ
     */
    public function buildTimeOptions($times, $value = '') {
        $tagFormat = '<option value="%s" %s>%s</option>';
        $optionTag = '';
        foreach ($times as $time) {
            $selected = ($time == $value) ? 'selected' : '';
            $optionTag .= vsprintf($tagFormat, [$time, $selected, $time]);
        }
        return $optionTag;
    }

    /**
     * 勤怠打刻のステータスラベルを出力します.
     * @param $state 勤怠状態オブジェクト
     * @return string
     */
    public function status($state) {
        $tagFormat = '
            <h4>
                <span class="label label-%s">
                    %s
                </span>
            </h4>
        ';

        $values = empty($state)
            ? ['default', '未出勤']
            : [$this->type($state['alias']), trim($state['label'])];

        return vsprintf($tagFormat, $values);
    }

    /**
     * ステータス状況別のボタンを出力します.
     *
     * @see TimeCardStatesTable
     * @param $state
     * @return string
     */
    public function button($state) {
        $tagFormat = '
            <button class="btn btn-lg col-md-4 center-block action-button btn-%s" data-alias="%s">
                <i class="glyphicon %s"></i> %s
            </button>
        ';

        $alias = $state['alias'];
        $values = [
            $this->type($alias),
            $alias,
            $this->icon($alias),
            trim(trim($state['name']))
        ];

        return vsprintf($tagFormat, $values);
    }

    /**
     * ボタンの種別から、classに指定する色種別を取得します.
     *
     * @see TimeCardStatesTable
     * @param $alias
     * @return string
     */
    public function type($alias) {
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

    /**
     * ボタン種別からアイコンのclassを取得します.
     *
     * @see TimeCardStatesTable
     * @param $alias
     * @return string
     */
    public function icon($alias) {
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
}