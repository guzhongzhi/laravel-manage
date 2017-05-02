<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;

class UserTableSeeder extends Seeder {
    
    public function run() {
        DB::table("users")->delete();
        $user = new User();
        
        $user->email = 'guzhongzhi@qq.com';
        $user->password = bcrypt('admin');
        $user->nick_name="test";
        $user->remember_token="";
        $user->created_at = date("Y-m-d H:i:s");
        $user->updated_at = date("Y-m-d H:i:s");
        $user->save();
    }
    
}