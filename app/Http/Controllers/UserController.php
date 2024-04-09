<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\MainUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function store(Request $request)
    {
        try {
            $database_connection = config('database.default');
            config(['database.default' => 'pgsql']);

            $validator = Validator::make(request()->all(), [
                "email" => "required|email|unique:users,email",
                'password' => "required|string",
                'first_name' => "required|string",
                'last_name' => "required|string",
                'id_number' => "nullable|string",
            ]);

            if ($validator->fails())
                return $this->handleError($validator->errors()->first());


            DB::beginTransaction();
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'table_connection' => $database_connection
            ]);


            config(['database.default' => $database_connection]);
            DB::beginTransaction();
            $person = Person::create([
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
                "id_number" => $request->id_number,
                "email" => $request->email,
            ]);

            DB::commit();

            config(['database.default' => 'pgsql']);
            DB::commit();

            return $this->handleResponse($person);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->handleError($ex);
        }
    }

    public function index()
    {
        try{
            $database_connection = config('database.default');
            config(['database.default' => 'pgsql']);

            $users = User::get();
            return $this->handleResponse($users);
        }
        catch (Exception $ex) {
            DB::rollBack();
            return $this->handleError($ex);
        }
    }
}
