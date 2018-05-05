<?php

class Entry_model extends CI_Model {

function __construct() {
        parent::__construct();
    }


	function Entry_model()
	{
		parent::Model();
	}

	function next_entry_number($entry_type_id)
	{

		$this->db->select_max('id')->from('entries')->where('entry_type', $entry_type_id);
		$max_id_q = $this->db->get();
	//	print_r($max_id_q);
		$row = $max_id_q->row();
		$max_id = $row->id;
	//	print_r($max_id);

		if($max_id != NULL){
			$this->db->select('number')->from('entries')->where('id', $max_id);
			$number_q = $this->db->get();
			$row1 = $number_q->row();
			$number = $row1->number;
			//echo "$number";
			if(ctype_digit($number)){
				$total_result = $this->db->count_all_results();
				$number = max($number,$total_result);
				$number++;
			}
			else{
				//$this->db->select('id')->from('entries')->where('entry_type', $entry_type_id);
				$this->db->where('entry_type', $entry_type_id);
				$this->db->from('entries');
				$max_number = $this->db->count_all_results();
				$max_number++;
				$number = $max_number;
			}
			
		}else{
			$number = 1;
		}

/*		$this->db->select_max('number', 'lastno')->from('entries')->where('entry_type', $entry_type_id);
		$last_no_q = $this->db->get();
		if ($row = $last_no_q->row())
		{
			$last_no = (int)$row->lastno;
			$this->logger->write_message("success", "voucherno from db=".$last_no);
			if ($last_no<=0){
				$this->db->where('entry_type', $entry_type_id);
				$this->db->from('entries');
				$last_no=$this->db->count_all_results();
			}
			$last_no++;
			return $last_no;
		} else {
			return 1;
		}*/
		return $number;
	}

	function check_duplicacy($v_number , $entry_type)
	{
		$this->db->from('entries')->where('number',$v_number)->where('entry_type', $entry_type);
		$number_q = $this->db->get();
		$rows = $number_q->num_rows();
		if ($rows > 0) 
			return true;
		
		else
			return  false;
	}

	/**
	* Code to check duplicacy of Vendor Voucher Number
	* false entry allowed, true entry stoped
	*/
	//added by @RAHUL
	function check_vendor_no($vendor_no,$purchase_order_no)
        {
		$vendor_no1 = $vendor_no;
		$purchase_order_no1 = $purchase_order_no;
		if($vendor_no1 == '' && $purchase_order_no == '')
		{
			return false;
		}
		elseif($purchase_order_no == '' && $vendor_no1 != '')
		{
			$this->db->from('entries')->where('vendor_voucher_number',$vendor_no1);
                        $number_q1 = $this->db->get();
                        $rows1 = $number_q1->num_rows();
                        if ($rows1 > 0)
                                return true;
                        else
                                return  false;

		}
		elseif($vendor_no1 == '' && $purchase_order_no1 != '')
		{
                                return  false;
		}
		else
		{
                	$this->db->from('entries')->where('vendor_voucher_number',$vendor_no1)->where('purchase_order_no',$purchase_order_no1);
                	$number_q = $this->db->get();
                	$rows = $number_q->num_rows();
                	if ($rows > 0)
                        	return true;
                	else
                        	return  false;
		}
        }

	function cheque_duplicacy($par_tyid,$data_cheque_no,$entry_id,$data_bank_name)
	{
		$this->db->from('cheque_print')->where('secunitid', $par_tyid)->where('update_cheque_no', $data_cheque_no)->where('entry_no', $entry_id)->where('bank_name', $data_bank_name);
		$duplicacy = $this->db->get();
		$rows = $duplicacy->num_rows();
                if ($rows > 0)
                	return true;
                else
                	return  false;
	}


	function get_entry($entry_id, $entry_type_id)
	{
		$this->db->from('entries')->where('id', $entry_id)->where('entry_type', $entry_type_id)->limit(1);
		$entry_q = $this->db->get();
		return $entry_q->row();
	}

	function get_entryid($number, $entry_type_id)
        {
                $this->db->select('id')->from('entries')->where('number', $number)->where('entry_type', $entry_type_id)->limit(1);
                $entry_q = $this->db->get();
		$result = $entry_q->row();
		$entryid = $result->id; 
		return  $entryid;
        }

	function  get_entryitemid($entry_id, $dc,$data_ledger_cid)	
	{
		$this->db->select('id')->from('entry_items')->where('entry_id',$entry_id)->where('dc', $dc)->where('ledger_id', $data_ledger_cid)->limit(1);
		$entry_q = $this->db->get();
                $result = $entry_q->row();
                $entryid = $result->id;
                return  $entryid;
	}

	function get_all_entry_types()
        {
                $options = array();
            //    if ($allow_none)
              //          $options[0] = "(None)";
                $this->db->from('entry_types')->order_by('id', 'asc');
                $tag_q = $this->db->get();
                foreach ($tag_q->result() as $row)
                {
                        $options[$row->id] = $row->name;
                }
                return $options;
        }

	function get_name_of_entry_type($entry_type_id)
	{
		$this->db->select('name')->from('entry_types')->where('id',$entry_type_id)->limit(1);
		$tye_entry = $this->db->get();
		$type_entry = $tye_entry->row();
		$entry_type_name = $type_entry->name;
		return $entry_type_name;
	}
	function get_id_of_entry_type($entry_type_name)
        {
                $this->db->select('id')->from('entry_types')->where('name',$entry_type_name)->limit(1);
                $tye_entry = $this->db->get();
                $type_entry = $tye_entry->row();
                $entry_type_id = $type_entry->id;
                return $entry_type_id;
        }

	function get_all_entry_items_ledger_notfund($entry_id,$income_id)
	{
		$this->db->select('verified_by')->from('entries')->where('id',$entry_id);
		$verified_id = $this->db->get();
		$verification = $verified_id->row();
		$verification_id = $verification->verified_by;
		$this->db->select('id')->from('entry_items')->where('entry_id',$entry_id)->where('ledger_id !=',$income_id);
          	$map_ledger_id = $this->db->get();
         	foreach($map_ledger_id->result() as $row_a1)
         	{
             		$maping_id = $row_a1->id;
                	$this->db->select('entry_items_id')->from('fund_management')->where('entry_items_id',$maping_id);
                     	$map_ledger_id_1 = $this->db->get();
			if($verification_id != '')
			{
				if($map_ledger_id_1->num_rows >0)
                        	{
                                	foreach($map_ledger_id_1->result() as $row_a2)
                                	{
                                        	$maping_id_1[] = $row_a2->entry_items_id;
                                	}
                        	}
                        	else
                        	{
                                	$maping_id_1[] = '0';
                        	}
			}
			else
			{
               			if($map_ledger_id_1->num_rows >0)
                    		{
                       			foreach($map_ledger_id_1->result() as $row_a2)
                          		{
                             			$maping_id_1[] = $row_a2->entry_items_id + 1;
                       			}
                  		}
                   		else
                    		{
                      			$maping_id_1[] = '0';
               			}
			}
		}
        	$this->db->from('entry_items')->where('entry_id',$entry_id)->where('ledger_id !=',$income_id)->where_not_in('id',$maping_id_1);
           	$cur_ledgers_q = $this->db->get();
		return $cur_ledgers_q;
	}

}
