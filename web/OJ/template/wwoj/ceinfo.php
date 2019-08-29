<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.26
   * last modified
   * by yybird
   * @2016.03.26
  **/
?>

<?php 
  $title="Complitation Error Info";
  require_once("header.php");
?>

<div class="am-container">
  <div id='source'></div>
  <pre id='errtxt' class="alert alert-error"><?php echo $view_reinfo?></pre>
  <div id='errexp'>Explain:</div>
  <script>
    var i=0;
    var pats=new Array();
    var exps=new Array();
    pats[0]=/System\.out\.print.*%.*/
    exps[0]="Java中System.out.print用法跟C语言printf不同，请试用System.out.format";
    pats[1]=/.*没有那个文件或目录.*/
    exps[1]="服务器为Linux系统，不能使用windows下特有的非标准头文件。";
    pats[2]=/not a statement/
    exps[2]="检查大括号{}匹配情况，eclipse整理代码快捷键Ctrl+Shift+F";
    pats[3]=/class, interface, or enum expected/
    exps[3]="请不要将java函数（方法）放置在类声明外部，注意大括号的结束位置}";
    pats[4]=/asm.*java/
    exps[4]="请不要将java程序提交为C语言";
    pats[5]=/package .* does not exist/
    exps[5]="检测拼写，如：系统对象System为大写S开头";
    pats[6]=/possible loss of precision/
    exps[6]="赋值将会失去精度，检测数据类型，如确定无误可以使用强制类型转换";
    pats[7]=/incompatible types/
    exps[7]="Java中不同类型的数据不能互相赋值，整数不能用作布尔值";
    pats[8]=/illegal start of expression/
    exps[8]="字符串应用英文双引号(\")引起";
    pats[9]=/cannot find symbol/
    exps[9]="拼写错误或者缺少调用函数所需的对象如println()需对System.out调用";
    pats[10]=/';' expected/
    exps[10]="缺少分号。";
    pats[11]=/should be declared in a file named/
    exps[11]="Java必须使用public class Main。";
    pats[12]=/expected ‘.*’ at end of input/
    exps[12]="代码没有结束，缺少匹配的括号或分号，检查复制时是否选中了全部代码。";
    pats[13]=/invalid conversion from ‘.*’ to ‘.*’/
    exps[13]="隐含的类型转换无效，尝试用显示的强制类型转换如(int *)malloc(....)";
    pats[14]=/warning.*declaration of 'main' with no type/
    exps[14]="C++标准中，main函数必须有返回值";
    pats[15]=/'.*' was not declared in this scope/
    exps[15]="变量没有声明过，检查下是否拼写错误！";
    pats[16]=/main’ must return ‘int’/
    exps[16]="在标准C语言中，main函数返回值类型必须是int，教材和VC中使用void是非标准的用法";
    pats[17]=/printf.*was not declared in this scope/
    exps[17]="printf函数没有声明过就进行调用，检查下是否导入了stdio.h或cstdio头文件";
    pats[18]=/warning: ignoring return value of/
    exps[18]="警告：忽略了函数的返回值，可能是函数用错或者没有考虑到返回值异常的情况";
    pats[19]=/:.*__int64’ undeclared/
    exps[19]="__int64没有声明，在标准C/C++中不支持微软VC中的__int64,请使用long long来声明64位变量";
    pats[20]=/:.*expected ‘;’ before/
    exps[20]="前一行缺少分号";
    pats[21]=/ .* undeclared \(first use in this function\)/
    exps[21]="变量使用前必须先进行声明，也有可能是拼写错误，注意大小写区分。";
    pats[22]=/scanf.*was not declared in this scope/
    exps[22]="scanf函数没有声明过就进行调用，检查下是否导入了stdio.h或cstdio头文件";
    pats[23]=/memset.*was not declared in this scope/
    exps[23]="memset函数没有声明过就进行调用，检查下是否导入了stdlib.h或cstdlib头文件";
    pats[24]=/malloc.*was not declared in this scope/
    exps[24]="malloc函数没有声明过就进行调用，检查下是否导入了stdlib.h或cstdlib头文件";
    pats[25]=/puts.*was not declared in this scope/
    exps[25]="puts函数没有声明过就进行调用，检查下是否导入了stdio.h或cstdio头文件";
    pats[26]=/gets.*was not declared in this scope/
    exps[26]="gets函数没有声明过就进行调用，检查下是否导入了stdio.h或cstdio头文件";
    pats[27]=/str.*was not declared in this scope/
    exps[27]="string类函数没有声明过就进行调用，检查下是否导入了string.h或cstring头文件";
    pats[28]=/‘import’ does not name a type/
    exps[28]="不要将Java语言程序提交为C/C++,提交前注意选择语言类型。";
    pats[29]=/asm’ undeclared/
    exps[29]="不允许在C/C++中嵌入汇编语言代码。";
    pats[30]=/redefinition of/
    exps[30]="函数或变量重复定义，看看是否多次粘贴代码。";
    pats[31]=/expected declaration or statement at end of input/
    exps[31]="程序好像没写完，看看是否复制粘贴时漏掉代码。";
    pats[32]=/warning: unused variable/
    exps[32]="警告：变量声明后没有使用，检查下是否拼写错误，误用了名称相似的变量。";
    pats[33]=/implicit declaration of function/
    exps[33]="函数隐性声明，检查下是否导入了正确的头文件。";
    pats[34]=/too .* arguments to function/
    exps[34]="函数调用时提供的参数数量不对，检查下是否用错参数。";
    pats[35]=/expected ‘=’, ‘,’, ‘;’, ‘asm’ or ‘__attribute__’ before ‘namespace’/
    exps[35]="不要将C++语言程序提交为C,提交前注意选择语言类型。";
    pats[36]=/stray ‘\\[0123456789]*’ in program/
    exps[36]="中文空格、标点等不能出现在程序中注释和字符串以外的部分。编写程序时请关闭中文输入法。请不要使用网上复制来的代码。";
    pats[37]=/division by zero/
    exps[37]="除以零将导致浮点溢出。";
    pats[38]=/cannot be used as a function/
    exps[38]="变量不能当成函数用，检查变量名和函数名重复的情况，也可能是拼写错误。";
    pats[39]=/format .* expects type .* but argument .* has type .*/
    exps[39]="scanf/printf的格式描述和后面的参数表不一致，检查是否多了或少了取址符“&”，也可能是拼写错误。";
    pats[40]=/类.*是公共的，应在名为 .*java 的文件中声明/
    exps[40]="Java语言提交只能有一个public类，并且类名必须是Main，其他类请不要用public关键词";
    pats[41]=/expected ‘\)’ before ‘.*’ token/
    exps[41]="缺少右括号";
    pats[42]=/找不到符号/
    exps[42]="使用了未定义的函数或变量，检出拼写是否有误，不要使用不存在的函数，Java调用方法通常需要给出对象名称如list1.add(...)。Java方法调用时对参数类型敏感，如:不能将整数(int)传送给接受字符串对象(String)的方法";
    pats[43]=/需要为 class、interface 或 enum/
    exps[43]="缺少关键字，应当声明为class、interface 或 enum";
    pats[44]=/符号： 类 .*List/
    exps[44]="使用教材上的例子，必须将相关类的代码一并提交，同时去掉其中的public关键词";
    pats[45]=/方法声明无效；需要返回类型/
    exps[45]="只有跟类名相同的方法为构造方法，不写返回值类型。如果将类名修改为Main,请同时修改构造方法名称。";
    pats[46]=/expected.*before.*&.*token/
    exps[46]="不要将C++语言程序提交为C,提交前注意选择语言类型。";
    pats[47]=/非法的表达式开始/
    exps[47]="请注意函数、方法的声明前后顺序，不能在一个方法内出现另一个方法的声明。";
    pats[48]=/需要 ';'/
    exps[48]="上面标注的这一行，最后缺少分号。";
    pats[49]=/extra tokens at end of #include directive/
    exps[49]="include语句必须独立一行，不能与后面的语句放在同一行";
    pats[50]=/int.*hasNext/
    exps[50]="hasNext() 应该改为nextInt()";
    pats[51]=/unterminated comment/
    exps[51]="注释没有结束，请检查“/*”对应的结束符“*/”是否正确";
    pats[52]=/expected ‘=’, ‘,’, ‘;’, ‘asm’ or ‘__attribute__’ before ‘{’ token/
    exps[52]="函数声明缺少小括号()，如int main()写成了int main";
    pats[53]=/进行语法解析时已到达文件结尾/
    exps[53]="检查提交的源码是否没有复制完整，或者缺少了结束的大括号";
    pats[54]=/subscripted value is neither array nor pointer/
    exps[54]="不能对非数组或指针的变量进行下标访问";
    pats[55]=/expected expression before ‘%’ token/
    exps[55]="scanf的格式部分需要用双引号引起";
    pats[56]=/ expected expression before ‘.*’ token/
    exps[56]="参数或表达式没写完";
    function explain(){
    //alert("asdf");
    var errmsg=document.getElementById("errtxt").innerHTML;
    var expmsg="辅助解释：<br>";
    for(var i=0;i<pats.length;i++){
    var pat=pats[i];
    var exp=exps[i];
    var ret=pat.exec(errmsg);
    if(ret){
    expmsg+=ret+":"+exp+"<br>";
    }
    }
    document.getElementById("errexp").innerHTML=expmsg;
    //alert(expmsg);
    }
    explain();
  </script>
</div> <!-- /container -->  
<script>
  $("#source").load("showsource.php?id=<?php echo $id?> #main");
</script>
<?php require_once("footer.php") ?>