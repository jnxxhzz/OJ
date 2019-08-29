<?php
  /**
   * This file is created
   * by jnxxhzz
   * @2019.03.28
  **/
?>

<?php $title="Register";?>
<?php
  include "header.php";
?>
<div class="am-g">
  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
    <h1 style="margin-top:40px; margin-bottom: 0px;">Register Page</h1>
    <hr>
    <form class="am-form am-form-horizontal" action="register.php" method="post" data-am-validator>
    <?php include_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/set_post_key.php"?>
    <div class="am-form-group">
      <label for="username" class="am-u-sm-4 am-form-label">
        <font color='red'><b>*</b></font>&nbsp;User ID:
      </label>
      <div class="am-u-sm-8">
        <input type="text" name="user_id" id="username" value="" placeholder="5-20 characters, unique" style="width:300px;" minlength="5" maxlength="20" pattern="^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~]{5,20}$" required/>
      </div>
    </div>
    <div class="am-form-group">
      <label for="pwd" class="am-u-sm-4 am-form-label">
        <font color='red'><b>*</b></font>&nbsp;Password:
      </label>
      <div class="am-u-sm-8">
        <input type="password" name="password" id="pwd" value="" placeholder="6-22 characters" style="width:300px;"
          pattern="^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~]{6,22}$" required/>
      </div>
    </div>
    <div class="am-form-group">
      <label for="rname" class="am-u-sm-4 am-form-label">
        <font color='red'><b>*</b></font>&nbsp;Repeat Password:
      </label>
      <div class="am-u-sm-8">
        <input type="password" id="rname" name="rptpassword"value="" style="width:300px;" placeholder="Repeat your password"
          data-equal-to="#pwd" required/>
      </div>
    </div>
    <div class="am-form-group">
      <label for="nc" class="am-u-sm-4 am-form-label">Nick Name: </label>
      <div class="am-u-sm-8">
        <input type="text" id="nc" name="nick" value="" style="width:300px;" placeholder="80 characters at most"/>
      </div>
    </div>
    <div class="am-form-group">
      <label for="school" class="am-u-sm-4 am-form-label">School: </label>
      <div class="am-u-sm-8">
        <input type="text" id="school" name="school"value="" style="width:300px;" placeholder="Your school" />
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-4 am-form-label">Level_system:</label>
      <div class="am-u-sm-8">
        <select data-am-selected name="level_system" style="width:300px;">
        <?php
          foreach ($level_choose_name as $i) {
        ?>
            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
        <?php
          }
        ?>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <!-- <a href="#" data-am-modal="{target:'#levelup_system_show'}">体系详情 </a> -->
        <div class="am-dropdown" data-am-dropdown>
          <a href="#" class="am-dropdown-toggle">等级详情</a>
          <div class="am-dropdown-content" style="min-width: 700px">
              <?php
              foreach ($levelup_system_show as $lvl_name_id => $level_up_name) {
                echo "<font size='4'>".$level_choose_name[$lvl_name_id]."体系结构</font>";
                foreach ($level_up_name as $detailed_id => $lvl_detailed) {
                  if ($detailed_id % 6 == 0) echo "<br/>";
                  if ($detailed_id != 0) echo "=>&nbsp;&nbsp;&nbsp;";
                  echo "<font size='3' color='".$levelup_system_show_color[$lvl_name_id][$detailed_id]."'>".$lvl_detailed."</font>&nbsp;&nbsp;";
                }
                echo "<br/>";
                echo "<br/>";
              }
            ?>
          </div>
        </div>
      </div>
    </div> 
    <div class="am-form-group">
      <label for="school" class="am-u-sm-4 am-form-label">Student ID: </label>
      <div class="am-u-sm-8">
        <input type="text" id="stu_id" name="stu_id"value="" style="width:300px;" placeholder="Your student ID" pattern="^[0-9]*$"/>
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-sm-4 am-form-label">Real Name:</label>
      <div class="am-u-sm-8">
        <input type="text" id='real_name' style="width:300px;" value="" name="real_name" placeholder="Your real name">
      </div>
    </div>
    <div class="am-form-group">
      <label for="email" class="am-u-sm-4 am-form-label">
        <font color='red'><b>*</b></font>&nbsp;Email:
      </label>
      <div class="am-u-sm-8">
        <input type="email" id="email" value="" name="email" style="width:300px;" placeholder="Your email"
          required/>
      </div>
    </div>
    <div class="am-from-group">
      <div class="am-cf am-u-sm-offset-4 am-u-sm-3 am-u-end">
            <input type="submit" name="submit" value="Register" class="am-btn am-btn-primary am-btn-sm am-fl am-btn-block" />
        </div>
    </div>
    </form>
  </div>
  <br>
  <br>
  <br>
  <br>
</div>
<?php include "footer.php" ?>


<!-- 升级体系结构 start -->

<div class="am-popup" id="levelup_system_show">
  <div class="am-popup-inner">
    <div class="am-popup-hd">
      <h4 class="am-popup-title">等级体系结构</h4>
      <span data-am-modal-close
            class="am-close">&times;</span>
    </div>
    <div class="am-popup-bd">
      <?php
          foreach ($levelup_system_show as $lvl_name_id => $level_up_name) {
          echo "<font size='4'>".$level_choose_name[$lvl_name_id]."体系结构</font>";
          foreach ($level_up_name as $detailed_id => $lvl_detailed) {
            if ($detailed_id % 4 == 0) echo "<br/>";
            if ($detailed_id != 0) echo "=>&nbsp;&nbsp;&nbsp;";
            echo "<font size='3' color='".$levelup_system_show_color[$lvl_name_id][$detailed_id]."'>".$lvl_detailed."</font>&nbsp;&nbsp;";
          }
          echo "<br/>";
          echo "<br/>";
        }
      ?>
    </div>
  </div>
</div>
<!-- 升级体系结构 end -->
