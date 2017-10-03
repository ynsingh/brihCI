<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!--@name addbank.php 
  @author Neha Khullar(@gmail.com)
 -->


<html>
<title>Add Bank</title>
 <head>    
        <?php $this->load->view('template/header'); ?>
        <h1>Welcome <?= $this->session->userdata('username') ?>  </h1>
        <?php $this->load->view('template/menu');?>
 </head>
   <body>
     <table width="100%">
       <tr><td>
        <div align="left">
        <?php
           echo anchor('setup/displaybankdetails', 'Bank Details', array('class' => 'top_parent'));
           $help_uri = site_url()."/help/helpdoc#Bank";
           echo "<a target=\"_blank\" href=$help_uri><b style=\"float:right;position:absolute;margin-left:73%\">Click for Help</b></a>";
        ?>
        </font>
        </div>
        <div align="left" style="margin-left:2%;width:90%;">
        <?php echo validation_errors('<div class="isa_warning">','</div>');?>
        <?php echo form_error('<div class="isa_error">','</div>');?>
        <?php if(isset($_SESSION['success'])){?>
        <div class="alert alert-success"><?php echo $_SESSION['success'];?></div>
        <?php
         };
        ?>
        <?php if(isset($_SESSION['err_message'])){?>
             <div class="isa_error"><?php echo $_SESSION['err_message'];?></div>
        <?php
        };
        ?>
      </div>
    </td>
    </tr>
    <tr>
    <div align="left" style="margin-left:1%">
        <form action="<?php echo site_url('setup/addbank');?>" method="POST" class="form-inline">
          <table style="margin-left:1%">
            <tr>

            <?php
                        echo "<td>";
                        echo form_label('Organization', 'org_id');
                        echo "</td>";
                        echo "<td>";
                        echo "<select name=\"org_id\" class=\"my_dropdown\" style=\"width:100%;\">";
                        echo "<option selected='true'disabled>------Organization------</option>";
                        echo "<option value='Indira Gandhi National Tribal University'>Indira Gandhi National Tribal University </option>";
                        echo "</select>";
                        ?>


            <tr>       
                <td><label for="name" class="control-label">Bank Name :</label></td>
                <td>
                <input type="text" name="bank_name" class="form-control" size="40" /><br>
                </td>
                <td>
                 <!--  <?php echo form_error('name')?></td>-->
                </td>
               <td>
                   Example : State Bank of India
                </td>

 
            </tr>
            <tr>
                <td><label for="branch" class="control-label">Branch Name :</label></td>
                <td>
                <input type="text" name="branch_name" class="form-control" size="40" /><br>
                </td>
                <td>
                  <!-- <?php echo form_error('branch')?></td>-->
                </td>
               <td>
                    Example : IIT Kanpur 
                 </td>


            </tr>
            <tr>
                <td><label for="address" class="control-label">Bank Address :</label></td>
                <td>
                <input type="text" name="bank_address" class="form-control" size="40" /><br>
                </td>
                <td>
                  <!-- <?php echo form_error('address')?></td>-->
                </td> 
               <td>
                    Example : Near Shopping Center, ITT Kanpur Campus, U. P., Pin 208016
                 </td>



            </tr>
            <tr>
                <td><label for="ifsc code" class="control-label">IFSC Code :</label></td>
                <td>
                <input type="text" MaxLength="11" name="ifsc_code" class="form-control"  size="40" /><br>
                </td>
                <td>
                  <!-- <?php echo form_error('ifsc code')?></td>-->
                </td>
               <td>
                    Example : SBIN0001161
                 </td>


               
            </tr>
            <tr>
                <td><label for="account number" class="control-label">Account Number :</label></td>
                <td>
                <input type="text" MaxLength="11" name="account_number" class="form-control" size="40" /><br>
                </td>
                <td>
                  <!-- <?php echo form_error('account number')?></td>-->
                </td>
               <td>
                    Example : 30281294639
                 </td>



            </tr>
            <tr>
                <td><label for="account type" class="control-label">Account Type :</label></td>
                <td>
                <input type="text" name="account_type" class="form-control"  size="40" /><br>
                </td>
                <td>
                  <!-- <?php echo form_error('account type')?></td>-->
                </td>
               <td>
                    Example : Savings
                 </td>

               

            </tr>
            <tr>
                <td><label for="account name" class="control-label">Account Name :</label></td>
                <td>
                <input type="text" name="account_name" class="form-control"  size="40" /><br>
                </td>
                <td>
                   <!--<?php echo form_error('account name')?></td>-->
                </td>
               <td>
                    Example : Aakriti Shukla
                 </td>



            </tr>
            <tr>
                <td><label for="pan number" class="control-label">PAN Number :</label></td>
                <td>
                <input type="text" MaxLength="10" name="pan_number" class="form-control"  size="40" /><br>
                </td>
                <td>
                   <!--<?php echo form_error('pan number')?></td>-->
                </td>
               <td>
                    Example : AAACS8577K 
                 </td>

                                           

            </tr>
            <tr>
                <td><label for="tan number" class="control-label">TAN Number :</label></td>
                <td>
                <input type="text" name="tan_number" class="form-control"  size="40" /><br>
                </td>
                <td>
                   <!--<?php echo form_error('tan number')?></td>-->
                </td>
               <td>
                    Example : DELA02603G
                 </td>



            </tr>
            <tr>
                <td><label for="gst number" class="control-label">GST Number :</label></td>
                <td>
                <input type="text" name="gst_number" class="form-control"  size="40" /><br>
                </td>
                <td>
                  <!-- <?php echo form_error('gst number')?></td>-->
                </td>
               <td>
                    Example : 22(State Code) AAAAA0000A(PAN) 1Z5(1-Entity no) (Z-Alphabet 'Z' by default) (5-Check sum digit)
                 </td>




            </tr>
            <tr>
                <td><label for="aadhar number" class="control-label">Aadhar Number :</label></td>
                <td>
                <input type="text" MaxLength="12" name="aadhar_number" class="form-control"  size="40" /><br>
                </td>
                <td>
                  <!-- <?php echo form_error('aadhar number')?></td>-->
                </td>
                <td>
                    Example : 499118665246
                 </td>



            </tr>
            <tr>
                <td><label for="remark" class="control-label">Remark :</label></td>
                <td>
                <input type="text" name="remark" class="form-control"  size="40" /><br>
                </td>
                <td>
                  <!-- <?php echo form_error('remark')?></td>-->                  
                </td>
               <td>
                    Example : Fair, Good, Need to be improved
                 </td>
            </tr>
            <tr>
              <td></td>
              <td>
              <button name="addbank">Add Bank</button>
              <button name="reset" >Clear</button>

                </td>
            </tr>
            </table>
    </form>
    </div>
    <div align=left> <?php $this->load->view('template/footer');?></div>
    </html>

              
            
          