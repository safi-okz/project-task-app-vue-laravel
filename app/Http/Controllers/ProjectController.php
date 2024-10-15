<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Str;
use App\Models\Project;
use App\Events\NewUserCreated;

class ProjectController extends Controller
{
    public function store(Request $request) {

        $fields = $request->all();

        $errors = Validator::make($fields, [
                'name' => 'required',
                'status' => 'required',
                'startDate' => 'required',
                'endDate' => 'required'
        ]);

        if($errors->fails()){
            return response($errors->errors()->all(), 422);
        }

        // $isValidEmail = filter_var($fiels['email'], FILTER_VALIDATE_EMAIL) ? 1 : 0;

       $project = Project::create([
            'name' => $fields['name'],
            'startDate' => $fields['startDate'],
            'endDate' => $fields['endDate'],
            'status' => $fields['status'],
        ]);

        return response([
            'project' => $project,
            'message' => 'Project Created'
        ]);
    }
}
