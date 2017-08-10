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
    /* 自动验证规则 */
    protected $_validate = array(
        array('name', 'require', '报修人不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('tel', 'require', '报修人电话不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );
}