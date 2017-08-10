<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-08-10
 * Time: 14:38
 */

namespace Admin\Model;


use Think\Model;

class MenageModel extends Model
{
    protected $_validate = array(
        array('name', 'require', '姓名不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('tel', 'require', '电话不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('address', 'require', '地址不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('problem', 'require', '保修问题不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );

}
