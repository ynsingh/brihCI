<!--@filename staffstrengthlist.php  @author Nagendra Kumar Singh(nksinghiitk@gmail.com) 
    @filename staffstrengthlist.php  @author Neha Khullar(nehukhullar@gmail.com)
    @author Manorama Pal(palseema30@gmail.com)

-->

<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
    <head>
        <title>Welcome to TANUVAS</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css">
        <script type="text/javascript" src="<?php echo base_url();?>assets/datepicker/jquery-1.12.4.js" ></script>
        <style type="text/css" media="print">
            @page {
                size: auto;   /* auto is the initial value */
                margin:0;  /* this affects the margin in the printer settings */
            }
        </style>
        <script>
            function printDiv(printme) {
                var printContents = document.getElementById(printme).innerHTML; 
                //alert("printContents==="+printContents);
                var originalContents = document.body.innerHTML;      
                document.body.innerHTML = "<html><head><title></title></head><body style='width:100%;' ><img src='<?php echo base_url(); ?>uploads/logo/logotanuvas.jpeg' alt='logo' style='width:100%;height:100px' >"+" <div style='width:100%;height:100px;'>  " + printContents + "</div>"+"</body>";
                window.print();     
                document.body.innerHTML = originalContents;
            }
            
            $(document).ready(function(){
                
                /****************************************** start of uofficer********************************/
                $('#wtype').on('change',function(){
                    var workt = $(this).val();
                    //alert(sc_code);
                    if(workt == ''){
                        $('#uoff').prop('disabled',true);
                   
                    }
                    else{
                        $('#uoff').prop('disabled',false);
                        $.ajax({
                            url: "<?php echo base_url();?>sisindex.php/report/getspuolist",
                            type: "POST",
                            data: {"worktype" : workt},
                            dataType:"html",
                            success:function(data){
                            //alert("data==1="+data);
                                $('#uoff').html(data.replace(/^"|"$/g, ''));
                                                 
                            },
                            error:function(data){
                                //alert("data in error==="+data);
                                alert("error occur..!!");
                 
                            }
                        });
                    }
                }); 
                /******************************************end of uofficer********************************/
                /****************************************** start of designation********************************/
                $('#uoff').on('change',function(){
                    var wtcode = $('#wtype').val();
                    var uoid = $('#uoff').val();
                    var wtuoid = wtcode+","+uoid;
                    if(uoid == ''){
                        $('#desig').prop('disabled',true);
                   
                    }
                    else{
                        $('#desig').prop('disabled',false);
                        $.ajax({
                            url: "<?php echo base_url();?>sisindex.php/report/getuo_postlist",
                            type: "POST",
                            data: {"wtuoid" : wtuoid},
                            dataType:"html",
                            success:function(data){
                            //alert("data==1="+data);
                                $('#desig').html(data.replace(/^"|"$/g, ' '));
                            },
                            error:function(data){
                                //alert("data in error==="+data);
                                alert("error occur..!!");
                 
                            }
                        });
                    }
                }); 
                /******************************************end of department********************************/
            });  
            
            function verify(){
                var w=document.getElementById("desig").value;
                var x=document.getElementById("wtype").value;
                var y=document.getElementById("uoff").value;
                if((x == 'null' && y == 'null') || (x == '' && y == '')||(y == 'null')||(x == 'null')){
                    alert("please select at least any two combination for search !!");
                    return false;
                };
            }
        </script>     
       
    
</head>
<body>
    <?php $this->load->view('template/header'); ?>
    <form action="<?php echo site_url('report/staffvacposition');?>" id="myForm" method="POST" class="form-inline">
        <table width="100%" border="0">
            <tr style="font-weight:bold;width:100%;">
                <td>  Select Working Type
                    <select name="wtype" id="wtype" style="width:250px;"> 

			<?php if  (!empty($this->wtyp)){ ?>
                        <option value="<?php echo $this->wtyp; ?>" > <?php echo $this->wtyp; ?></option>
                        <?php  }else{ ?>
                      <option value="" disabled selected>------- Select Working Type -------</option>
                          <?php  } ?>

                      <option value="Teaching">Teaching</option>
                      <option value="Non Teaching"> Non Teaching</option>
                       
                    </select> 
                                    
                </td> 
                <td>   Select UO
                    <select name="uoff" id="uoff" style="width:270px;"> 
			<?php if  ((!empty($this->uolt))&&($this->uolt != 'All')){ ?>
                        <option value="<?php echo $this->uolt; ?>" > <?php echo $this->lgnmodel->get_listspfic1('authorities', 'name', 'id',$this->uolt)->name ." ( ". $this->lgnmodel->get_listspfic1('authorities', 'code', 'id',$this->uolt)->code ." )"; ?></option>
                        <?php  }else{ ?>
                      <option value="" disabled selected>------ Select University officer -----</option>
                          <?php  } ?>

                     <!-- <option value="All" >All</option> -->
                    </select> 
                </td>
                <td> Select Post
                    <select name="desig" id="desig" style="width:250px;"> 
			<?php if  ((!empty($this->desigm))&&($this->desigm != 'All')){ ?>
                        <option value="<?php echo $this->desigm; ?>" > <?php echo $this->commodel->get_listspfic1('designation', 'desig_name', 'desig_id',$this->desigm)->desig_name ." ( ". $this->commodel->get_listspfic1('designation', 'desig_code', 'desig_id',$this->desigm)->desig_code ." )"; ?></option>
                        <?php  }else{ ?>
                      <option value="" disabled selected>----------- Select Post------</option>
                         <?php  } ?>

                      <!--<option value="All" >All</option> -->
                    </select> 
                </td>
                <td valign="bottom">
                    <input type="submit" name="filter" id="crits" value="Search"  onClick="return verify()"/>
                </td>
            </tr>    
    </table>          
    <table width="100%">
        <tr style=" background-color: graytext;">
            <td>
                <img src='<?php echo base_url(); ?>uploads/logo/print1.png' alt='print'  onclick="javascript:printDiv('printme')" style='width:30px;height:30px;' title="Click for print" >  
                <div style="margin-left:500px;"><b>View Staff Vacancy Position Details</b></div>
            </td>      
      <!-- <div>
       <//?php
       echo "<td align=\"center\" width=\"100%\">";
       echo "<b>View Staff Vacancy Position Details</b>";
       echo "</td>";
       ?>

        <div> -->

        <?php //echo anchor('staffmgmt/staffprofile/', "Add Profile" ,array('title' => 'Add staff profile ' , 'class' => 'top_parent'));
        //$help_uri = site_url()."/help/helpdoc#ViewEmployeeList";
       // echo "<a target=\"_blanik\" href=$help_uri><b style=\"float:right;position:absolute;margin-left:54%\">Click for Help</b></a>";
        ?>
            <div>
                <?php echo validation_errors('<div class="isa_warning">','</div>');?>
                <?php echo form_error('<div class="isa_error">','</div>');?>

                <?php if(isset($_SESSION['success'])){?>
                    <div class="isa_success"><?php echo $_SESSION['success'];?></div>
                <?php
                };
                ?>
                 <?php if(isset($_SESSION['err_message'])){?>
                    <div  class="isa_error"><?php echo $_SESSION['err_message'];?></div>
                <?php
                };
                ?>

            </div>
        </tr>
    </table>
<div id="printme" align="left" style="width:100%;">
<div class="scroller_sub_page">
        <table class="TFtable" >
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Depart/Scheme Name</th>
                    <th>Sanctioned Strength</th>
                    <th>Position</th>
                    <th>Vacancy</th>
                    <th>Remark</th>
                <!--    <th>Designation</th>
                    <th>University Officer Control</th>
                    <th>Department Name</th>
                    <th>Scheme Name</th>
                    <th>Specialisation(Major Subject)</th> -->
                    <!--<th>Employee Post</th>-->
                   <!-- <th>Pay Band</th>-->
                 <!--   <th>E-Mail ID</th>
                    <th>Contact No</th>
                    <th>Aadhaar No</th>
                    <th>Action</th>
                    -->
                </tr>
            </thead>
            <tbody>
                <?php $serial_no = 1;
		$ouoid = 0;
		$opid = 0;
		
               if( count($records) ):  ?>
                    <?php foreach($records as $record){
//                        print_r($record);
			if($opid !=$record->sp_emppost){
                        echo "<tr><td colspan=6 align=left><b> Name of the Post : ";
			//echo $record->sp_grppost;
                        echo $this->commodel->get_listspfic1('designation','desig_name','desig_id',$record->sp_emppost)->desig_name;
//			echo $this->commodel->get_listspfic1('Department','dept_name','dept_id',$record->sp_dept)->dept_name;
//			echo " ( ". $this->commodel->get_listspfic1('Department','dept_code','dept_id',$record->sp_dept)->dept_code ." )";
                        echo "</b></td></tr>";
			$opid = $record->sp_emppost;
                        }
			if($ouoid !=$record->sp_uo){
			echo "<tr>";
			echo "<td colspan=6 style=\"text-align:center;\">";
			echo " <b> UO CONTROL : ";
			echo $this->lgnmodel->get_listspfic1('authorities','name','id' ,$record->sp_uo)->name;
			echo " ( ".$this->lgnmodel->get_listspfic1('authorities','code','id' ,$record->sp_uo)->code." )";
			echo "</b></td>";
			echo "</tr>";
			$ouoid=$record->sp_uo;
			$serial_no = 1;
			}
			echo "<tr>";
			echo "<td>". $serial_no++ ." </td>";
                        echo "<td> <b>".$this->commodel->get_listspfic1('Department','dept_name','dept_id',$record->sp_dept)->dept_name ;
			echo "(".$this->commodel->get_listspfic1('Department','dept_code','dept_id',$record->sp_dept)->dept_code.")"."</b>" ;
			echo "<br>";
			echo $this->sismodel->get_listspfic1('scheme_department','sd_name','sd_id',$record->sp_schemecode)->sd_name ;
			echo "</td>";
			echo "<td>". $record->sp_sancstrenght ." </td>";
			echo "<td> $record->sp_position</td>";
			echo "<td> $record->sp_vacant</td>";
			echo "<td> $record->sp_remarks</td>";
?>
                        </tr>
                    <?php }; ?>
                <?php else : ?>
                    <td colspan= "13" align="center"> No Records found...!</td>
                <?php endif;?>
                </tbody>
        </table>              
        </div><!------scroller div------>
        </div><!------print div------>
        </form>
        <p> &nbsp; </p>
        <div align="center">  <?php $this->load->view('template/footer');?></div>
 
    </body>
</html>
