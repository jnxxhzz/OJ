<?php
/**
 * UCenter Ӧ�ó��򿪷� Example
 *
 * Ӧ�ó������Լ����û����û�ע�ᡢ����� Example ����
 * ʹ�õ��Ľӿں�����
 * uc_get_user()	���룬��ȡ�û�����Ϣ
 * uc_user_register()	���룬ע���û�����
 * uc_authcode()	��ѡ�������û����ĵĺ����ӽ��ܼ����ִ��� Cookie
 */

if(empty($_POST['submit'])) {
	//ע���
	echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?example=register">';

	if($_GET['action'] == 'activation') {
		echo '����:';
		list($activeuser) = explode("\t", uc_authcode($_GET['auth'], 'DECODE'));
		echo '<input type="hidden" name="activation" value="'.$activeuser.'">';
		echo '<dl><dt>�û���</dt><dd>'.$activeuser.'</dd></dl>';
	} else {
		echo 'ע��:';
		echo '<dl><dt>�û���</dt><dd><input name="username"></dd>';
		echo '<dt>����</dt><dd><input name="password"></dd>';
		echo '<dt>Email</dt><dd><input name="email"></dd></dl>';
	}
	echo '<input name="submit" type="submit">';
	echo '</form>';
} else {
	//��UCenterע���û���Ϣ
	$username = '';
	if(!empty($_POST['activation']) && ($activeuser = uc_get_user($_POST['activation']))) {
		list($uid, $username) = $activeuser;
	} else {
		if(uc_get_user($_POST['username']) && !$db->result_first("SELECT uid FROM {$tablepre}members WHERE user_id='$_POST[username]'")) {
			//�ж���Ҫע����û��������Ҫ������û���������ת����¼ҳ����֤
			echo '���û�����ע�ᣬ�뼤����û�<br><a href="'.$_SERVER['PHP_SELF'].'?example=login">����</a>';
			exit;
		}

		$uid = uc_user_register($_POST['username'], $_POST['password'], $_POST['email']);
		if($uid <= 0) {
			if($uid == -1) {
				echo '�û������Ϸ�';
			} elseif($uid == -2) {
				echo '����Ҫ����ע��Ĵ���';
			} elseif($uid == -3) {
				echo '�û����Ѿ�����';
			} elseif($uid == -4) {
				echo 'Email ��ʽ����';
			} elseif($uid == -5) {
				echo 'Email ������ע��';
			} elseif($uid == -6) {
				echo '�� Email �Ѿ���ע��';
			} else {
				echo 'δ����';
			}
		} else {
			$username = $_POST['username'];
		}
	}
	if($username) {
		$db->query("INSERT INTO {$tablepre}members (uid,username,admin) VALUES ('$uid','$username','0')");
		//ע��ɹ������� Cookie������ֱ���� uc_authcode �������û�ʹ���Լ��ĺ���
		setcookie('Example_auth', uc_authcode($uid."\t".$username, 'ENCODE'));
		echo 'ע��ɹ�<br><a href="'.$_SERVER['PHP_SELF'].'">����</a>';
		exit;
	}
}

?>