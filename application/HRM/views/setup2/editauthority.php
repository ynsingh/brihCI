<!---@name editauthority.php                                                                                                                                                               
    @author Nagendra Kumar Singh (nksinghiitk@gmail.com)
 -->
<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
<title>Edit Authority</title>
    <head>    
        <?php $this->load->view('template/header'); ?>
            <h1>Welcome <?= $this->session->userdata('username') ?>  </h1>
        <?php $this->load->view('template/menu');?>
    </head>
    <body>
 <script>
        function goBack() {
        window.history.back();
        }
    </script>


        <!--<//?php
            echo "<table width=\"100%\" border=\"1\" style=\"color: black;  border-collapse:collapse; border:1px solid #BBBBBB;\">";
            echo "<tr style=\"text-align:left; font-weight:bold; background-color:#66C1E6;\">";
            echo "<td style=\"padding: 8px 8px 8px 20px;color:white;\">";
            echo "Setup";
            echo "<span  style='padding: 8px 8px 8px 20px;'> ";
            echo "|";
            //echo "<span  style='padding: 8px 8px 8px 20px;'> ";
            echo anchor('setup/displayrole/', "View Role Details", array('title' => 'Add Detail' , 'class' => 'top_parent'));
            echo "<span  style='padding: 8px 8px 8px 20px;'> ";
            echo "|";
            echo "<span  style='padding: 8px 8px 8px 20px;'>";
            echo "Edit role";
            echo "</span>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
        ?>--!>
        <table width="100%">
                <tr><td>
                        <div margin="2%;width:90%;">
                        <?php echo validation_errors('<div  class="isa_warning">','</div>');?>
                        <?php echo form_error('<div class="isa_error">','</div>');?>
                        <?php if(isset($_SESSION['success'])){?>
                                <div class="isa_success" width="90%"><?php echo $_SESSION['success'];?></div>
                        <?php }; ?>
                        </div> </br>
                </td></tr>
        </table>
        <table style="padding: 8px 8px 8px 30px;">
        <?php
                echo form_open('setup2/editauthority/'. $id);
                echo "<tr>";
                echo "<td>";
                echo form_label('Authority Code : ', 'code');
                echo "</td>";
                echo "<td>";
                echo form_input($code);
                echo "</td>";
		echo "</tr>";

                echo "<tr>";
                echo "<td>";
                echo form_label('Authority Name : ', 'name');
                echo "</td>";
                echo "<td>";
                echo form_input($name);
                echo "</td>";
                echo "</tr>";
              
                echo "<tr>";
                echo "<td>";
                echo form_label('Authority Nickname : ', 'nickname');
                echo "</td>";
                echo "</td>";
                echo "<td>";
                echo form_input($nickname);
                echo "</td>";
                echo "</tr>";
                  
                echo "<tr>";
                echo "<td>";
                echo form_label('Authority Email : ', ' authority_email');
                echo "</td>";
                echo "</td>";
                echo "<td>";
                echo form_input($authority_email);
                echo "</td>";
                echo "</tr>";
           
                echo "<tr>";
                echo "<td>";
                echo "</td>";
                echo "<td>";
                echo form_hidden('id', $id);
                echo form_submit('submit', 'Update');
                echo " ";
                echo form_close();
                echo "<button onclick=\"goBack()\" >Back</button>";
                echo "</td>";
                echo "</tr>";
 ?>
       </table>
</body>
    <div align="center">  <?php $this->load->view('template/footer');?></div>
</html>                                                                                                                      
               





              
