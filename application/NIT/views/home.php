<!DOCTYPE html>
<html>
    <head>
        <title>Welcome to NIT Sikkim</title>
	<meta charset="utf-8">
	<title>NIT Sikkim:offline payment</title>
		<!--<link rel="shortcut icon" href="<?php //echo base_url('assets/images'); ?>/index.jpg">-->
	<link rel="icon" href="<?php echo base_url('uploads/logo'); ?>/nitsindex.png" type="image/png">	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css">

<style>
body{font-family: "Helvetica Neue","Lucida Grande","Helvetica Neue",Arial,sans-serif;}
</style>


    </head>
<body>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css">
<?php 
	$this->load->view('template/header');

		 echo "<table width=\"100%\">";
		 echo "<tr>";
		 echo "<td align=\"left\" width=\"33%\"><b>";
		 echo anchor('profile/viewprofile', 'View Profile', array('title' => 'View Profile', 'class' => 'top_parent'));
                 echo "</b></td>";
                 echo "<td align=\"center\" width=\"34%\">";
                 echo "<b></b>";
                 echo "</td>";
                 echo "<td align=\"right\" width=\"33%\"><b>";
		 echo anchor('profile/changepasswd', 'Change Password', array('title' => 'Change Password', 'class' => 'top_parent'));
		 echo "</b></td>";
                 echo "</tr>";
		 echo "</table>";

                ?>

<div>

<div class="scroller">


<?php
	//echo "</br>";
	echo "<table width='100%'><tr ><td valign=\"top\">";
	echo "<table border=\"1\" style=\"text-align:center;color: black;  border-collapse:collapse; border:1px solid #BBBBBB;\">";
        echo "<tr style=\"text-align:center; font-weight:bold; background-color:#66C1E6;\">";
        echo "<td style=\"padding: 8px 8px 8px 20px; font-size:17px;\">";
	echo "Institute Profile";
        echo "</td>";
        echo "</tr>";
	echo "<tr>";
	echo "<td>";
        echo "<table width=\"100%\" border=\"0\" style=\"color: black; \">";
	echo "<table style=\"text-align:center;padding: 8px 8px 8px 20px;\">";
        echo "<tbody align=\"left\">";


      	echo "<tr><td style=\"padding: 0px 8px 8px 0px;\"><b>Institute Code</b></td><td>";
	echo $this->result->org_code;
	echo "</td></tr>";
        echo "<tr><td style=\"padding: 0px 8px 8px 0px;\"><b>Institute Name</b></td><td>";
	echo $this->result->org_name;
	echo "</td></tr>";
     	echo "<tr><td style=\"padding: 0px 8px 8px 0px;\"><b>Institute Type</b></td><td>";
	echo $this->result->org_type;
	echo "</td></tr>";
        echo "<tr><td style=\"padding: 0px 8px 8px 0px;\"><b>Institute Address</b></td><td>";
	echo $this->result->org_address1;
	echo "\t";
	echo $this->result->org_address2;echo ",";echo $this->result->org_city;echo "";echo $this->result->org_state;echo ","; echo $this->contryname->name; echo ","; echo $this->result->org_pincode;
	echo "</td></tr>";
        echo "<tr><td style=\"padding: 8px 8px 8px 0px;\"><b>Institute Email</b></td><td>";
	echo $this->result->org_email;
	echo "</td></tr>";
        echo "<tr><td style=\"padding: 8px 8px 8px 0px;\"><b>Institute Fax</b></td><td>";
	echo $this->result->org_fax;
	echo "</td></tr>";
        echo "<tr><td style=\"padding: 8px 8px 8px 0px;\"><b>Institute Phone</b></td><td>";
	echo $this->result->org_phone;
	echo "</td></tr>";
        echo "<tr><td style=\"padding: 8px 8px 8px 0px;\"><b>Institute Affilation</b></td><td>";
	echo $this->result->org_affiliation;
	echo "</td></tr>";
        echo "<tr><td style=\"padding: 8px 8px 8px 0px;\"><b>Institute Administrator</b></td><td>";
	echo $this->result->org_adminfn;echo "\t";echo $this->result->org_adminln;echo ",";echo "\t";echo $this->result->org_admindesig;
	echo "</td></tr>";
     	echo "</tbody>";
	echo "</table>";
	echo "</td>";
	echo "</tr>";
        echo "</table>";

	echo "</td><td valign=\"top\" >";

	echo "<table border=\"1\" style=\"color: black;  border-collapse:collapse; border:1px solid #BBBBBB;  \">";
        echo "<tr style=\"text-align:left; font-weight:bold; background-color:#66C1E6;\">";
        echo "<td style=\"text-align:center;padding: 8px 8px 8px 20px; text-align:center;font-size:17px;\">";
        echo "Institute Program and Seat";
        echo "</td>";
		  echo "</tr>";
        echo "<tr>";
        echo "<td>";
        echo "<table width=\"100%\" border=\"0\" style=\"color: black; border-collapse:collapse;\">";

        echo "<table style=\"padding: 8px 8px 8px 20px;text-align:center;\">";
		  echo "<table class=\"TFtable\" style=\"padding: 8px 8px 8px 0px;\">";
        echo "<tbody align=\"left\">";
	/*echo "<tr style='text-align:center;font-weight:bold; background-color:#66C1E6;padding: 8px 8px 8px 20px; text-align:center;font-size:17px;'>
		<td colspan=5><b>Institute Name</b></td></tr>";
	$orgid=0;
	foreach($this->prgseat as $row){
		if($orgid != $row->prg_scid){
			$scname=$this->commodel->get_listspfic1('org_profile','org_name','org_id',$row->prg_scid)->org_name;
			echo "<tr><td style=\"text-align:center;font-weight:bold; background-color:#66C1E6;padding: 8px 8px 8px 20px; text-align:center;font-size:17px;\" colspan=5> $scname </td></tr>";
		$orgid = $row->prg_scid;
		}
	}*/
	
		echo "<tr><td style=\"padding: 8px 8px 8px 0px; background-color:#A9A9A9;\"><b>Program Branch</b></td><td style=\"padding: 8px 8px 8px 0px; background-color:#A9A9A9;\"><b>Seat</b></td></tr>";
	$prgcatid=0;
	foreach($this->prgseat as $row){
		if($prgcatid != $row->prg_category){
	    //echo "<tr align=\"center\">";
	    	echo "<td style=\"text-align:left; font-weight:bold; background-color:#b2e0f2;\">"; echo "<b>Programme Category : </b>";
		//echo $this->commodel->get_listspfic1('programcategory','prgcat_name','prgcat_id',$row->subp_prgcat)->prgcat_name; echo"</td>";
		echo	$prgcatname = $this->commodel->get_listspfic1('programcategory','prgcat_name','prgcat_id',$row->prg_category)->prgcat_name;
			echo "</td>";
			echo "<td style=\"text-align:left; font-weight:bold; background-color:#b2e0f2;\"><b>Program Name :</b>";
			echo " &nbsp"; 
	      echo "$row->prg_name</td>";
	    echo "</tr>";

	$prgcatid = $row->prg_category;
	$srno = 1;	
	}
		
	echo "<td style=\"padding: 8px 8px 8px 0px;\">$row->prg_branch</td><td style=\"padding: 8px 8px 8px 0px;\">$row->prg_seat</td></tr>";
	//echo "<tr><td><b>Program Category</b></td><td><b>Program Name</b></td><td><b>Program Branch</b></td> <td><b>Seat</b></td></tr>";
	//foreach($this->prgseat as $row){
		//$scname=$this->commodel->get_listspfic1('study_center','sc_name','sc_id',$row->prg_scid)->sc_name;
		//  $prgcatname = $this->commodel->get_listspfic1('programcategory','prgcat_name','prgcat_id',$row->prg_category)->prgcat_name;
		//echo "<tr><td style=\"padding: 8px 8px 8px 0px;\"> $scname </td>
		//echo "<td style=\"padding: 8px 8px 8px 0px;\">$prgcatname</td>
		//echo "<td style=\"padding: 8px 8px 8px 0px;\">$row->prg_name</td> <td style=\"padding: 8px 8px 8px 0px;\">$row->prg_branch</td><td style=\"padding: 8px 8px 8px 0px;\">$row->prg_seat</td></tr>";
		
	}
        echo "</tbody>";
        echo "</table>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";

	echo "</td><td valign=\"top\">";

        echo "<table border=\"1\" style=\"color: black;  border-collapse:collapse; border:1px solid #BBBBBB;\">";
        echo "<tr style=\"text-align:left; font-weight:bold; background-color:#66C1E6;\">";
        echo "<td style=\"padding: 8px 8px 8px 20px; text-align:center;font-size:17px;\">";
        echo "Institute Program and Fees";
        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>";
        echo "<table width=\"100%\" border=\"0\" style=\"color: black; border-collapse:collapse;\">";
        echo "<table style=\"padding: 8px 8px 8px 20px;\">";
        echo "<tbody align=\"left\">";
	echo "<tr><td style=\"padding: 8px 8px 8px 0px;\"> <b>Program Name</b></td><td style=\"padding: 8px 8px 8px 0px;\"><b>Semester</b></td><td style=\"padding: 8px 8px 8px 0px;\"><b>Category</b></td> <td style=\"padding: 8px 8px 8px 0px;\"><b>Gender</b></td> <td style=\"padding: 8px 8px 8px 0px;\"><b> Fees</b></td></tr>";
	if(!empty($frecord)){
		foreach($frecord as $ta){
			echo "<tr>";
			foreach($ta as $ta1){
				echo "<td style=\"padding: 8px 8px 0px 0px;\">";
				echo $ta1;
				echo "</td>";
 			}
			echo "</tr>";
		}
	}
        echo "</tbody>";
        echo "</table>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";

	echo "</td>";
	echo "</tr></table>";
echo "<br>";echo "<br>";echo "<br>";
?>
</div>

</div><!------scroller div------>

<?php $this->load->view('template/footer'); ?>
	
    </body>
</html>
