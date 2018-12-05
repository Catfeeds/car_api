<?php

namespace App\Admin\Controllers;

use App\Models\Logistics;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class LogisticsController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content,Request $request)
    {
        $order_id=$request->order_id;
        \Cache::forever('order_id', $order_id);
        return $content
            ->header('物流信息')
            ->description('列表')
            ->body($this->grid($order_id));
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('物流信息')
            ->description('修改')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        //$order_id=$request->order_id;
        return $content
            ->header('物流信息')
            ->description('新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid($order_id)
    {
        $grid = new Grid(new Logistics);
        $grid->model()->where('order_id',$order_id);
        $grid->content('物流信息');
        $grid->created_at('创建时间');
        $grid->disableExport();
        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->actions(function ($actions) {
            $actions->disableView();
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Logistics::findOrFail($id));

        $show->id('Id');
        $show->order_id('Order id');
        $show->content('Content');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Logistics);

        $form->text('content', '物流信息');
        $order_id=\Cache::get('order_id', 0);
        $form->hidden('order_id')->value($order_id);

        $form->tools(function (Form\Tools $tools) {


            // 去掉`删除`按钮
            $tools->disableDelete();

            // 去掉`查看`按钮
            $tools->disableView();
        });
        $form->footer(function ($footer) {

            // 去掉`重置`按钮
            $footer->disableReset();

            // 去掉`查看`checkbox
            $footer->disableViewCheck();

            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();
            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();

        });


        return $form;
    }

    // public function delete(Request $request)
    // {

    //     $id=$request->id;
    //     $data=Logistics::where('id',$id)->delete();
    //     if($data){
    //         return response()->json([
    //             'status'  => true,
    //             'message' => trans('删除成功'),
    //         ]);
    //     }else{
    //         return response()->json([
    //             'status'  => false,
    //             'message' => trans('删除失败'),
    //         ]);
    //     }
    // }
}
