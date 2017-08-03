<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!--@name display program subject paper.php 
    @author Nagendra Kumar Singh (nksinghiitk@gmail.com)
 -->
<html>
    <head>    
        <?php $this->load->view('template/header'); ?>
        <h1>Welcome <?= $this->session->userdata('username') ?>  </h1>
        <?php $this->load->view('template/menu');?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css">
    </head>
    <body>

		   <?php
                    echo "<table style=\"padding: 20px 8px 8px 20px;\">";
                    echo "<tr valign=\"top\">";
                    echo "<td>";
                    $help_uri = site_url()."/help/helpdoc#ProgramSubjectPapaerArchive";
                    echo "<a target=\"_blank\" href=$help_uri><b style=\"float:right;margin-left:39%;position:absolute;margin-top:-1%\">Click for Help</b></a>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                    ?>

        <table style="margin-left:10px;">
            <tr colspan="2"><td>
            <div  style="margin-left:-06px;width:1793px;">

                <?php echo validation_errors('<div class="isa_warning>','</div>');?>

                <?php if(isset($_SESSION['success'])){?>
                    <div style="margin-left:30px;" class="isa_success"><?php echo $_SESSION['success'];?></div>

                <?php
                };
                ?>
                <?php if(isset($_SESSION['err_message'])){?>
                    <div class="isa_error"><?php echo $_SESSION['err_message'];?></div>

                <?php
                };
                ?>

            </div>
            </td></tr>
        </table>
        <table cellpadding="16" style="margin-left:30px;" class="TFtable" >
            <thead>
                <tr align="center">
		<th>Sr.No</th>
		<th>Subject Paper Id</th>
		<th>Program Category</th>
		<th>Program Name</th>
		<th>Program Branch</th>
		<th>Academic Year</th>
		<th>Subject Name</th>
		<th>Paper Number</th>
		<th>Paper Name</th>
		<th>Paper Code</th>
		<th>Paper Short Name</th>
		<th>Paper Description</th>
		<th>Archiver Name</th>
		<th>Archive date</th>
                </tr>
            </thead>
            <tbody>
                    <?php $count =0?>
              	    <?php if( count($this->prgsubaresult) ): ?>
                    <?php foreach($this->prgsubaresult as $row){ ?>
                         <tr align="center">
			 <td> <?php echo ++$count; ?> </td>
			 <td> <?php echo $row->subpa_subpid ?></td>
			 <td> <?php echo $row->subpa_subtype ?></td>
                         <?php  echo "<td>" . $this->common_model->get_listspfic1('program','prg_name','prg_id',$row->subpa_degree)->prg_name . "</td>";?>
            		 <td> <?php echo $row->subpa_branch ?></td>
           		 <td> <?php echo $row->subpa_acadyear ?></td>
			 <?php  echo "<td>" . $this->common_model->get_listspfic1('subject','sub_name','sub_id',$row->subpa_sub_id)->sub_name. "</td>";?>
            		 <td> <?php echo $row->subpa_paperno ?></td>
            		 <td> <?php echo $row->subpa_name ?></td>
         		 <td> <?php echo $row->subpa_code ?></td>
         		 <td> <?php echo $row->subpa_short ?></td>
         		 <td> <?php echo $row->subpa_desp ?></td>
            		 <td> <?php echo $row->creatorid ?></td>
            		 <td> <?php echo $row->createdate ?></td>

                        </tr>
                    <?php }; ?>
                <?php else : ?>
                    <td colspan= "10" align="center"> No Records found...!</td>
                <?php endif;?>

            </tbody>
        </table>
    </body>
    <div align="center">  <?php $this->load->view('template/footer');?></div>
</html>

