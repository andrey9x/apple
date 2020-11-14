<?php


namespace console\controllers;


use common\models\User;
use yii\console\Controller;
use yii\helpers\Console;

class AdminController extends Controller
{
    public function actionCreate()
    {
        $user = new User();
        $user->status = User::STATUS_ACTIVE;
        $user->generateAuthKey();

        $username = Console::input('Введите имя пользователя: ');
        $user->username = $username;
        if (!$user->validate(['username'])) {
            Console::output('Администратор не добавлен');
            Console::output($user->getFirstError('username'));
            return;
        }

        $email = Console::input('Введите email: ');
        $user->email = $email;
        if (!$user->validate(['email'])) {
            Console::output('Администратор не добавлен');
            Console::output($user->getFirstError('email'));
            return;
        }

        $password = Console::input('Введите пароль: ');
        if (!empty($password)) {
            $user->setPassword($password);
        }
        if (!$user->validate(['password_hash'])) {
            Console::output('Администратор не добавлен');
            Console::output($user->getFirstError('password_hash'));
            return;
        }

        if ($user->save()) {
            Console::output('Администратор добавлен');
        }
    }

}
