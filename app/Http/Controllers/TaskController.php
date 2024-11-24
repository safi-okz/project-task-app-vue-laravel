<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Str;
use App\Models\Member;
use App\Models\Task;
use App\Models\TaskMember;

class TaskController extends Controller
{
    public function createTask(Request $request) {

        $fields = $request->all();

        $errors = Validator::make($fields, [
                'name' => 'required',
                'projectId' => 'required|numeric',
                'memberIds' => 'required|array',
                'memberIds.*' => 'numeric'
        ]);

        if($errors->fails()){
            return response($errors->errors()->all(), 422);
        }
    }
}
