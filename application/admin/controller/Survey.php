<?php
/**
 * Created by PhpStorm.
 * User: Shicw
 * Date: 2019/3/4
 * Time: 15:27
 */
namespace app\admin\controller;
use app\admin\model\QuestionnaireModel;
use think\Controller;
use think\Db;

class Survey extends Controller
{
    /**
     * 问卷列表页面
     * @return mixed
     */
    public function index(){
        return $this->fetch();
    }

    /**
     * 获取问卷列表数据
     * @return array
     */
    public function getList(){
        if(!$this->request->isGet()){
            $this->error('请求失败');
        }
        $limit = $this->request->get('limit');
        $keyword = $this->request->param('keyword');//获取搜索关键词
        $conditions = [];//查询条件
        //关键字搜索
        if($keyword!=null){
            $conditions['q.id|q.title'] = ['like', "%$keyword%"];
        }

        $rows = Db::name('questionnaire q')
            ->field(['q.id','q.title','u.username','q.answer_count','q.status',
                'FROM_UNIXTIME(q.create_time,\'%Y-%m-%d %H:%i:%s\') create_time',
                'FROM_UNIXTIME(q.end_time,\'%Y-%m-%d %H:%i:%s\') end_time',
            ])
            ->join([['user u','u.id=q.user_id']])
            ->where($conditions)
            ->where(['q.delete_time'=>0])
            ->order('q.create_time desc')
            ->paginate($limit)
            ->toArray();
        if(count($rows['data'])>0){
            return [
                'code'=>0,
                'msg'=>'请求成功',
                'count'=>$rows['total'],
                'data'=>$rows['data']
            ];
        }else{
            return [
                'code' => 1,
                'msg' => '无数据',
                'count' => 0,
                'data' => ''
            ];
        }
    }
    /**
     * 结束问卷
     */
    public function stop(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $id = $this->request->post('id');
        $model = new QuestionnaireModel();
        $result = $model->stopQuestionnaire($id);
        if($result['code']===1){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 结束问卷
     */
    public function delete(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $id = $this->request->post('id');
        $model = new QuestionnaireModel();
        $result = $model->deleteQuestionnaire($id);
        if($result['code']===1){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 创建问卷页面
     */
    public function add(){
        $questionType = Db::name('question_type')->where('delete_time',0)->select();
        $this->assign('question_type',$questionType);
        $this->assign('question_type_str',json_encode($questionType));
        return $this->fetch();
    }

    /**
     * 创建问卷提交
     */
    public function addPost(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $data = $this->request->post()['data'];
        $model = new QuestionnaireModel();
        $result = $model->addQuestionnaire($data);
        if($result['code']===1){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 查看问卷详情
     */
    public function detail(){
        $id = $this->request->param('id');
        //获取问卷信息
        $questionnaire = Db::name('questionnaire')->where('id',$id)->find();
        //获取题目信息
        $questions = Db::name('question q')
            ->field(['q.id','q.questionnaire_id','q.content','q.question_type','qt.name type'])
            ->join('question_type qt','qt.id=q.question_type')
            ->where(['q.questionnaire_id'=>$id,'q.delete_time'=>0])
            ->select();

        foreach ($questions as $key1 => $q){
            //获取选项信息
            $options = Db::name('option')
                ->field(['id','question_id','option_index','content'])
                ->where(['question_id'=>$q['id'],'delete_time'=>0])
                ->select();
            $questions[$key1]['option'] = $options;

            foreach ($options as $key2 => $o){
                //获取各选项选择数量
                $answerCount = Db::name('answer')
                    ->where(['questionnaire_id'=>$id,'question_id'=>$q['id'],'option_index'=>$o['option_index']])
                    ->count();
                $questions[$key1]['option'][$key2]['answer_count'] = $answerCount;
                //计算各选项的选择比例
                $percent = round($answerCount / $questionnaire['answer_count'],2) * 100;
                $questions[$key1]['option'][$key2]['answer_percent'] = $percent;
            }
        }

        $this->assign(['questionnaire'=>$questionnaire,'questions'=>$questions]);
        return $this->fetch();
    }
}