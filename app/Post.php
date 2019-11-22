<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Ruser;

class Post extends Model
{
    function doesItBelongsToUser(User $user)
    { 
        // try
        // {
            $ruser = Ruser::findOrFail($this->ruser_id);
        // }catch(Exception $e){
        //     return response()->json(['message' => 'Reddit user not found'], 404);
        // }
        if($user->id == $ruser->user_id){
            return true;
        }

        return false;
    }

    function canUse(User $user)
    { 
        if($user->is_admin == 1 || $this->doesItBelongsToUser($user))
        {
            return true;
        }else{
            return false;
        }
    }
}
