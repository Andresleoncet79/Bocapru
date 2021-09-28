<?php
////////////////////////////////////////////////////////////////////////////////
//BOCA Online Contest Administrator
//    Copyright (C) 2003-2012 by BOCA Development Team (bocasystem@gmail.com)
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//    You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
////////////////////////////////////////////////////////////////////////////////
// Last modified 05/aug/2012 by cassio@ime.usp.br

ob_start();
header ("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-Type: text/html; charset=utf-8");
session_start();
$_SESSION["loc"] = dirname($_SERVER['PHP_SELF']);
if($_SESSION["loc"]=="/") $_SESSION["loc"] = "";
$_SESSION["locr"] = dirname(__FILE__);
if($_SESSION["locr"]=="/") $_SESSION["locr"] = "";

require_once("globals.php");
require_once("db.php");

if (!isset($_GET["name"])) {
	if (ValidSession())
	  DBLogOut($_SESSION["usertable"]["contestnumber"], 
		   $_SESSION["usertable"]["usersitenumber"], $_SESSION["usertable"]["usernumber"],
		   $_SESSION["usertable"]["username"]=='admin');
	session_unset();
	session_destroy();
	session_start();
	$_SESSION["loc"] = dirname($_SERVER['PHP_SELF']);
	if($_SESSION["loc"]=="/") $_SESSION["loc"] = "";
	$_SESSION["locr"] = dirname(__FILE__);
	if($_SESSION["locr"]=="/") $_SESSION["locr"] = "";
}
if(isset($_GET["getsessionid"])) {
	echo session_id();
	exit;
}

$coo = array();
if(isset($_COOKIE['biscoitobocabombonera'])) {
  $coo = explode('-',$_COOKIE['biscoitobocabombonera']);
  if(count($coo) != 2 ||
     strlen($coo[1])!=strlen(myhash('xxx')) ||
     !is_numeric($coo[0]) ||
     !ctype_alnum($coo[1]))
    $coo = array();
}
if(count($coo) != 2)
  setcookie('biscoitobocabombonera',time() . '-' . myhash(time() . rand() . time() . rand()),time() + 240*3600);

ob_end_flush();

require_once('version.php');

?>
<title>BOCA</title>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet href="Css.php" type="text/css">
<script language="JavaScript" src="sha256.js"></script>
<script language="JavaScript">
function computeHASH()
{
	var userHASH, passHASH;
	userHASH = document.form1.name.value;
	passHASH = js_myhash(js_myhash(document.form1.password.value)+'<?php echo session_id(); ?>');
	document.form1.name.value = '';
	document.form1.password.value = '                                                                                 ';
	document.location = 'index.php?name='+userHASH+'&password='+passHASH;
}
</script>
<?php
if(function_exists("globalconf") && function_exists("sanitizeVariables")) {
  if(isset($_GET["name"]) && $_GET["name"] != "" ) {
	$name = $_GET["name"];
	$password = $_GET["password"];
	$usertable = DBLogIn($name, $password);
	if(!$usertable) {
		ForceLoad("index.php");
	}
	else {
		if(($ct = DBContestInfo($_SESSION["usertable"]["contestnumber"])) == null)
			ForceLoad("index.php");
		if($ct["contestlocalsite"]==$ct["contestmainsite"]) $main=true; else $main=false;
		if(isset($_GET['action']) && $_GET['action'] == 'transfer') {
			echo "TRANSFER OK";
		} else {
			if($main && $_SESSION["usertable"]["usertype"] == 'site') {
				MSGError('Direct login of this user is not allowed');
				unset($_SESSION["usertable"]);
				ForceLoad("index.php");
				exit;
			}
			echo "<script language=\"JavaScript\">\n";
			echo "document.location='" . $_SESSION["usertable"]["usertype"] . "/index.php';\n";
			echo "</script>\n";
		}
		exit;
	}
  }
} else {
  echo "<script language=\"JavaScript\">\n";
  echo "alert('Unable to load config files. Possible file permission problem in the BOCA directory.');\n";
  echo "</script>\n";
}
?>
</head>
<body onload="document.form1.name.focus()">
<link rel="stylesheet" href="estilos.css">
 <section id="pantalla-dividida">

<div class="izquierda">
  <center>
    <h1>
    <font face="Arial" size="20" span class="color1">BOCA</font>
    </h1>
  <p>
  <font face="Arial" size="3" span class="color2">SOFTWARE OF MANAGEMENT OF PROGRAMMING MARATHONS</font>
  </p>
<img span class="imagen" src="índice.jpeg" alt="">
</center>
</div>
<div class="derecha">
<tr align="" valign=""> 
    <td> 
      <form name="form1" action="javascript:computeHASH()">
      <div id="general">
        <center>
          <h4>
          <font  face="Arial" size="5" span class="color3">Sign In</font>
          </h4>
        </center>
              </td>
            </tr>
            <tr>
              <td valign="top"> 
                <table align="center">
                  <tr> 
                    <td> 
                      <input type="text" size="20" span class="colorName" name="name" placeholder="Email of phone number">
                    </td>
                  </tr>
                  <tr> 
                    <td> 
                      <input type="password" span class="colorPass" size="20" name="password" placeholder="Password">
                    </td>
                  </tr>

                <tr> 
                    <td>
                      <center>
                      <input type="submit" span class ="colorBoton" name="Submit" value="Sign In">
                      </center> 
                    </td>
                  </tr>
                  <tr> 
                    <td>
                      
                      <input type="checkbox"  name="checkbox" id="checkbox1">
                      <label for="checkbox1" span class ="colorCheck">Remember data</label>
                    </td>
                  </tr>

                  <tr> 
                    <td>
                      <a href="https://blogs.iteso.mx/acm/primera-fase-gran-premio-de-mexico-centroamerica/d-sistemas-utilizados-para-el-control-de-los-concursos/">Need Help</a>
                    </td>
                  </tr>


                </table>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </form>
    </td>
  </tr>
</table>
</div>

 </section>  

<?php?>
