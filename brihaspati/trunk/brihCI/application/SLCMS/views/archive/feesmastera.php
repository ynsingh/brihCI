<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!--@name displayfees.php 
  @author Vijay (vijay.pal428@gmail.com)
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
                    $help_uri = site_url()."/help/helpdoc#FeesMasterArchive";
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
		<th>Fees master Id</th>
		<th>Program Name</th>
		<th>Academic Year</th>
		<th>Semester</th>
		<th>Category</th>
		<th>Gender</th>
		<th>Head</th>
		<th>Amount</th>
		<th>From Date</th>
		<th>To Date</th>
		<th>Description</th>
		<th>Archiver Name</th>
		<th>Archive date</th>
                </tr>
            </thead>
            <tbody>
                    <?php $count =0?>
              	    <?php if( count($this->fmaresult) ): ?>
                    <?php foreach($this->fmaresult as $row){ ?>
                         <tr align="center">
			 <td> <?php echo ++$count; ?> </td>
			 <td> <?php echo $row->fma_fmid ?></td>
			 <?php  echo "<td>" . $this->common_model->get_listspfic1('program','prg_name','prg_id',$row->fma_programid)->prg_name. "</td>";?>
			 <td> <?php echo $row->fma_acadyear ?></td>
            		 <td> <?php echo $row->fma_semester ?></td>
                         <?php  echo "<td>" . $this->common_model->get_listspfic1('category','cat_name','cat_id',$row->fma_category)->cat_name . "</td>";?>
            		 <td> <?php echo $row->fma_gender ?></td>
           		 <td> <?php echo $row->fma_head ?></td>
            		 <td> <?php echo $row->fma_amount ?></td>
            		 <td> <?php echo $row->fma_frmdate ?></td>
            		 <td> <?php echo $row->fma_todate ?></td>
         		 <td> <?php echo $row->fma_desc ?></td>
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
