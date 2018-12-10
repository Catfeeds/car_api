<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Http\Request;
use App\Models\Upload;

class LoanShowController extends Controller
{

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content,Request $request)
    {
        $order_id=$request->order_id;
        $upload=Upload::where([
                'order_id'=>$order_id,
                'type'=>0
            ])->first();
        if($upload){
            $articleView = view('admin.unmarried_loan_show',compact('order_id'))
                    ->render();
        }else{
            $articleView = view('admin.married_loan_show',compact('order_id'))
                    ->render();
        }
        
        
       return $content->header('贷款审核信息')
            ->description(' ')
            ->row($articleView);
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
            ->header('Edit')
            ->description('description')
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
        $grid = new Grid(new Banner);

        $grid->id('Id');
        $grid->title('Title');
        $grid->image('Image');
        $grid->sort('Sort');
        $grid->is_show('Is show');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

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
        $show = new Show(Banner::findOrFail($id));

        $show->id('Id');
        $show->title('Title');
        $show->image('Image');
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
        $form = new Form(new Banner);

        $form->text('title', 'Title');
        $form->image('image', 'Image');
        $form->switch('sort', 'Sort')->default(50);
        $form->switch('is_show', 'Is show');

        return $form;
    }
}
