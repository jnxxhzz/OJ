<?php
/**
 * UCenter Ӧ�ó��򿪷� Example
 *
 * UCenter ����Ӧ�ó���Ӧ�ó��������ݿ�
 * ʹ�õ��Ľӿں�����
 * uc_authcode()	��ѡ�������û����ĵĺ����ӽ��� Cookie
 * uc_pm_checknew()	��ѡ������ȫ���ж��Ƿ����¶���Ϣ������ $newpm ����
 */

include './config.inc.php';
include './uc_client/client.php';

/**
 * ��ȡ��ǰ�û��� UID �� �û���
 * Cookie ����ֱ���� uc_authcode �������û�ʹ���Լ��ĺ���
 */
if(!empty($_COOKIE['Example_auth'])) {
	list($Example_uid, $Example_username) = explode("\t", uc_authcode($_COOKIE['Example_auth'], 'DECODE'));
} else {
	$Example_uid = $Example_username = '';
}
echo $Example_uid;

/**
 * ��ȡ���¶���Ϣ
 */
$newpm = uc_pm_checknew($Example_uid);

/**
 * �������ܵ� Example ����
 */
switch(@$_GET['example']) {
	case 'login':
		//UCenter �û���¼�� Example ����
		include 'code/login_nodb.php';
	break;
	case 'logout':
		//UCenter �û��˳��� Example ����
		include 'code/logout.php';
	break;
	case 'register':
		//UCenter �û�ע��� Example ����
		include 'code/register_nodb.php';
	break;
	case 'pmlist':
		//UCenter δ������Ϣ�б�� Example ����
		include 'code/pmlist.php';
	break;
	case 'pmwin':
		//UCenter ����Ϣ���ĵ� Example ����
		include 'code/pmwin.php';
	break;
	case 'friend':
		//UCenter ���ѵ� Example ����
		include 'code/friend.php';
	break;
	case 'avatar':
		//UCenter ����ͷ��� Example ����
		include 'code/avatar.php';
	break;
}

echo '<hr />';
if(!$Example_username) {
	//�û�δ��¼
	echo '<a href="'.$_SERVER['PHP_SELF'].'?example=login">��¼</a> ';
	echo '<a href="'.$_SERVER['PHP_SELF'].'?example=register">ע��</a> ';
} else {
	//�û��ѵ�¼
	echo '<script src="ucexample.js"></script><div id="append_parent"></div>';
	echo $Example_username.' <a href="'.$_SERVER['PHP_SELF'].'?example=logout">�˳�</a> ';
	echo ' <a href="'.$_SERVER['PHP_SELF'].'?example=pmlist">����Ϣ�б�</a> ';
	echo $newpm ? '<font color="red">New!('.$newpm.')</font> ' : NULL;
	echo '<a href="###" onclick="pmwin(\'open\')">�������Ϣ����</a> ';
	echo ' <a href="'.$_SERVER['PHP_SELF'].'?example=friend">����</a> ';
	echo ' <a href="'.$_SERVER['PHP_SELF'].'?example=avatar">ͷ��</a> ';
}

?>