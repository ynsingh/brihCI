
<!--@filename cca_allowancelist.php  @author Manorama Pal(palseema30@gmail.com) -->

<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
    <head>
        <title>CCA Allowance List</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css">   
    </head>
    <body>
            <?php $this->load->view('template/header'); ?>
           
        <table width="100%"><tr colspan="2">
            <?php 
            echo "<td align=\"left\" width=\"33%\">";
            echo anchor('setup3/add_ccaallowance/', "Add City Compensatory Allowance(CCA)" ,array('title' => ' Add City Compensatory Allowance(CCA)' , 'class' => 'top_parent'));
            echo "</td>";
            echo "<td align=\"center\" width=\"34%\">";
            echo "<b>City Compensatory Allowance(CCA) List</b>";
            echo "</td>";
            echo "<td align=\"right\" width=\"33%\">";
            $help_uri = site_url()."/help/helpdoc#SalaryHeadList";
//            echo "<a style=\"text-decoration:none\" target=\"_blank\" href=$help_uri><b>Click for Help</b></a>";
            echo "</td>";
            ?>
            <div>     
                <?php echo validation_errors('<div class="isa_warning">','</div>');?>
                <?php echo form_error('<div class="isa_error">','</div>');?>
                <?php
                if((isset($_SESSION['success'])) && ($_SESSION['success'])!=''){
                    echo "<div  class=\"isa_success\">";
                    echo $_SESSION['success'];
                    echo "</div>";
                }
                if((isset($_SESSION['err_message'])) && (($_SESSION['err_message'])!='')){
                    echo "<div  class=\"isa_error\">";
                    echo $_SESSION['err_message'];
                    echo "</div>";
                }
                ;?>
            </div>
        </tr></table>
        <div class="scroller_sub_page">
        <table class="TFtable" >
            
            <thead>
                <tr>
                    <th>Sr.No</th>
<!--                    <th>Working Type</th> -->
			<th>Pay Commission</th>
<!--                    <th>Pay Scale</th> -->
			<th>Pay Range</th>
                    <th>Grade</th>
                    <th>Amount</th>
    <!--                <th>Description</th>-->
                    <th>Action</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php $serial_no = 1;?>
              <?php if( count($ccadata) ):  ?>
                    <?php foreach($ccadata as $record){ ?>
                        <tr>
                            <td><?php echo $serial_no++; ?></td>
<!--                            <td><?php //echo $record->cca_workingtype;?></td>-->
				<td><?php echo $record->cca_paycomm;?></td>
                                <?php // $pname=$this->sismodel->get_listspfic1('salary_grade_master','sgm_name','sgm_id',$record->cca_payscaleid)->sgm_name;
//                                    $min=$this->sismodel->get_listspfic1('salary_grade_master','sgm_min','sgm_id',$record->cca_payscaleid)->sgm_min;
  //                                  $max=$this->sismodel->get_listspfic1('salary_grade_master','sgm_max','sgm_id',$record->cca_payscaleid)->sgm_max;
    //                                $gp=$this->sismodel->get_listspfic1('salary_grade_master','sgm_gradepay','sgm_id',$record->cca_payscaleid)->sgm_gradepay;
      //                              $fullstr=$pname."( ".$min." - ".$max." )".$gp;
                
        //                            $hragradename=$this->sismodel->get_listspfic1('hra_grade_city','hgc_gradename','hgc_id',$record->cca_gradeid)->hgc_gradename;
                                ?>
          <!--                  <td><?php //echo $fullstr;?></td>     -->
				<td><?php echo $record->cca_payrange; ?></td>
                            <td><?php echo $this->sismodel->get_listspfic1('cca_grade_city','cgc_gradename','cgc_id',$record->cca_gradeid)->cgc_gradename; ?></td>
                            <td><?php echo $record->cca_amount; ?></td>
<!--                            <td><?php //echo $record->cca_description; ?></td>-->
                            <td> <a href='<?php echo site_url()."/setup3/edit_ccaallowance/".$record->cca_id;?>' title="Edit Details"><img src="<?php echo base_url('assets/sis/images/edit.png');?>"></a>&nbsp; 
                                <a href='<?php echo site_url()."/setup3/delete_ccaallowance/".$record->cca_id;?>' title="Delete" onclick="return confirm('Are you sure you want to delete this record?');"><img src="<?php echo base_url('assets/sis/images/delete3.jpg');?>"></a>  
                            </td>    
                            
                        </tr>
                        
                    <?php }; ?>
                    
                <?php else : ?>
                    <td colspan= "6" align="center"> No Records found...!</td>
                <?php endif;?>
               
		</tbody>
               
        </table>
        </div>
        <p> &nbsp; </p>
        <div align="center">  <?php $this->load->view('template/footer');?></div>
        
    </body>    
</html>   

