<?php

namespace App\Http\Controllers;

use App\Key;
use App\Mailing;
use App\Project;
use App\User;
use Illuminate\Http\Request;
use Mail;
use Redis;
use DB;
use Log;

class MailController extends Controller
{

    function startSending(Request $request) //Функция принимает HTTP POST запрос с информацией каким пользователям нужно отправить письмо
    {
        try{
            if ($request->has('mailing') && count($request->mailing) != 0) {
                $mailing=$request->mailing;
                if(isset($mailing['mass'])){
                    $massInfo=$mailing['mass'];
                    $temp=User::getUsersWithoutProjects($massInfo);
                    $t=[];
                    foreach ($temp as $project=>$users){
                        foreach ($users as $user){
                            $t[]=['user_id'=>$user->id,'project_id'=>$project];
                        }
                    }
                    $mailing=$t;
                }
                foreach ($mailing as $mail) {
                    /* hset используется для абстагирования от других данных хранящихся в redis.
                     Остальное все тривиально: Key - id пользователя, value - json с информацией о пользователе и проекте с которым связано его письмо */
                    Redis::command('hset', ['email', $mail['user_id'], json_encode(['user_id' => $mail['user_id'], 'project_id' => $mail['project_id']])]);
                }
                return response()->json(['status' => true], 200);
            }
            return response()->json(['status' => false], 400);
        }
        catch (\Exception $e){
            return response()->json(['status' => false], 500);
        }
    }


}
