<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!--@name editcategory.php 
  @author Om Prakash(omprakashkgp@gmail.com)
 -->

<html>
<title>Edit Category</title>
    <head>    
        <?php $this->load->view('template/header'); ?>
        
    </head>
    <body>

<script>
        function goBack() {
        window.history.back();
        }
    </script>

      <table>
            <tr colspan="2"><td>
	     	<?php
        	   echo anchor('setup/displaycategory', 'Category List', array('class' => 'top_parent'));
	        ?>
		 <div align="left" style="margin-left:0%;width:90%;">
                    <?php echo validation_errors('<div class="isa_warning">','</div>');?>
                    <?php echo form_error('<div class="isa_error">','</div>');?>

                    <?php if(isset($_SESSION['success'])){?>
                        <div class="isa_success"><?php echo $_SESSION['success'];?></div>

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
        <table>  
        <?php
            echo form_open('setup/editcategory/' . $cat_id);
       
            echo "<tr>";
                echo "<td>";
                    echo form_label('Category Name', 'cname');
                echo "</td>";
                echo "<td>";
                    echo form_input($cname);
                echo "</td>";
                echo "<td>";
                    echo "Example: Scheduled Tribe";
                echo "</td>";
            echo "</tr>";

            //echo "<p>";
            echo "<tr>";
                echo "<td>";
                   echo form_label('Category Code', 'ccode');
                    //echo "<br />";
                echo "</td>";
                echo "<td>";
                    echo form_input($ccode);
                echo "</td>";
                echo "<td>";
                    echo "Example: Scheduled Tribe";
                echo "</td>";
            echo "</tr>";
        
            echo "<tr>";
                echo "<td>";
                    echo form_label('Category Short Name', 'csname');
                //echo "<br />";
                echo "</td>";
                echo "<td>";
                    echo form_input($csname);
                echo "</td>";
                echo "<td>";
                    echo " Example : ST, SC, OBC, GEN, PH etc. ";
                echo "</td>";
            echo "</tr>";
            
            echo "<tr>";
                echo "<td>";
                    echo form_label('Category Description', 'cdesc');
                echo "</td>";
                echo "<td>";
                    //echo "<br />";
                    echo form_input($cdesc);
                echo "</td>";
                echo "<td>";
                    echo " Example : Reserved Category, Unreserved Category.";
                echo "</td>";
            echo "</tr>";
            //echo "</p>";
        
            // echo "<p>";
        
            echo "<tr>";
		echo "<td></td>";
                echo "<td>";
                    echo form_hidden('cat_id', $cat_id);
                    echo form_submit('submit', 'Update');
              //   echo anchor('setup/displaycategory', 'Back', array('class' => 'top_parent'));
               // echo "</p>";
            echo form_close();
            echo "<button onclick=\"goBack()\" >Back</button>";
            echo "</td>";
            echo "</tr>";

       ?>
 
        </table>   
    </body>
<p>&nbsp;</p>
    <div align="center">  <?php $this->load->view('template/footer');?></div>
</html>

