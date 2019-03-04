<?php
/**
 * Created by PhpStorm.
 * User: Shicw
 * Date: 2019/3/4
 * Time: 15:59
 */
namespace app\admin\model;
use think\Db;
use think\Model;

class QuestionnaireModel extends Model
{
    protected $table = 'questionnaire';

    /**
     * 结束问卷
     * @param $id
     * @return array
     */
    public function stopQuestionnaire($id){
        $result = $this->where(['id'=>$id])->update(['status'=>0,'end_time'=>time()]);
        if($result){
            return ['code' => 1, 'msg' => '问卷结束成功'];

        }else{
            return ['code' => 0, 'msg' => '问卷结束失败'];
        }
    }

    /**
     * 删除问卷
     * @param $id
     * @return array
     */
    public function deleteQuestionnaire($id){
        $result = $this->where(['id'=>$id])->update(['delete_time'=>time()]);
        if($result){
            return ['code' => 1, 'msg' => '问卷删除成功'];

        }else{
            return ['code' => 0, 'msg' => '问卷删除失败'];
        }
    }
}