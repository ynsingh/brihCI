<!--@name mapsubpre.php  @author Nagendra Kumar Singh(nksinghiitk@gmail.com)  -->

 <?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<html>
<title>Subject Semester Program with Department List</title>
    <head>    
       	<?php $this->load->view('template/header'); ?>
     
	<?php //$this->load->view('template/menu');?> 
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/1.12.4jquery.min.js" ></script>
	<script>
	function getsubname(branch){
		var branch = branch;
		//alert (branch);
                $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>slcmsindex.php/map/subjlist",
                data: {"programname" : branch},
                dataType:"html",
                success: function(data){
                $('#spreq_subid').html(data.replace(/^"|"$/g, ''));
                }
            }); 
        }

	/*function getbranchname(branch){
                var branch = branch;
                //alert(branch);
                $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>slcmsindex.php/map/branchlist",
                data: {"programname" : branch},
                dataType:"html",
                success: function(data){
                //alert(data);
                $('#spreq_prgid').html(data.replace(/^"|"$/g, ''));
                }
            }); 
	}

	function getsubject(subj){
            var subj = subj;
                $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>slcmsindex.php/map/subjectlist",
                data: {"spreq_prgid" : subj},
                dataType:"html",
                success: function(data){
                $('#spreq_subid').html(data.replace(/^"|"$/g, ''));
                }
             });
	}*/

        function getpapername(combid){
                var subid = $('#spreq_subid').val();
                var pid = $('#programname').val();
                var combid = subid+","+pid;
		//alert(combid);
                $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>slcmsindex.php/map/paperlist",
                data: {"sub_prog" : combid},
                dataType:"html",
                success: function(data){
                $('#spreq_subpid').html(data.replace(/^"|"$/g, ''));
                }
            });
	}

	 function getprepaper(subpre){
             var subpre = subpre;
                $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>slcmsindex.php/map/paperprelist",
                data: {"spreq_subdepid" : subpre},
                dataType:"html",
                success: function(data){
                $('#spreq_subpdepid').html(data.replace(/^"|"$/g, ''));
                }
            });
	}

	</script>
    </head>    
    <body>
<!--       <table id="uname"><tr><td align=center>Welcome <?//= $this->session->userdata('username') ?>  </td></tr></table>-->
        <!--<//?php
           echo "<table width=\"100%\" border=\"1\" style=\"color: black;  border-collapse:collapse; border:1px solid #BBBBBB;\">";
            echo "<tr style=\"text-align:left; font-weight:bold; background-color:#66C1E6;\">";
            echo "<td style=\"padding: 8px 8px 8px 20px;color:white;\">";
            echo "Map";
            echo "<span  style='padding: 8px 8px 8px 20px;'> ";
            echo "|";
            
            echo anchor('map/viewscprgseat/', "View Study Center Program Seat ", array('title' => 'Add Detail' , 'class' => 'top_parent'));
            echo "<span  style='padding: 8px 8px 8px 20px;'> ";
            echo "|";
            echo "<span  style='padding: 8px 8px 8px 20px;'>";
            echo "Map Study Center Program Seat";
            echo "</span>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
        ?>-->
    
        <table width="100%"> 
            
            <?php echo form_error('<div style="" class="isa_error">','</div>');?>
 
 <tr>  
                <div>    
                <?php
                echo "<td align=\"left\" width=\"33%\">";
                echo anchor('map/prerequisite/', "Subject With Prerequisite List ", array('title' => 'View Detail' , 'class' => 'top_parent'));
                echo "</td>";

                echo "<td align=\"center\" width=\"34%\">";
                echo "<b>Add Subject With Prerequisite Details</b>";
                echo "</td>";

                echo "<td align=\"right\" width=\"33%\">";
		$help_uri = site_url()."/help/helpdoc#MapSubjectandPaperwithPrerequisite";
		echo "<a target=\"_blank\" href=$help_uri><b style=\"float:right;\">Click for Help</b></a>";
                
		?>
                </div>
               </tr>
           </table>
           <table width="100%">
           <tr><td>
               <div>
                 
                <?php echo validation_errors('<div  class="isa_warning">','</div>');?>

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
        </td></tr>  
        </table>  
 
            
	<form action="<?php echo site_url('map/mapsubpre');?>" method="POST" class="form-inline">
	<table>
		<tr>
           	<td>Program Name :</td>
           	<td>
                <select name="programname" id="programname" class="my_dropdown" style="width:100%;" onchange="getsubname(this.value)" >
                <option value="" disabled selected >------Select Program Name--------------</option>
                <?php foreach($prgresult as $dataspt): ?>
                <option value="<?php echo $dataspt->prg_id?>"><?php echo $dataspt->prg_name.'('.$dataspt->prg_branch.')'; ?></option>
                <?php endforeach; ?>
           	</td>
		</tr>

        	<!--<tr>
           	<td>Branch Name :</td>
           	<td>
                <select name="spreq_prgid" id="spreq_prgid" class="my_dropdown" style="width:100%;" onchange="getsubject(this.value)">
                <option value="" disabled selected >------Select Branch Name--------------</option>
           	</td>
		</tr>-->

		<tr>
                <td>Subject Name :</td>
                <td>
                <select name="spreq_subid" id="spreq_subid" class="my_dropdown" style="width:100%;" onchange="getpapername(this.value)" >
                <option value="" disabled selected >------Select Subject Name--------------</option>
                <?php //foreach($subres as $dataspt): ?>
<!--                <option value="<?php //echo $dataspt->sub_id ?>"><?php //echo $dataspt->sub_name; ?></option> -->
                <?php //endforeach; ?>
                </td>
                </tr>

		<tr>
                <td>Prerequisite Subject Name :</td>
                <td>
                <select name="spreq_subdepid" id="spreq_subdepid" class="my_dropdown" style="width:100%;" onchange="getprepaper(this.value)">
                <option value="" disabled selected >------Select Subject Prerequisite Name--------------</option>
                <?php foreach($subres as $dataspt): ?>
                <option value="<?php echo $dataspt->sub_id ?>"><?php echo $dataspt->sub_name; ?></option>
                <?php endforeach; ?>
                </td>
                </tr>

		<tr>
                <td>Subject Paper Name :</td>
                <td>
                <select name="spreq_subpid" id="spreq_subpid" class="my_dropdown" style="width:100%;" >
                <option value="" disabled selected >------Select Subject Paper Name--------------</option>
                <?php //foreach($subpres as $dataspt): ?>
            <!--    <option value="<?php //echo $dataspt->subp_id ?>"><?php //echo $dataspt->subp_name; ?></option> -->
                <?php //endforeach; ?>
                </td>
                </tr>

                <tr>
                <td>Prerequisite Subject Paper Name :</td>
                <td>
                <select name="spreq_subpdepid" id="spreq_subpdepid" class="my_dropdown" style="width:100%;" >
                <option value="" disabled selected >------Select Subject Prerequisite Paper Name--------------</option>
               <!-- <?php //foreach($subpres as $dataspt): ?>
                <option value="<?php //echo $dataspt->subp_id ?>"><?php //echo $dataspt->subp_id; ?></option>
                <?php //endforeach; ?>-->
                </td>
                </tr>

            	<tr>
               	<td></td>
                <td>   
                <button name="mapsubpre" >Submit</button>
                <button name="reset" >Clear</button>
                </td>
            	</tr>
        </form>    
        </table>
    </body> 
    <div align="center">  <?php $this->load->view('template/footer');?></div>
</html>
