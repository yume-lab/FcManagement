<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * TimeCard component
 * Class TimeCardComponent
 * タイムカードまわりの共通処理です.
 */
class TimeCardComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * 総労働時間を計算します.
     * @param $data string 勤怠1日分のJSONデータ
     *  '/in', '/out'要素が必須です.
     * @see \App\Model\Table\TimeCardsTable
     * @return string 総労働時間
     */
    public function getAll($data) {
        if (isset($data['/in']) && isset($data['/out'])) {
            return $this->format($this->diff($data['/in'], $data['/out']));
        }
        return 0;
    }

    /**
     * 休憩時間を計算します.
     * @param $data string 勤怠1日分のJSONデータ
     *  '/break_in', '/break_out'要素が必須です.
     * @see \App\Model\Table\TimeCardsTable
     * @return string 休憩時間
     */
    public function getBreak($data) {
        if ($this->hasBreak($data)) {
            return $this->format($this->diff($data['/break_in'], $data['/break_out']));
        }
        return 0;
    }

    /**
     * 実働時間を計算します.
     * @param $data string 勤怠1日分のJSONデータ
     *  '/in', '/out', '/break_in', '/break_out'要素が必須です.
     * @see \App\Model\Table\TimeCardsTable
     * @return string 実働時間
     */
    public function getReal($data) {
        $all = $this->diff($data['/in'], $data['/out']);
        $break = $this->hasBreak($data) ? $this->diff($data['/break_in'], $data['/break_out']) : 0;
        return $this->format($all - $break);
    }

    /**
     * 時間の差を計算します.
     * @param $start string 開始時間
     * @param $end string 終了時間
     * @return int 時間差
     */
    public function diff($start, $end) {
        return strtotime($end) - strtotime($start);
    }

    /**
     * 休憩の有無を反省します.
     * @param $data array その日の打刻データ
     * @return bool 休憩があればtrue
     */
    private function hasBreak($data) {
        return (isset($data['/break_in']) && isset($data['/break_out']));
    }

    /**
     * 返却する時間のフォーマットを行います.
     * @param $time int 時間
     * @return string H:i形式の時間表記
     */
    private function format($time) {
        return date('H:i', $time);
    }
}
