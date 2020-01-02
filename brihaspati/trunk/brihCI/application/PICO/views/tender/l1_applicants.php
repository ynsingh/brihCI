<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
<head>
<title>List|L1</title>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/1.12.4jquery.min.js" ></script>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css">
     <?php $this->load->view('template/header');
     
      ?>
     
 


</head>
<body>
 
<script>
               $(document).ready(function(){
                            $("#offpayrowl").hide();
    		              
    		      $('.approve').on('change',function(){
                        var pt= $('.approve').val();
                      //  alert(pt);
                        if(pt != null){
                            $("#offpayrowl").show();}
                        else{
                            $("#offpayrowl").hide();}
               });
               
                      
               });

</script>

<form action="<?php echo site_url('tender/l1');?>" method="POST" class="form-inline" enctype="multipart/form-data">


<h2 class="title">Comparative statement</h2>

  
          <table class="TFtable">
            <tr>
              
                <td><label for="bd_tt" class="control-label">Tender type:</label></td>
                <td>
                <select class="approve" name="id"  style="width:317px ;">
				          <option selected="" disabled="">----Select Tender ID----</option>
                            <?php
                                foreach ($dept as $row){
                            ?>
                                    <option value="<?php echo $row->tc_id ?>"><?php echo 'Tender ID='.$row->tc_id.' & Reference No.='.$row->tc_refno ?></option> 
                                <?php
                                }
                                ?>
				    </select>
                
                
                 <br></td>
           
         
                   
               
		    <td id="offpayrowl"  >
				
					<button name="push" style="float:left; ">--Submit--</button>
				
					</td>
					</tr>     
 			 
 			 </table>   
 			  
</form>           

<table class="TFtable" >
<th colspan="3">comparative statement</th>
<?php 
foreach($l1 as $l)
{
?>
<tr>
<td>
<b>Tender Reference No.</b> <?php  echo $l->ld_tenrefno ?>
</td>
<td>
<b>Vendor ID</b> <?php  echo $l->ld_vendorid ?>
</td>
<td>
<b><?php echo anchor('tender/comparative_view/' . $l->ld_id , "View", array('title' => 'VIEW Details' , 'class' => 'red-link')) . "<br><br>"; 
           
           if($l->ld_comparativeflag=='') 
           {echo anchor('tender/complete_proposal/' . $l->ld_taid , "Complete Proposal", array('title' => 'VIEW Details' , 'class' => 'red-link')) . "<br><br>";
           }

?>                
     
</td>
</tr><?php } ?>
</table>
<br>



</body>
<br>
<br>
<p>&nbsp;</p>
  <div align="center"> <?php $this->load->view('template/footer');?>
</html>
