<?php
/**
 * UCenter Ӧ�ó��򿪷� Example
 *
 * �û��˳��� Example ����
 * ʹ�õ��Ľӿں�����
 * uc_user_synlogout()	��ѡ������ͬ���˳��Ĵ���
 */

setcookie('Example_auth', '', -86400);
//����ͬ���˳��Ĵ���
$ucsynlogout = uc_user_synlogout();
echo '�˳��ɹ�'.$ucsynlogout.'<br><a href="'.$_SERVER['PHP_SELF'].'">����</a>';
exit;

?>