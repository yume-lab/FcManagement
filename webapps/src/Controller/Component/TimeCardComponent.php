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
            return ($this->diff($data['/in'], $data['/out']) / (60*60));
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
        if (isset($data['/break_in']) && isset($data['/break_out'])) {
            return ($this->diff($data['/break_in'], $data['/break_out']) / (60*60));
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
        return $this->getAll($data) - $this->getBreak($data);
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
}
