<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class OrdersController extends Controller
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
            ->header('订单列表')
            ->description(' ')
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
            ->header('订单')
            ->description('详细信息')
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
            ->header('订单')
            ->description('编辑')
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
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);
        $grid->model()->orderBy('created_at','desc');
        $grid->no('订单号');
        $grid->user()->username('用户');
        $grid->intention_money('定金/意向金')->display(function($money){
            if($money){
                return '已交';
            }else{
                return '未交';
            }
        });
        $grid->loan_status('是否贷款')->display(function($loan_status){
            switch ($loan_status) {
                case 0:
                    return '无贷款';
                break;
                case 1:
                    return '80%';
                break;
                case 2:
                    return '90%';
                break;
                
            }
        });
        $grid->ship_status('货物状态')->display(function($ship_status){
            switch ($ship_status) {
                case 0:
                    return '未发货';
                break;
                case 1:
                    return '已发货';
                break;
                case 2:
                    return '已收货';
                break;
                
            }
        });
        $grid->disableExport();
        $grid->disableCreateButton();
        $grid->disableRowSelector();
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('no', '订单号');
        });
        $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->append("<a href='logistics?order_id=".$actions->getKey()."'><i class='fa fa-envelope-o'></i></a>");
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
        $show = new Show(Order::findOrFail($id));

        $show->no('订单号');
        $show->divider();
        $show->user()->username('用户');
        $show->address('地址信息');
        $show->user()->id_number('身份证号');
        $show->user()->phone('电话');
        $show->divider();
        
        $show->total_amount('价格');
        $show->items('商品详情')->first()->product_content()->as(function($content){
            $content=$content->toArray();
            $content=json_decode($content[0]['product_content']);
            $str='车辆：'.$content->product_info->title."\n 规格：".$content->sku_info->color.$content->sku_info->configuration.$content->sku_info->style;
            return $str;
        });
        $show->divider();
        $show->loan_status('是否贷款')->as(function($status){
            switch ($status) {
                case 0:
                    return '无贷款';
                break;
                case 1:
                    return '贷款80%';
                break;
                case 2:
                    return '贷款90%';
                break;
            }
        });
        $show->intention_money('定金/意向金')->as(function($money){
            if($money){
                return '已交';
            }else{
                return '未交';
            }
        });
        $show->pay_status('剩余金额')->as(function($money){
            if($money){
                return '已交';
            }else{
                return '未交';
            }
        });
        $show->divider();
        $show->loan_status('贷款详情')->unescape()->as(function($loan_status) use ($id){
            if($loan_status==0){
                return '无贷款';
            }else{
                return "<a href='".env('APP_URL')."/admin/loan_show?order_id=".$id."'>点击查看</a>";
            }
        });
        $show->panel()
            ->tools(function ($tools) {
                $tools->disableDelete();
                
            });
        

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order);

        $form->switch ('intention_money','意向金');
        $form->switch('pay_status','购买金额支付');
        $ship=[
            0=>'未发货',
            1=>'已发货',
            2=>'已收货'
        ];
        $form->select('ship_status','发货状态')->options($ship);

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
