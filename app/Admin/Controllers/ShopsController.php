<?php

namespace App\Admin\Controllers;

use App\Models\Shop;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ShopsController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('门店列表')
            ->body($this->grid());
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
            ->header('门店列表')
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
        return $content
            ->header('门店列表')
            ->description('新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Shop);

        $grid->model()->orderBy('sort','asc');

        $grid->name('门店名称');
        $grid->address('地址');
        $grid->phone('联系电话');
        $grid->sort('排序');
        $grid->is_show('是否展示')->switch();
        $grid->disableExport();
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('name', '分公司名称');
        });
        $grid->actions(function ($actions) {
                $actions->disableView();
            });
        $grid->disableRowSelector();

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
        $show = new Show(Shop::findOrFail($id));

        $show->id('Id');
        $show->name('Name');
        $show->image('Image');
        $show->banner('Banner');
        $show->address('Address');
        $show->phone('Phone');
        $show->content('Content');
        $show->sort('Sort');
        $show->is_show('Is show');
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
        $form = new Form(new Shop);

        $form->text('name', '门店名称')->rules('required',[
                    'required'=>'不能为空'
            ]);
        $form->image('image', '展示图')->rules('required',[
                    'required'=>'不能为空'
            ]);
         $form->multipleImage('banner', '详情轮播图')->removable();
        $form->text('address', '地址')->rules('required',[
                    'required'=>'不能为空'
            ]);
        $form->text('phone', '联系电话')->rules('required',[
                    'required'=>'不能为空'
            ]);
        $form->editor('content', '图文介绍')->rules('required',[
                    'required'=>'不能为空'
            ]);
        $form->number('sort','排序')->default(50);
        $form->switch('is_show','是否展示')->default(0);
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
}
