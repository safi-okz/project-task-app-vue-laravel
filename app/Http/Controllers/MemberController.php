<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Str;
use App\Models\Project;
use App\Models\TaskProgress;
use App\Models\Member;

class MemberController extends Controller
{
    public function index(Request $request){

        $query = $request->get('query');

        $members = Member::select();

        // $members = DB::select('members');

        if(!is_null($query) && $query !== '') {
            $members->where('name', 'like', '%'.$query.'%')->orderBy('id', 'desc');

            return response(['data' => $members->paginate(10)], 200);
        }

        return response(['data' => $members->paginate(10)], 200);
    }

    public function store(Request $request) {

            $fields = $request->all();

        $errors = Validator::make($fields, [
                'name' => 'required',
                'email' => 'required|email'
        ]);

        if($errors->fails()){
            return response($errors->errors()->all(), 422);
        }

        // $isValidEmail = filter_var($fiels['email'], FILTER_VALIDATE_EMAIL) ? 1 : 0;

       $member = Member::create([
            'name' => $fields['name'],
            'email' => $fields['email']
        ]);

        return response([
            'member' => $member,
            'message' => 'Member Created'
        ]);
    }

    public function edit(Request $request, $id) {

        $fields = $request->all();

        $errors = Validator::make($fields, [
                'id' => 'required',
                'name' => 'required',
                'email' => 'required'
        ]);

        if($errors->fails()){
            return response($errors->errors()->all(), 422);
        }

        // $isValidEmail = filter_var($fiels['email'], FILTER_VALIDATE_EMAIL) ? 1 : 0;

       $member = Member::where('id', $id)->update([
        'name' => $fields['name'],
        'email' => $fields['email']
    ]);

        return response([
            'member' => $member,
            'message' => 'Member updated'
        ]);
    }

}
