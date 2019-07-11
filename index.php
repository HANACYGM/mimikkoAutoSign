<?php
/**
 * Mimikko 自动签到
 * 望着遥遥不至的4级，不知何时才能到达。
 * 或者买会员自动签到也可以接受。
 *
 * 用法：
 * Usage: 更改 $config 里的用户名和密码, 然后 php v2,php
 *
 * 洁白(｀?ω??)
 * 闪耀(^?ω?^ ) 0w0 ←
 * 奇迹～(￣▽￣～)
 * 之花(～￣▽￣)～
 * White Lily(°?°)ゞ
 * 
 * @author: CYGM <hana@9999.moe>
 * @version: 1.0.0
 */
namespace mimikko;

use src\Mimikko;

require_once __DIR__ . '/vendor/autoload.php';

$config = [
    "user" => "",
    "password" => ""
];

$mimikko = new Mimikko($config["user"], $config['password']);

$mimikko->getToken()
        ->getServantId()
        ->getExchangeReward()
        ->SignAndSignInformationV2()
        ;

echo "[" . date('Y-m-d') . "]" . "  " . json_encode($mimikko->response) . PHP_EOL;

unset($mimikko);