<html>
<head>
<title>Brihaspati</title>

<style>
body{padding:0px;margin:0px;background-color:#DDDDDD;}
#bas{background-color:#067eb7;color:white;font-size:50px;text-align:center;}
#loa{background-color:#DCDCDC;font-size:30px;text-align:center;}
#footer{background-color:#067eb7;height:20px;}
#footer span{font-size:20px;text-align:center;color:white;}
table tr td a{text-decoration:none;font-size:20px;color:black;}
</style>
</head>
<body>

<div id="bas">
	<span>Brihaspati Automation System</span>
</div>

</br>

<div id="loa">
	<span>List of Application</span>
</div>

</br>
<?php
//$dir = opendir('SLCMS');
//while(($file = readdir($dir)) !== FALSE ){
//	echo $file."</br>";
//}
?>
<table style="width:20%;" align="center" border=1>
	<tr align="center">
		<td><a href="sisindex.php">SIS</a></td>
	</tr>
	<tr align="center">
		<td><a href="slcmsindex.php">SLCMS</a></td>
	</tr>
	<tr align="center">
		<td><a href="">BHR</a></td>
	</tr>
</table>

</br></br>

<div id="footer">
<center>
	<span>2017 © <a href="#">Brihaspati ERP Team IIT Kanpur</a> All rights reserved. <a href="<?php echo base_url(); ?>brihaspati-license.txt">Click here </a> for distribution license</span>
</center>
</div>

</body>
</html>
