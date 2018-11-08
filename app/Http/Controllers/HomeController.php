<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$user = User::find(5);
        //$token = $user->createToken($user->id.'token')->accessToken;
        //dd($token);

        //Redis::set('name', 'Taylor');
        //dd(Redis::get('name'));

        // Add scope manually
        // $tab = DB::table('oauth_access_tokens')->where('user_id', '5')->first();
        // $scopes = json_decode($tab->scopes, true);
        // array_push($scopes, "pushed-scope");
        // $scopes_db = json_encode($scopes);
        // DB::table('oauth_access_tokens')->where('user_id', '5')->update(['scopes' => $scopes_db]);


        return view('home');
    }

    /**
     * Deletes all the token from the user and generates a fresh token
     * 
     * @param Illuminate\Http\Request
     * @return $token
    */
    public function refreshToken(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)) {
            $delete_old_tokens = \DB::table('oauth_access_tokens')->where('user_id', Auth::user()->id)->delete();
            $token = Auth::user()->createToken('access_token', ['read-users', 'create-users', 'delete-users'])->accessToken;
            return response()->json(['error' => false, 'access_token' => $token]);
        }
        
        return response()->json(['error' => true, 'message' => 'Unable to login using these credentials. Check your credentials and try again.'], 401);

    }
}


//eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjVhOWIzMTE5Y2ZlMjNkMWQ5ZGZkNjRkNDIzZGM4Y2EzNTg4YTk1NjhkM2RmMzQyYzU5OGMyMjUzM2NhYTliOGE1MzU5Nzc0ZDJkZGJhMGNmIn0.eyJhdWQiOiIxIiwianRpIjoiNWE5YjMxMTljZmUyM2QxZDlkZmQ2NGQ0MjNkYzhjYTM1ODhhOTU2OGQzZGYzNDJjNTk4YzIyNTMzY2FhOWI4YTUzNTk3NzRkMmRkYmEwY2YiLCJpYXQiOjE1NDA1Nzg4NzYsIm5iZiI6MTU0MDU3ODg3NiwiZXhwIjoxNTcyMTE0ODc2LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.ZcH-eM9bNpWewl8B-P49QkOlB2lOmePfit83q8JvayxS0rFZYGKm04dZoVfgVkJ1d9aRuGx-X2pqMM-9eTfV83iAvDLbHz3Upr5FerVFqGeFLyjkBkU0rW5keekFn7S9TygswVS5XZkFZ6Da5ucK7bgT3VKwqC43XjYoehEHS4WRyxGlKZKZBkztmBE9tM2QLgUcrFhRK1H1teVkIVBCqszrmbQQHlUkz8tx5_aKvFX5XGOaUQFuAXecsWEVjVePShELbm_44essOPZkbjofRWcXOM5y3JkZui5qTLL1dLh_wAatJCPtxO24tABPDRDMffH2bMZ75eq72pzvukxGTc7ieI2rz6Yc7BkiNF6_m4t6vZ7rUax4_GIvRIsNjyvyYqnOvEb8R0QYz9u03Uw6Okagsp87M3okjIjjTC548bLbQw5s1gPY2Gp7rdZOD_SWv4ODiPyeuGRPGCDaIEanY34I1FuACiBILsYmCw2fCfU9050CNsEG8sJtu-U9u144lYhTbpG7WwLoKSuGWq81jZ9iKkEwhD3uWQ4mL3Hy9Hrk_uMvGtO0D3CnVk2aFD2wwEqF7fKEVxuw3NyrHH92Tr6G7szAackH8DGaZ4nEP8aH6xJaNfLrOFiDZOyFbXzBWrzg84vL5vUpSQM3CDVhvIpNzLHeggF82-sum8aAI4E

//eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjY5YWVhYzIwYzFiNWE5YmNmMDVkZGJlODkxODk4Y2YxMTE0YzcxYWJhMGRkMjQ2OGVlMWE5M2NhZjQ5ZDBiMzhhMjVjNWE2OGNkODE3N2FiIn0.eyJhdWQiOiIxIiwianRpIjoiNjlhZWFjMjBjMWI1YTliY2YwNWRkYmU4OTE4OThjZjExMTRjNzFhYmEwZGQyNDY4ZWUxYTkzY2FmNDlkMGIzOGEyNWM1YTY4Y2Q4MTc3YWIiLCJpYXQiOjE1NDE3MDU4MjAsIm5iZiI6MTU0MTcwNTgyMCwiZXhwIjoxNTczMjQxODIwLCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.dxS4vYdWBLHFuj7eHiDOrVRw6FfW-Ct8ztbIa1y5uVVx9_T47bk6XD9YVQ7pG5RGCG4ZyOhXLLIOtNKxm5Hv5-BOTzg4Q08iZC8qAYgSNR4z3qRJ05BWGcnXSoWt7PYbk7c79vHmARatvwzS6w5eGkmjhQk5J8jwPGDX7bPSeo2IwMTBcF4J4IRjqbn9K3MU5kjK54LYa2fmL357UYpXjsvtmhmT-ybTeK7mXbAd3jvWSP1Ha_gx5eB68MesS0g2Qe4VSGJ4qvAUZcwdYXcAkth-TkCBQtGr4Q0ssubGjtWI0_SfA078D-HpzBc-bGJovW3URPL1TZaH81SAFIVHl-mcCtZ1ofQLke2x1M6Ctt624vTDff-vBn6-gsqBC1wXaaBVIACr7W0ShpnMwbwlteXBAl4ZIE7ljCIx2eg2IEUlQpUV0tRiHOqm9aExjtfRYi93Q-zjk7ZEZrs0jvl80CHLq0-DL-1Ka02H_444o1WjspfNpYyaJZ8uIqzjrvk4c0hOArCHNboKIqPSgdfPFtBep6Pqnm1ZZp5bk0lw8b4BkLK8w1yxpY5ZVntEtAfiy8_12pSvJbN5l9cZmgh1GZdLWSMY2_GbxQR8KqLm3_rB8uSOg8nQWMQGC2GhFbEvWt2siGC1skUu83apB_CnQ7NT7nYPB8OPX_lCGkyEbOI