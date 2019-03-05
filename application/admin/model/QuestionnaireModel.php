<?php
/**
 * Created by PhpStorm.
 * User: Shicw
 * Date: 2019/3/4
 * Time: 15:59
 */
namespace app\admin\model;
use think\Db;
use think\Exception;
use think\Model;

class QuestionnaireModel extends Model
{

    /**
     * 结束问卷
     * @param $id
     * @return array
     */
    public function stopQuestionnaire($id){
        $result = $this->name('questionnaire')->where(['id'=>$id])->update(['status'=>0,'end_time'=>time()]);
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
        $result = $this->name('questionnaire')->where(['id'=>$id])->update(['delete_time'=>time()]);
        if($result){
            return ['code' => 1, 'msg' => '问卷删除成功'];

        }else{
            return ['code' => 0, 'msg' => '问卷删除失败'];
        }
    }

    /**
     * 创建问卷
     * @param $data
     * @return array
     */
    public function addQuestionnaire($data){
        $title = $data['title'];//问卷标题
        unset($data['title']);
        $questionArray = [];
        //循环所有参数,将各个题目的参数分类到数组中
        foreach ($data as $key => $d){
            $questionNumber = explode('-',$key)[1] - 1;
            if(strpos($key,'-o-')!==false){
                $optionNumber = explode('-',$key)[3] - 1;
                $key = explode('-',$key);
                $key = $key[count($key)-1];
                $questionArray[$questionNumber]['option'][$optionNumber][$key] = $d;
            }else{
                $key = explode('-',$key);
                $key = $key[count($key)-1];
                $questionArray[$questionNumber]['info'][$key] = $d;
            }
        }

        //开启事务处理,将问卷信息写入数据库
        $this->startTrans();
        try{
            $time = time();
            $id = $this->name('questionnaire')->insertGetId([
                'title'=>$title,
                'user_id'=>getAdminId(),
                'status'=>1,
                'create_time'=>$time
            ]);
            if(!$id){
                throw new Exception("问卷创建失败");
            }
            //循环每道题目
            foreach ($questionArray as $q){
                $question = $this->name('question')->insertGetId([
                    'questionnaire_id' => $id,
                    'content' => $q['info']['content'],
                    'question_type' => $q['info']['type'],
                    'create_time' => $time
                ]);
                if(!$question){
                    throw new Exception("问卷创建失败");
                }
                //循环每道题的每个选项设置
                foreach ($q['option'] as $o){
                    $option = $this->name('option')->insert([
                        'question_id' => $question,
                        'option_index' => $o['type'],
                        'content' => $o['content'],
                        'create_time' => $time
                    ]);
                    if(!$option){
                        throw new Exception("问卷创建失败");
                    }
                }
            }
            $this->commit();// 提交事务
        }catch(Exception $e){
            $this->rollback();// 回滚事务
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
        return ['code' => 1, 'msg' => '问卷创建成功'];

    }
}