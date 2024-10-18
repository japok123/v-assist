<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;
            $user->token = $token;
           
            return response()->json([
                'user' => $user,
                'token' => $token ?? ''
            ], 200); 
        }
    
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    

    public function logout(Request $request)
    {
        \Log::info(\Auth::id());
        Auth::guard('web')->logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout successful']);
    }
    public function register() {
        try {
            DB::beginTransaction();
            $user = null;
            if(request()->get('id')){
                // for edit 
                $user = User::find(request()->get('id'));
                $user->fill(request()->all());
                $user->save();
            }else{
                // for create
                $user = User::create(request()->all());
                $token = $user->createToken('API Token')->plainTextToken;
                $user->remember_token = $token;
 
                $user->update();
                $user->token = $token;
 
            }
  
            DB::commit();
    
          
            return response()->json([
                'user' => $user,
                'token' => $token ?? ''
            ], 201); 
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
            
            return response()->json([
                'error' => 'Registration failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }    

    public function index(){
        return User::all();
    }

    public function delete($id){
     
        DB::beginTransaction();
        try{
            User::getQuery()->where('id',$id)->delete();
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            \Log::info($e);
            return response()->json($e->getMessage(),500);
        }
    }
}
