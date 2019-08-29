<?php

// Polish Language Module for v2.3 (translated by the ADAM SWIERCZ & QuiX project)

$GLOBALS["charset"] = "iso-8859-2";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "B��D(�DY)",
	"back"			=> "Z Powrotem",
	
	// root
	"home"			=> "Katalog domowy nie istnieje. Sprawd� swoje ustawienia.",
	"abovehome"		=> "Obecny katalog nie mo�e by� powy�ej katalogu domowego.",
	"targetabovehome"	=> "Katalog docelowy nie mo�e by� powy�ej katalogu domowego.",
	
	// exist
	"direxist"		=> "Ten katalog nie istnieje.",
	//"filedoesexist"	=> "This file already exists.",
	"fileexist"		=> "Ten plik nie istnieje.",
	"itemdoesexist"		=> "Ta pozycja ju� istnieje.",
	"itemexist"		=> "Ta pozycja nie istnieje.",
	"targetexist"		=> "Katalog docelowy nie istnieje.",
	"targetdoesexist"	=> "Pozycja docelowa ju� istnieje.",
	
	// open
	"opendir"		=> "Nie mog� otworzy� katalogu.",
	"readdir"		=> "Nie mog� odczyta� katalogu.",
	
	// access
	"accessdir"		=> "Nie masz dost�pu do tego katalogu.",
	"accessfile"		=> "Nie masz dost�pu do tego pliku.",
	"accessitem"		=> "Nie masz dost�pu do tej pozycji.",
	"accessfunc"		=> "Nie masz dost�pu do tej funkcji.",
	"accesstarget"		=> "Nie masz dost�pu do katalogu docelowego.",
	
	// actions
	"permread"		=> "Pobranie uprawnie� nie uda�o si�.",
	"permchange"		=> "Zmiana uprawnie� si� nie powiod�a.",
	"openfile"		=> "Otawrcie pliku si� nie powiod�o.",
	"savefile"		=> "Zapis pliku si� nie powiod�o.",
	"createfile"		=> "Utworzenie pliku si� nie powiod�o.",
	"createdir"		=> "Utworzenie katalogu si� nie powiod�o.",
	"uploadfile"		=> "Wrzucanie pliku na serwer si� nie powiod�o.",
	"copyitem"		=> "Kopiowanie si� nie powiod�o.",
	"moveitem"		=> "Przenoszenie si� nie powiod�o.",
	"delitem"		=> "Usuwanie si� nie powiod�o.",
	"chpass"		=> "Zmiana has�a nie powiod�a si�.",
	"deluser"		=> "Usuwanie u�ytkowika si� nie powiod�o.",
	"adduser"		=> "Dodanie u�ytkownika si� nie powiod�o.",
	"saveuser"		=> "Zapis u�ytkownika si� nie powiod�o.",
	"searchnothing"		=> "Musisz dostarczy� czego� do szukania.",
	
	// misc
	"miscnofunc"		=> "Funkcja niedost�pna.",
	"miscfilesize"		=> "Rozmiar pliku przekroczy� maksymaln� warto��.",
	"miscfilepart"		=> "Plik zosta� za�adowany tylko cz�ciowo.",
	"miscnoname"		=> "Musisz nada� nazw�.",
	"miscselitems"		=> "Nie zaznaczy�e� �adnej pozycji.",
	"miscdelitems"		=> "Jeste� pewny �e chcesz usun�� te (\"+num+\") pozycje?",
	"miscdeluser"		=> "Jeste� pewny �e chcesz usun�� u�ytkownika '\"+user+\"'?",
	"miscnopassdiff"	=> "Nowe has�o nie r�ni si� od obecnego.",
	"miscnopassmatch"	=> "Podane has�a r�ni� si�.",
	"miscfieldmissed"	=> "Opuszczono wa�ne pole.",
	"miscnouserpass"	=> "U�ytkownik i has�o s� niezgodne.",
	"miscselfremove"	=> "Nie mo�esz siebie usun��.",
	"miscuserexist"		=> "U�ytkownik ju� istnieje.",
	"miscnofinduser"	=> "U�ytkownika nie znaleziono.",
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "ZMIANA UPRAWNIE�",
	"editlink"		=> "EDYCJA",
	"downlink"		=> "DOWNLOAD",
	"uplink"		=> "KATALOG WY�EJ",
	"homelink"		=> "KATALOG DOMOWY",
	"reloadlink"		=> "OD�WIE�",
	"copylink"		=> "KOPIUJ",
	"movelink"		=> "PRZENIE�",
	"dellink"		=> "USU�",
	"comprlink"		=> "ARCHIWIZUJ",
	"adminlink"		=> "ADMINISTRUJ",
	"logoutlink"		=> "WYLOGUJ",
	"uploadlink"		=> "WRZU� PLIK NA SERWER - UPLOAD",
	"searchlink"		=> "SZUKAJ",
	
	// list
	"nameheader"		=> "Nazwa",
	"sizeheader"		=> "Rozmiar",
	"typeheader"		=> "Typ",
	"modifheader"		=> "Zmodyfikowano",
	"permheader"		=> "Prawa dost�pu",
	"actionheader"		=> "Akcje",
	"pathheader"		=> "�cie�ka",
	
	// buttons
	"btncancel"		=> "Zrezygnuj",
	"btnsave"		=> "Zapisz",
	"btnchange"		=> "Zmie�",
	"btnreset"		=> "Reset",
	"btnclose"		=> "Zamknij",
	"btncreate"		=> "Utw�rz",
	"btnsearch"		=> "Szukaj",
	"btnupload"		=> "Wrzu� na serwer",
	"btncopy"		=> "Kopiuj",
	"btnmove"		=> "Przenie�",
	"btnlogin"		=> "Zaloguj",
	"btnlogout"		=> "Wyloguj",
	"btnadd"		=> "Dodaj",
	"btnedit"		=> "Edycja",
	"btnremove"		=> "Usu�",
	
	// actions
	"actdir"		=> "Katalog",
	"actperms"		=> "Zmiana uprawnie�",
	"actedit"		=> "Edycja pliku",
	"actsearchresults"	=> "Rezultaty szukania",
	"actcopyitems"		=> "Kopiuj pozycje",
	"actcopyfrom"		=> "Kpiuj z /%s do /%s ",
	"actmoveitems"		=> "Przenie� pozycje",
	"actmovefrom"		=> "Przenie� z /%s do /%s ",
	"actlogin"		=> "Nazwa u�ytkownika",
	"actloginheader"	=> "Zaloguj si� by u�ywa� QuiXplorer",
	"actadmin"		=> "Administracja",
	"actchpwd"		=> "Zmie� has�o",
	"actusers"		=> "U�ytkownicy",
	"actarchive"		=> "Pozycje zarchiwizowane",
	"actupload"		=> "Wrzucanie na serwer- Upload",
	
	// misc
	"miscitems"		=> " -Ilo�c element�w",
	"miscfree"		=> "Wolnego miejsca",
	"miscusername"		=> "Nazwa u�ytkownika",
	"miscpassword"		=> "Has�o",
	"miscoldpass"		=> "Stare has�o",
	"miscnewpass"		=> "Nowe has�o",
	"miscconfpass"		=> "Potwierd� has�o",
	"miscconfnewpass"	=> "Potwierd� nowe has�o",
	"miscchpass"		=> "Zmie� has�o",
	"mischomedir"		=> "Katalog g��wny",
	"mischomeurl"		=> "URL Katalogu domowego",
	"miscshowhidden"	=> "Show hidden items",
	"mischidepattern"	=> "Hide pattern",
	"miscperms"		=> "Uprawnienia",
	"miscuseritems"		=> "(nazwa, katalog domowy, poka� pozycje ukryte, uprawnienia, czy aktywny)",
	"miscadduser"		=> "dodaj u�ytkownika",
	"miscedituser"		=> "edycja u�ytkownika '%s'",
	"miscactive"		=> "Aktywny",
	"misclang"		=> "J�zyk",
	"miscnoresult"		=> "Bez rezultatu.",
	"miscsubdirs"		=> "Szukaj w podkatalogach",
	"miscpermnames"		=> array("Tylko przegl�danie","Modyfikacja","Zmiana has�a","Modyfikacja i zmiana has�a",
					"Administrator"),
	"miscyesno"		=> array("Tak","Nie","T","N"),
	"miscchmod"		=> array("W�a�ciciel", "Grupa", "Publiczny"),
);
?>