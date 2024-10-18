<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\json;

class TodoListController extends Controller
{
    public function modify(){
       
        DB::beginTransaction();
        try{
            $request = request()->all();
            $request['user_id'] = \Auth::id();
            if(request()->get('id')){
                // for edit 
                $todo = TodoList::find($request['id']);
                $todo->fill($request);
                $todo->save();
            }else{
                // for create
                TodoList::create($request);
            }

            DB::commit();

            return 'success';
        }catch(\Exception $e){
            DB::rollBack();
            \Log::info($e);
            return response()->json($e->getMessage(),500);
        }
    }

    public function index(){{
        $user = \Auth::user();

        $todoList = TodoList::getQuery()
            ->join('users','users.id','todo_lists.user_id')
            ->when($user->type == 1,function($builder) use ($user){
                return $builder->where('todo_lists.user_id',$user->id);
            })
            ->when(!is_null(request()->get('status')),function($builder){
                return $builder->whereIn('todo_lists.status',explode(',', request()->get('status')));
            })->orderBy('todo_lists.status',)
            ->select('todo_lists.*',DB::raw('concat(users.firstname,"a ",users.lastname) as name'))->get();

        return $todoList;

    }}

    public function delete($id){
     
        DB::beginTransaction();
        try{
            TodoList::getQuery()->where('id',$id)->delete();
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            \Log::info($e);
            return response()->json($e->getMessage(),500);
        }
    }
}
