<?php

namespace App\Admin\Controllers;

use App\Models\Set;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class SetsController extends Controller
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
            ->header('设置')
            ->description('列表')
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
            ->header('设置')
            ->description('.')
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
        $grid = new Grid(new Set);

         $grid->column('redirector', '说明')->display(function () {
                return <<<CODEEND
新用户请点击新建按钮，如果长时间未跳转，请点击右侧的编辑按钮
<script>
$(document).ready(function () {
    $('.fa-edit').each(function (i, item) {
        $(item).click();
        return;
    });
});
</script>
CODEEND;
        });
        $grid->disableRowSelector();
        $grid->disableFilter();
        $grid->disableExport();
        $grid->actions(function($action) {
            $action->disableDelete();
        });
        $grid->disablePagination();

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
        $show = new Show(Set::findOrFail($id));

        $show->id('Id');
        $show->all_customer_phone('All customer phone');
        $show->email('Email');
        $show->account('Account');
        $show->loan_phone('Loan phone');
        $show->no_loan_phone('No loan phone');
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
        $form = new Form(new Set);

        $form->text('all_customer_phone', '总客服电话');
        $form->email('email', '邮箱');
        $form->text('account', '打款账户');
        $form->text('loan_phone', '贷款电话');
        $form->text('no_loan_phone', '无贷款电话');

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
