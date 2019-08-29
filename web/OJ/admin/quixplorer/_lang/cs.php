<?php

// English Language Module for v2.3 (translated by the QuiX project)

$GLOBALS["charset"] = "WINDOWS-1250";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "CHYBA(Y)",
	"back"			=> "Zp�t",
	
	// root
	"home"			=> "Domovsk� adres�� neexistuje, opravte sv� zad�n�.",
	"abovehome"		=> "Dan� adres�� nem��e b�t pou�it jako domovsk� adres��.",
	"targetabovehome"	=> "C�lov� adres�� nem��e b�t domovsk�m adres��em.",
	
	// exist
	"direxist"		=> "Adres�� neexistuje.",
	//"filedoesexist"	=> "Soubor existuje.",
	"fileexist"		=> "Soubor neexistuje.",
	"itemdoesexist"		=> "Tato polo�ka existuje.",
	"itemexist"		=> "Tato polo�ka neexistuje.",
	"targetexist"		=> "C�lov� adres�� neexistuje.",
	"targetdoesexist"	=> "C�lov� polo�ka existuje.",
	
	// open
	"opendir"		=> "Nemohu otev��t adres��.",
	"readdir"		=> "Nemohu ��st adres��.",
	
	// access
	"accessdir"		=> "Nem�te povolen p��stup do tohoto adres��e.",
	"accessfile"		=> "Nem�te povolen p��stup k tomuto souboru.",
	"accessitem"		=> "Nem�te povolen p��stup k t�to polo�ce.",
	"accessfunc"		=> "Nem�te povoleno u�it� t�to funkce.",
	"accesstarget"		=> "Nem�te povolen p�istup k tomuto c�lov�mu adres��i.",
	
	// actions
	"permread"		=> "Nastaven� pr�v selhalo.",
	"permchange"		=> "Zm�na pr�v selhala.",
	"openfile"		=> "Otev�en� souboru selhalo.",
	"savefile"		=> "Ulo�en� souboru selhalo.",
	"createfile"		=> "Vytvo�en� souboru selhalo.",
	"createdir"		=> "Vytvo�en� adres��e selhalo.",
	"uploadfile"		=> "Nahr�n� souboru se nezda�ilo.",
	"copyitem"		=> "Kop�rov�n� selhalo.",
	"moveitem"		=> "P�esun se nezda�il.",
	"delitem"		=> "Smaz�n� se nezda�ilo.",
	"chpass"		=> "Zm�na hesla se nezda�ila.",
	"deluser"		=> "Smaz�n� u�ivatele se nezda�ilo.",
	"adduser"		=> "P�id�n� u�ivatele se nezda�ilo.",
	"saveuser"		=> "Ulo�en� u�ivatele se nezda�ilo.",
	"searchnothing"		=> "Mus�te zadat n�zev hledan�ho souboru/adres��e.",
	
	// misc
	"miscnofunc"		=> "Funkce nep��stupn�.",
	"miscfilesize"		=> "Soubor p�ekra�uje maxim�ln� velikost.",
	"miscfilepart"		=> "Soubor byl ulo�en pouze ��ste�n�.",
	"miscnoname"		=> "Mus�te zadat jm�no.",
	"miscselitems"		=> "Nevybral jste ��dnou polo�ku(y).",
	"miscdelitems"		=> "Jste si jisti, �e chcete smazat tuto \"+num+\" polo�ku(y)?",
	"miscdeluser"		=> "Jste si jisti, �e chcete smazat tohoto u�ivatele '\"+user+\"'?",
	"miscnopassdiff"	=> "Nov� heslo nesouhlas� s p�vodn�m.",
	"miscnopassmatch"	=> "Hesla se neshoduj�.",
	"miscfieldmissed"	=> "Zapomn�l jste vyplnit po�adovan� pole.",
	"miscnouserpass"	=> "Zadan� jm�no nebo heslo je chybn�.",
	"miscselfremove"	=> "Nem��ete smazat s�m sebe.",
	"miscuserexist"		=> "U�ivatel ji� existuje.",
	"miscnofinduser"	=> "Nemohu naj�t u�ivatele.",
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "ZM�NA PR�V",
	"editlink"		=> "EDITACE",
	"downlink"		=> "ST�HNOUT",
	"uplink"		=> "V݊",
	"homelink"		=> "�VOD",
	"reloadlink"		=> "RELOAD",
	"copylink"		=> "KOP�ROV�N�",
	"movelink"		=> "P�ESUN",
	"dellink"		=> "SMAZAT",
	"comprlink"		=> "ARCH�V",
	"adminlink"		=> "ADMIN",
	"logoutlink"		=> "ODHL��EN�",
	"uploadlink"		=> "NAHR�T",
	"searchlink"		=> "VYHLEDAT",
	
	// list
	"nameheader"		=> "N�zev",
	"sizeheader"		=> "Velikost",
	"typeheader"		=> "Typ",
	"modifheader"		=> "Upraveno",
	"permheader"		=> "Pr�va",
	"actionheader"		=> "Akce",
	"pathheader"		=> "Cesta",
	
	// buttons
	"btncancel"		=> "Zru�it",
	"btnsave"		=> "Ulo�it",
	"btnchange"		=> "Zm�nit",
	"btnreset"		=> "Reset",
	"btnclose"		=> "Zav��t",
	"btncreate"		=> "Vytvo�it",
	"btnsearch"		=> "Vyhledat",
	"btnupload"		=> "Nahr�t",
	"btncopy"		=> "Kop�rovat",
	"btnmove"		=> "P�esunout",
	"btnlogin"		=> "P�ihl�sit",
	"btnlogout"		=> "Odhl�sit",
	"btnadd"		=> "P�idat",
	"btnedit"		=> "Editovat",
	"btnremove"		=> "Smazat",
	
	// actions
	"actdir"		=> "Adres��",
	"actperms"		=> "Zm�na pr�v",
	"actedit"		=> "Editace souboru",
	"actsearchresults"	=> "Naj�t v�sledky",
	"actcopyitems"		=> "Kop�rovat polo�ku(y)",
	"actcopyfrom"		=> "Kop�rovat z /%s do /%s ",
	"actmoveitems"		=> "P�esunout polo�ku(y)",
	"actmovefrom"		=> "P�esunout z /%s do /%s ",
	"actlogin"		=> "P�ihl�sit k FTP ADASERVIS s.r.o.",
	"actloginheader"	=> "WEB/FTP QuiXplorer",
	"actadmin"		=> "Administrace",
	"actchpwd"		=> "Zm�na hesla",
	"actusers"		=> "U�ivatel�",
	"actarchive"		=> "Arch�v polo�ek",
	"actupload"		=> "Nahr�t soubror(y)",
	
	// misc
	"miscitems"		=> "Polo�ka(y)",
	"miscfree"		=> "Free",
	"miscusername"		=> "Jm�no",
	"miscpassword"		=> "Heslo",
	"miscoldpass"		=> "Star� heslo",
	"miscnewpass"		=> "Nov� heslo",
	"miscconfpass"		=> "Potvrdit heslo",
	"miscconfnewpass"	=> "Potvrdit nov� heslo",
	"miscchpass"		=> "Zm�nit heslo",
	"mischomedir"		=> "Domovsk� adres��",
	"mischomeurl"		=> "Domovk� URL",
	"miscshowhidden"	=> "Zobrazit skryt� polo�ky",
	"mischidepattern"	=> "Skr�t vzor",
	"miscperms"		=> "Pr�va",
	"miscuseritems"		=> "(jm�no, domovsk� adres��, zobrazit skryt� polo�ky, pr�va, aktivn�)",
	"miscadduser"		=> "P�idat u�ivatele",
	"miscedituser"		=> "Editovat u�ivatele '%s'",
	"miscactive"		=> "Aktivn�",
	"misclang"		=> "Jazyk",
	"miscnoresult"		=> "Nenalezeny ��dn� v�sledky.",
	"miscsubdirs"		=> "Hledat podadres��e",
	"miscpermnames"		=> array("Pouze �ten�","�pravy","Zm�na hesla","�pravy & Zm�na hesla",
					"Administr�tor"),
	"miscyesno"		=> array("Ano","Ne","A","N"),
	"miscchmod"		=> array("Vlastn�k", "Skupina", "Ve�ejn�"),
);
?>