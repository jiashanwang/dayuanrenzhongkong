<?php
/*
 本代码由 成都大猿人网络科技有限公司 原创开发
 官方网址：www.dayuanren.cn
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace app\common\command;use app\common\library\Configapi;use app\common\library\Notification;use app\common\library\SmsNotice;use app\common\model\Client;use app\common\model\Porder;use think\console\Command;use think\console\Input;use think\console\Output;class Crontab58 extends Command{protected function configure(){$this->setName('Crontab58')->setDescription('58秒定时器');}protected function execute(Input $input,Output $output){Kh9x1:if(1)goto Kh9eWjgx4;goto Kh9ldMhx4;Kh9eWjgx4:C(Configapi::getconfig());Porder::apiSelfUp();$Kh9MC="同步上下架任务执行完成！" . date("Y-m-d H:i:s",time());$Kh9MD=$Kh9MC . PHP_EOL;echo $Kh9MD;sleep(58);goto Kh9x1;goto Kh9x3;Kh9ldMhx4:Kh9x3:Kh9x2:}}
?>