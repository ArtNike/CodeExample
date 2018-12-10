<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class User extends Model
{
    use Notifiable;

    public $timestamps = false;

    protected $table = 'users';

    public const UPDATEABLE_ATTRIBUTES = ['name', 'email', 'info'];


    private static $totalUsers = null;
    private static $availableUsersAmount = null;
    private static $availableUsers = null;

    public static function getUsersWithoutProjects($projects = null) //есть вариант когда мы можем не указывать с какими проектами у пользователей не должно быть связи
    {
        if (self::$availableUsers == null) {
            if ($projects == null) {
                $projects = Project::getActiveProjects();
            }
            $usersToSendPerProject = [];
            foreach ($projects as $project) {
                // Для каждого из проектов достаем пользователей которым не было отправленно сообщение с id проекта
                $usersToSendPerProject[$project] = DB::select("select id from users where id not in(select user_id from mailings where project_id={$project})");
            }

            self::$availableUsers = $usersToSendPerProject;
        }
        return self::$availableUsers;
    }

}
