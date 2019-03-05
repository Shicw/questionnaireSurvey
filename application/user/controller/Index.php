<?php
namespace app\user\controller;

use think\Controller;
use think\Db;

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
}
