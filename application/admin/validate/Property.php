<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27 0027
 * Time: 8:42
 */

namespace app\admin\validate;


use think\Validate;

class Property extends Validate
{
    protected $rule = [
        ['name', 'require', '用户名不能空'],
        ['title', 'require|length:1,80', '标题不能为空|标题长度不能超过80个字符'],
        ['tel', 'require', '电话不能为空']
    ];
}
