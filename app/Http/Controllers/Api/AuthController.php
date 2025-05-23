<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
   public function register(Request $request) {

    // $validate_all_user_data = $request->validate([
    //     'name' => 'required|string',
    //     'email' => 'required|unique|email',
    //     'password' => 'required|min:6'
    // ]);


   //Validate all the user data before the database
    $validate_all_user_data = Validator::make($request->all(), [
    'name' => 'required|string',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|min:6',
    ]);
  
  //Cheak all the data is valid or not
    if($validate_all_user_data->fails()) {
        return response()->json([
            'status' => 'error',
            'error' => $validate_all_user_data->errors()
        ], 404);
    }

    //return response()->json(['message' => $request->all()], 200);
    //Store all the user data into the database
    
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

//$token = $user->creteToken('user-token', [user])->plainTextToken;
 $token = $user->createToken('user-token', ['user'])->plainTextToken;

  return response()->json([
    'id' => $user->id,
    'name' => $user->name,
    'email' => $user->email,
    'token' => $token,
    'created_at' => $user->created_at
  ], 200);
   }

  public function login(Request $request) {
//return response()->json("okkkkkk", 200);
    $validate = Validator::make($request->all(),[
      'email' => 'required|email',
      'password' => 'required|min:6'
    ]);

    if($validate->fails()) {
      return response()->json([
        'errors' => $validate->errors()
      ], 422);
    }
    $user = User::where('email', $request->email)->first();

    if(!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $userType = $user->role === 'admin' ? ['admin'] : ['user'];

    $token = $user->createToken('login-token', $userType)->plainTextToken;

    return response()->json([
      'message' => 'User logged in successfully',
      'token' => $token,
      'user' => $user,
    ], 200);
    
   }

    public function index() {
      
    $user = User::all();
    
    $authenticated_user = auth()->user();

    return response()->json([
    'authenticateUser' => $authenticated_user,
    ], 200);
   }
   


}
