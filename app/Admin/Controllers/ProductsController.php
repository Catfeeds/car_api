<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use App\Models\classify;
use App\Models\ProductSku;
use Illuminate\Support\MessageBag;

class ProductsController extends Controller
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
            ->header('商品列表')
            ->description('.')
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
            ->header('商品列表')
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
            ->header('商品列表')
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
        $grid = new Grid(new Product);
        $grid->model()->orderBy('classify_id')->orderBy('sort');
        $grid->title('商品名称');
        $grid->classify()->title('所属分类');
        $grid->sort('排序');
        $grid->is_sale('是否上架')->display(function($is_sale){
            if($is_sale){
                return '是';
            }else{
                return '否';
            }
        });
        $grid->is_hot('是否热卖')->display(function($is_hot){
            if($is_hot){
                return '是';
            }else{
                return '否';
            }
        });
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
        });
        $grid->disableExport();
        $grid->tools(function ($tools) {
            // 禁用批量删除按钮
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('title', '商品名称');
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
        $show = new Show(Product::findOrFail($id));

        $show->id('Id');
        $show->title('Title');
        $show->classify_id('Classify id');
        $show->image('Image');
        $show->banner('Banner');
        $show->description('Description');
        $show->keywords('Keywords');
        $show->sort('Sort');
        $show->is_sale('Is sale');
        $show->is_hot('Is hot');
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
        $form = new Form(new Product);

        $form->text('title', '商品名称')->rules('required',[
                    'required'=>'不能为空'
            ]);
        $form->select('classify_id', '所属分类')->options(classify::all()->pluck('title','id'))->rules('required',[
                    'required'=>'不能为空'
            ]);
        $form->text('abstract','简述')->rules('required',[
                    'required'=>'不能为空'
            ]);
        $form->image('image', '封面图片')->rules('required',[
                    'required'=>'不能为空'
            ]);
        $form->multipleImage('banner', '详情轮播图')->removable();
        $form->editor('description', '图文介绍')->rules('required',[
                    'required'=>'不能为空'
            ]);
        $form->text('keywords', '关键词')->rules('required',[
                    'required'=>'不能为空'
            ])->help("多个关键词请用' / '分隔");
        $form->number('sort', '排序')->default(50);
        $form->switch('is_sale', '是否上架')->default(0);
        $form->switch('is_hot', '是否热卖')->default(0);

        $form->hasMany('skus', 'SKU 列表', function (Form\NestedForm $form) {
            $form->text('color', '颜色')->rules('required');
            $form->text('configuration', '配置')->rules('required');
            $form->text('style', '款型')->rules('required');
            $form->currency('price', '单价')->symbol('￥')->rules('required|numeric|min:0.01');
            $form->currency('foreign_price', '国外价格')->symbol('$')->help('没有可不填写');
            $form->text('rate','汇率')->help('没有可不填写');
            $form->switch('is_sale', '是否上架')->default(0)->rules('required');
            $form->switch('is_discount','是否为折扣商品')->default(0);
            $form->currency('discount_price', '单价')->symbol('￥')->help('非折扣商品可不填写');
        });


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
        $form->saving(function (Form $form) {
            $skus=$form->input('skus');
            if($skus){
                foreach ($skus as $k => $v) {
                   if($v['id']){
                        $sku=ProductSku::find($v['id']);
                        if($v['price']>$sku->price*1.02 || $v['price']<$sku->price*0.98){
                            $error = new MessageBag([
                                'title'   => '错误信息',
                                'message' => '修改价格不应超过现价的2%',
                            ]);

                            return back()->with(compact('error'));
                        }
                        if($v['discount_price']>$sku->discount_price*1.02 || $v['discount_price']<$sku->discount_price*0.98){
                            $error = new MessageBag([
                                'title'   => '错误信息',
                                'message' => '修改折扣价格不应超过现价的2%',
                            ]);

                            return back()->with(compact('error'));
                        }
                   }
                }
            }
            
            $form->model()->price = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('price') ?: 0;
        });
        return $form;
    }
}
