<?php

// Bulgarian Language Module (translated by Veselin N. Stefanov)

$GLOBALS["charset"] = "windows-1251";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
      // error
      "error"                  => "������(�)",
      "back"                  => "�����",
      
      // root
      "home"                  => "��������� ���������� �� ����������, ��������� ������ ���������.",
      "abovehome"            => "�������� ���������� �� ���� �� ���� ����� ���������.",
      "targetabovehome"      => "�������� ���������� �� ���� �� ���� ����� ���������.",

      // exist
      "direxist"            => "������������ �� ����������",
      //"filedoesexist"      => "���� � ���� ��� ���� ����������",
      "fileexist"            => "����� ���� �� ����������",
      "itemdoesexist"            => "����� ����� ���� ����������",
      "itemexist"            => "����� ����� �� ����������",
      "targetexist"            => "�������� ���������� �� ����������",
      "targetdoesexist"      => "�������� ����� �� ����������",
      
      // open
      "opendir"            => "������������ �� ���� �� ���� ��������",
      "readdir"            => "������������ �� ���� �� ���� ���������",

      // access
      "accessdir"            => "������ ������ �� ���� ����������",
      "accessfile"            => "������ ������ �� ���� ����",
      "accessitem"            => "������ ������ �� ���� �����",
      "accessfunc"            => "������ ����� �� �������� ���� �������",
      "accesstarget"            => "������ ������ �� �������� ����������",

      // actions
      "permread"            => "������ ��� ���������� �� ����� �� ������",
      "permchange"            => "������ ��� ����� ����� �� ������",
      "openfile"            => "������ ��� �������� �� ����",
      "savefile"            => "������ ��� ����� �� ����",
      "createfile"            => "������ ��� ��������� �� ����",
      "createdir"            => "������ ��� ��������� �� ����������",
      "uploadfile"            => "������ ��� ������� �� ����",
      "copyitem"            => "������ ��� ��������",
      "moveitem"            => "������ ��� ������������",
      "delitem"            => "������ ��� ���������",
      "chpass"            => "������ ��� ������� �� ������",
      "deluser"            => "������ ��� ��������� �� ����������",
      "adduser"            => "������ ��� ��������� �� ����������",
      "saveuser"            => "������ ��� ����� �� ����������",
      "searchnothing"            => "��������� ������ �� �������",
      
      // misc
      "miscnofunc"            => "���������� �������",
      "miscfilesize"            => "�������� ���������� ������ �� �����",
      "miscfilepart"            => "����� � ����� ��������",
      "miscnoname"            => "������ �� �������� ���",
      "miscselitems"            => "�� ��� ������� �����(�)",
      "miscdelitems"            => "������� �� ��� �� ������ �� �������� ���� \"+num+\" �����(�)?",
      "miscdeluser"            => "������� �� ��� �� ������ �� �������� ���������� '\"+user+\"'?",
      "miscnopassdiff"      => "������ ������ �� �� �������� �� ����������",
      "miscnopassmatch"      => "�������� �� ��������",
      "miscfieldmissed"      => "���������� ��� �� ��������� ����� ����",
      "miscnouserpass"      => "������ ��� ��� ������",
      "miscselfremove"      => "�� ������ �� �������� ����������� �� ������",
      "miscuserexist"            => "������������ ���� ����������",
      "miscnofinduser"      => "������������ �� ���� �� ���� ������",
);
$GLOBALS["messages"] = array(
      // links
      "permlink"            => "������� ����� �� ������",
      "editlink"            => "����������",
      "downlink"            => "�������",
      "uplink"            => "������",
      "homelink"            => "������",
      "reloadlink"            => "������",
      "copylink"            => "�������",
      "movelink"            => "��������",
      "dellink"            => "������",
      "comprlink"            => "���������",
      "adminlink"            => "��������������",
      "logoutlink"            => "�����",
      "uploadlink"            => "�������",
      "searchlink"            => "�����",
      
      // list
      "nameheader"            => "����",
      "sizeheader"            => "������",
      "typeheader"            => "���",
      "modifheader"            => "��������",
      "permheader"            => "�����",
      "actionheader"            => "��������",
      "pathheader"            => "���",
      
      // buttons
      "btncancel"            => "������",
      "btnsave"            => "�������",
      "btnchange"            => "�������",
      "btnreset"            => "�������",
      "btnclose"            => "�������",
      "btncreate"            => "������",
      "btnsearch"            => "�����",
      "btnupload"            => "�������",
      "btncopy"            => "�������",
      "btnmove"            => "��������",
      "btnlogin"            => "����",
      "btnlogout"            => "�����",
      "btnadd"            => "������",
      "btnedit"            => "����������",
      "btnremove"            => "������",
      
      // actions
      "actdir"            => "�����",
      "actperms"            => "������� �� �����",
      "actedit"            => "���������� ����",
      "actsearchresults"      => "��������� �� �������",
      "actcopyitems"            => "������� �����(�)",
      "actcopyfrom"            => "������� �� /%s � /%s ",
      "actmoveitems"            => "�������� �����(�)",
      "actmovefrom"            => "�������� �� /%s � /%s ",
      "actlogin"            => "����",
      "actloginheader"      => "���� �� �� ������� QuiXplorer",
      "actadmin"            => "��������������",
      "actchpwd"            => "����� ������",
      "actusers"            => "�����������",
      "actarchive"            => "��������� ������(�)",
      "actupload"            => "������� ����(���)",
      
      // misc
      "miscitems"            => "�����(�)",
      "miscfree"            => "��������",
      "miscusername"            => "����������",
      "miscpassword"            => "������",
      "miscoldpass"            => "����� ������",
      "miscnewpass"            => "���� ������",
      "miscconfpass"            => "���������� ������",
      "miscconfnewpass"      => "���������� ���� ������",
      "miscchpass"            => "������� ������",
      "mischomedir"            => "������� ����������",
      "mischomeurl"            => "������� URL",
      "miscshowhidden"      => "�������� ������ ������",
      "mischidepattern"      => "����� �������",
      "miscperms"            => "�����",
      "miscuseritems"            => "(���, ������� ����������, �������� ������ ������, ����� �� ������, �������)",
      "miscadduser"            => "������ ����������",
      "miscedituser"            => "���������� ���������� '%�'",
      "miscactive"            => "�������",
      "misclang"            => "����",
      "miscnoresult"            => "���� ���������",
      "miscsubdirs"            => "����� � �������������",
      "miscpermnames"            => array("���� �� ���������","�����������","����� �� ������","����� � ����� �� ������",
                              "�������������"),
      "miscyesno"            => array("��","��","�","�"),
      "miscchmod"            => array("����������", "�����", "������������"),
);
?>
