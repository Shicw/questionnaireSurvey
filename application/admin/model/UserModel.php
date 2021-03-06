<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2019/3/4
 * Time: 17:00
 */
namespace app\admin\model;
use think\Db;
use think\Model;

class UserModel extends Model
{
    protected $table = 'user';
    /**
     * 验证用户名密码
     * @param $username
     * @param $password
     * @param $type
     * @return array
     */
    public function doLogin($username, $password, $type){
        $find = $this->field(['id', 'username'])
            ->where(['username' => $username, 'password' => md5($password)])
            ->find();
        if ($find) {
            $find = $find->toArray();
            session($type, $find);//保存session
            //更新最后登录时间
            $this->where('id', $find['id'])->update(['last_login_time' => time()]);
            return ['code' => 1, 'msg' => '登录成功', 'data' => $find];
        } else {
            return ['code' => 0, 'msg' => '用户名或密码错误'];
        }
    }

    /**
     * 修改密码
     * @param $username
     * @param $oldPassword
     * @param $newPassword
     * @return array
     */
    public function changePassword($username, $oldPassword, $newPassword){
        //判断旧密码
        $find = $this->where(['username'=>$username,'password'=>md5($oldPassword)])->find();
        if(!$find){
            return ['code' => 0, 'msg' => '旧密码错误'];
        }
        $update = $this->where('id',$find['id'])->update(['password'=>md5($newPassword)]);
        if ($update) {
            return ['code' => 1, 'msg' => '修改成功'];
        } else {
            return ['code' => 0, 'msg' => '修改失败'];
        }
    }
}