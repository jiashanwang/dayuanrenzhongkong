<?php
/*
 �������� �ɶ���Գ������Ƽ����޹�˾ ԭ������
 �ٷ���ַ��www.dayuanren.cn
 �Ͻ������롢������κ���ʽ����Ȩ��Ϊ��Υ�߽�׷����������
*/

namespace app\common\library;
class Createlog
{
    public static function porderLog($porderid, $log)
    {
        $Kh9vPMC = $porderid % 10;
        $Kh9vPMD = 'porder_log' . $Kh9vPMC;
        M($Kh9vPMD)->insertGetId(array('porder_id' => $porderid, 'log' => $log, 'create_time' => time()));
    }

    public static function customerLog($customer_id, $log, $operator)
    {
        M('customer_log')->insertGetId(array('customer_id' => $customer_id, 'log' => $log, 'operator' => $operator, 'create_time' => time()));
    }
}

?>