<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27 0027
 * Time: 8:42
 */

namespace app\common\validate;


use think\Validate;

class Owner extends Validate
{
    protected $rule = [
        ['name', 'require', '用户名不能空'],
        ['room_num', 'require|length:1,80', '房号不能为空|房号长度不能超过30个字符'],
        ['tel', 'require', '电话不能为空'],
        ['identity', 'require', '身份证号不能为空']
    ];

}
