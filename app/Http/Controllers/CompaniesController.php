<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Validator;
use Hash;
use Auth;

class CompaniesController extends Controller
{
    
    public function __construct() {
        $this->middleware('jwt.auth', ['except' => ['index', 'show']]);
    }
    
    
    public function index() {
        $companies = Company::all();
        return response()->json($companies);
    }

    public function show($id) {
        $company = Company::find($id);

        if (!$company) {
            return response()->json([
                'message' => 'Record not found',
            ], 404);
        }

        return response()->json($company);
    }

    public function store(Request $request) {

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:100',
            'email' => 'required|email|unique:companies',
            'password' => 'required',
        ]); 

        if($validator->fails()) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $validator->errors()->all() 
            ], 422);
        }

        $company = new Company();
        $company->fill($data);
        $password = $request->only('password')["password"];
        $company->password = Hash::make($password);
        $company->save();

        return response()->json($company, 201);
    }

    public function update(Request $request, $id) {
        
        $company = Company::find($id);
        $data = $request->all();

        if(!$company) {
            return response()->json([
                'message'   => 'Record not found',
            ], 404);
        }

        if (array_key_exists('email', $data) && $company->email == $data['email']) {
            unset($data['email']);
        }

        $validator = Validator::make($data, [
            'name' => 'max:100',
            'email' => 'email|unique:companies',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $validator->errors()->all()
            ], 422);
        }

        if (array_key_exists('password', $data)) {
            $company->password = Hash::make($data['password']);
        }

        $company->fill($data);
        $company->save();

        return response()->json($company);
    }

    public function destroy($id) {
        $company = Company::find($id);

        if(!$company) {
            return response()->json([
                'message'   => 'Record not found',
            ], 404);
        }

        if (Auth::user()->id != $company->id) {
            return response()->json([
                'message' => 'You haven\'t permission to delete this entry',
            ], 401);
        }

        return response()->json($company->delete(), 204);
    }
}
