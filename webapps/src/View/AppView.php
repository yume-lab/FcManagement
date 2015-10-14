<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;

/**
 * Application View
 *
 * Your application’s default view class
 *
 * @link http://book.cakephp.org/3.0/en/views.html#the-app-view
 */
class AppView extends View
{

    /**
     * 初期処理.
     * Formヘルパーで表示するデフォルトのスタイルを変更します.
     * 
     * @return void
     */
    public function initialize()
    {

        $customTemplates = [
//            'label' => '<label class="control-label"{{attrs}}>{{text}}</label>',
            'label' => '<h4{{attrs}}>{{text}}</h4>',
            'input' => '<input class="form-control" type="{{type}}" name="{{name}}"{{attrs}} />',
            'select' => '<select class="form-control" name="{{name}}"{{attrs}}>{{content}}</select>',
            'inputContainer' => '<div class="form-group">{{content}}</div>',
        ];
        $this->Form->templates($customTemplates);
    }
}
