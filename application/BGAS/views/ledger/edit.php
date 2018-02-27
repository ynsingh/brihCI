<script type="text/javascript">
$(document).ready(function() {

        //code for displaying parent child hierarchy
        $('.ledger-parent').change(function() {
                        if($(this).val() == "Please Select"){
                                $('.parent').hide();
                        
                        }else{
                                $('.parent').show();
                                //name = $(this).val();
				ledger = $(this).val();
                                var ledgerArray = ledger.split('#');
                                name = ledgerArray[0];
                                id = ledgerArray[1];

                                $.ajax({
                                        url: <?php echo '\'' . site_url('ledger/set_group_id') . '/\''; ?> + id,
                                        success: function() {
                                                location.reload();
                                        }
                                 });
                        }
        });     


});
</script>

<?php
	echo form_open('ledger/edit/' . $ledger_id);

	echo "<p>";
	echo form_label('Ledger name', 'ledger_name');
	echo "<br />";
	echo form_input($ledger_name);
	echo "</p>";

	echo "<p>";
	echo form_label('Ledger code', 'ledger_code');
	echo "<br />";
	echo form_input($ledger_code);
	echo "</p>";

	echo "<p>";
	echo form_label('Parent group', 'ledger_group_id');
	echo "<br />";
	//echo form_dropdown('ledger_group_id', $ledger_group_id, $ledger_group_active,"class=\"ledger-parent\"");
	echo form_dropdown('ledger_group_id', $ledger_group_id, $ledger_group_active, "disabled");
	echo "</p>";

	/**
         * Code for diplaying parent child hierarchy
         * for the selected ledger head.
         * @author Priyanka Rawat <rpriyanka12@ymail.com>
         */
	$attributes = array('class' => "parent", 'style' => "width:600px");
        echo form_fieldset('Parent Child Hierarchy', $attributes);
        echo "<p>";

        $this->load->library('session');
        $groupid = $this->session->userdata('ledger_group_id');
        $group_array = array();
        $counter = 0;

        if($groupid != 0){
                $id = $groupid;
        }else{
                //$id = $ledger_id;
		$this->db->select('group_id, name');
                $this->db->from('ledgers')->where('id =', $ledger_id);
                $ledgers_result = $this->db->get();
                $ledgers = $ledgers_result->row();
		$group_array[$counter] = $ledgers->name;
                $counter++;
		$id = $ledgers->group_id;
	}

        if($id != 0){
                //$id = $groupid;
                do{
                        $this->db->select('parent_id, name');
                        $this->db->from('groups')->where('id =', $id);
                        $query_result = $this->db->get();
                        $data = $query_result->row();
                        //echo $data->name;
                        $group_array[$counter] = $data->name;
                        $counter++;

                        //if($data->parent_id >  4){
                        if($data->parent_id){
                        //      echo " -> ";
                                $id = $data->parent_id;
                        }else{
                                $id = 0;
                                $counter--;
                        }

                }while($id != 0);


                do{
                        echo $group_array[$counter];
                        $counter--;

                        if($counter >= 0)
                                echo " -> ";
                }while($counter >= 0);

                $this->session->unset_userdata('ledger_group_id');
        }
        echo "</p>";
        echo form_fieldset_close();

    echo "<p>";
    echo form_label('Ledger Description', 'ledger_description');
    echo "<br />";
    echo form_textarea($ledger_description);
    echo "</p>";
//		$string = strval($ledger_code);
//	if (substr($string, 0, 2) == '10'){
	echo "<p>";
	echo form_label('Opening balance', 'op_balance');
	echo "<br />";
	echo "<span id=\"tooltip-target-1\">";
	echo form_dropdown_dc('op_balance_dc', $op_balance_dc);
	echo " ";
	echo form_input($op_balance);
	echo "</span>";
	echo "<span id=\"tooltip-content-1\">&nbsp;&nbsp;Assets / Expenses => Dr. Balance<br />Liabilities / Incomes => Cr. Balance</span>";
	echo "</p>";

	echo "<p>";
	echo "<span id=\"tooltip-target-2\">";
	echo form_checkbox('ledger_type_cashbank', 1, $ledger_type_cashbank) . " Bank or Cash Account";
	echo "</span>";
	echo "<span id=\"tooltip-content-2\">Select if Ledger account is a Bank account or a Cash account.</span>";
	echo "</p>";

	echo "<p>";
	echo "<span id=\"tooltip-target-3\">";
	echo form_checkbox('reconciliation', 1, $reconciliation) . " Reconciliation";
	echo "</span>";
	echo "<span id=\"tooltip-content-3\">If enabled account can be reconciled from Reports > Reconciliation</span>";
	echo "</p>";
//	}
	echo "<p>";
	echo form_hidden('ledger_id', $ledger_id);
	echo form_submit('submit', 'Update');
	echo " ";
	echo anchor('account', 'Back', 'Back to Chart of Accounts');
	echo "</p>";

	echo form_close();
?>
