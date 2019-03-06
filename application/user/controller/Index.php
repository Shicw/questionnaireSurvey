<?php
namespace app\user\controller;

use think\Controller;
use think\Db;
use think\Exception;

class Index extends Controller
{
    /**
     * 问卷作答页面
     */
    public function index(){
        $id = $this->request->param('id');
        //获取问卷信息
        $questionnaire = Db::name('questionnaire')->where('id',$id)->find();
        //获取题目信息
        $questions = Db::name('question q')
            ->field(['q.id','q.questionnaire_id','q.content','q.question_type','qt.name type'])
            ->join('question_type qt','qt.id=q.question_type')
            ->where(['q.questionnaire_id'=>$id,'q.delete_time'=>0])
            ->select();
        //获取选项信息
        foreach ($questions as $key => $q){
            $options = Db::name('option')
                ->field(['id','question_id','option_index','content'])
                ->where(['question_id'=>$q['id'],'delete_time'=>0])
                ->select();
            $questions[$key]['option'] = $options;
        }

        $this->assign(['questionnaire'=>$questionnaire,'questions'=>$questions]);
        return $this->fetch();
    }
    /**
     * 问卷作答提交
     */
    public function post(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $data = $this->request->post();
        if(count($data)===1){
            $this->error('不能提交空白问卷');
        }
        $ip = $this->request->ip();//ip地址
        $questionnaireId = $data['questionnaireId'];//问卷id
        $answer = $data['data'];//回答内容

        //开启事务处理,将作答信息写入数据库
        Db::startTrans();
        try{
        //循环每一个回答
        foreach ($answer as $key => $a){
            //获取当前的题号
            $questionId = explode('-',$key)[1];
            $result = Db::name('answer')->insert([
                'questionnaire_id' => $questionnaireId,
                'question_id' => $questionId,
                'option_index' => $a,
                'create_time' => time(),
                'ip' => $ip
            ]);
            if(!$result){
                throw new Exception("问卷提交失败,请重试");
            }
        }
            Db::commit();// 提交事务
        }catch(Exception $e){
            Db::rollback();// 回滚事务
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
        return ['code' => 1, 'msg' =>'问卷提交成功!'];
    }
}
