<?php
header('Content-Type', 'application/json');

use Respect\Validation\Validator as v;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\HtmlFormatter;

require(__DIR__ . '/../vendor/autoload.php');

$log = new Logger('name');

$streamHandler = new StreamHandler('./../logs/register.html', Logger::DEBUG);
$streamHandler->setFormatter(new HtmlFormatter());
$log->pushHandler($streamHandler);

/**
 * Пользователей список
 */

$lastId = 0;
$users = [
    [
        'id'           => ++$lastId,
        'name'         => 'Борис',
        'surname'      => 'Нуралиев',
        'email'        => 'bnyraliev@gmail.com',
        'passwordHash' => md5('password'),
    ],
    [
        'id'           => ++$lastId,
        'name'         => 'Джон',
        'surname'      => 'Резиг',
        'email'        => 'jeresig@gmail.com',
        'passwordHash' => md5('password'),
    ],
    [
        'id'           => ++$lastId,
        'name'         => 'Линда',
        'surname'      => 'Стоун',
        'email'        => 'lindastoyn@microsoft.com',
        'passwordHash' => md5('password'),
    ],
    [
        'id'           => ++$lastId,
        'name'         => 'Имя1',
        'surname'      => 'Фамилия1',
        'email'        => 'user1@gmail.com',
        'passwordHash' => md5('password'),
    ],
    [
        'id'           => ++$lastId,
        'name'         => 'Имя2',
        'surname'      => 'Фамилия2',
        'email'        => 'user2@gmail.com',
        'passwordHash' => md5('password'),
    ],

];

// Соберем входные поля

$name = $_POST['name'] ?? '';
$surname = $_POST['surname'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['passwordConfirm'] ?? '';

// Проведем валидацию
$errMessages = [];

// Поле: Имя
if ($name == '') {
    $errMessages[] = "Проверь поле имя";
}

// Поле: Фамилия
if ($surname == '') {
    $errMessages[] = "Проверь поле фамилия";
}

// Поле: Email
if (!v::email()->validate($email)) {
    $errMessages[] = "Проверь поле емейл";
}

// Поля: Пароль и повтор пороля
if ($password == '' || $passwordConfirm == '') {
    $errMessages[] = "Проверь поле пароль\повтор пароля";
}

if ($password != $passwordConfirm) {
    $errMessages[] = "Пароли не совпадают";
}

if (!v::length(6, 12)->validate($password)) {
    $errMessages[] = "Длина пароля должна быть от 6 до 12 знаков";
}

// Проверка занятых емейлов
foreach ($users as $user) {
    if ($email == $user['email']) {
        $errMessages[] = "Этот email уже занят.";
    }
}

// Если есть ошибки - вернем их клиенту и завершим скрипт
if (count($errMessages) > 0) {
    echo json_encode([
        'result'        => 'error',
        'errorMessages' => $errMessages
    ]);
    $log->warn("Ошибочная регистрация",
        ['errMessage' => $errMessages, '_POST' => $_POST]);
    die;
}

$log->info("Успешная регистрация", $_POST);

echo json_encode(['result' => 'OK']);
