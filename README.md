# 欢迎使用 Mimikko自动签到助手

#### 目前支持的功能

- 兑换能量值
- 自动签到

## 如何使用

- cd mimikkoAutoSign && composer update
- 更改index.php中的$config变量，设置用户名密码（明文）
- 使用crontab每天定时执行 php index.php >> /tmp/mimikko.log

## 说明
> 使用本程序签到会踢下线正常的客户端，可以禁止你手机上的mimikko访问网络。
> 本程序不会收集任何用户信息。
> 可能会侵犯未知的权益，如有侵权请联系index.php中的联系方式。
