<?php
defined ("BASEPATH") or exit("No direct script access");
class Loan_model extends CI_Model{
    public function getLoanTypes(){
        return $this->db->get("tbl_loantype")->result_array();
    }
    public function getLoanById($id){
        $this->db->where("id",$this->db->escape_str($id));
        return $this->db->get("tbl_loantype")->row_array();
    }
    public function updateBorrowedLoan($id,$data){
        $this->db->where("id",$id);
        $this->db->set($data);
        return $this->db->update("tbl_loan");
    }
    private function updateBalance($id,$amount){
        $loan = $this->getLoanWhere(array("id" => $id));
        $where = array(
            "id" => $id
        );
        
        $balance = $loan["balance"];
        $data["balance"] = $balance - $amount;
        if ($data["balance"] == 0){
            $data["state"] = "cleared";
        }
        //echo "Balance: $balance To pay: $amount";
        $this->db->where($where);
        $this->db->set($data);
        $result = $this->db->update("tbl_loan");
        //echo $result;
        return $result;
    }
    private function saveUpdateLoanPayment($id,$amount){
        $data = array(
            "amount" => $amount,
            "loan_id" => $id
        );
        if ($this->db->insert("tbl_loanpayment",$data)){
            if ($this->updateBalance($id,$amount)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    public function payLoan($id,$identity_no,$amount){
        $loan = $this->getLoanWhere(array("id" => $id));
        $this->load->model("user_model");
        $user = $this->user_model->findOneByNationalId($identity_no);
        if (count($loan) > 0 && count($user) > 0){
            if ($amount <= $loan["balance"]){
                if ($user["id"] != $loan["user"]){
                    //if user_id of payer does not match id of user registered on the loan to avoid mistake
                    return false;
                }else{
                    //save to db and update balance
                    return $this->saveUpdateLoanPayment($id,$amount);
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    public function updateLoanById($id,$data)
    {
        $this->db->where("id",$id);
        $this->db->set($data);
        return $this->db->update("tbl_loantype",$data);
    }
    public function get_loanSummary(){
        $result = $this->db->get("tbl_loantype")->result_array();
        $data["count"] = count($result);
        return $data;
    }
    public function newLoanType($data){
        return $this->db->insert("tbl_loantype",$data);
    }
    public function getAllLoans(){
        $sql = "SELECT `tbl_loan`.`id`,`tbl_loan`.`state`,`tbl_users`.`id_number`, `tbl_users`.`first_name`,`tbl_users`.`last_name`,`tbl_loan`.`date_borrowed`, `tbl_loan`.`amount_borrowed`, `tbl_loan`.`interest`, `tbl_loan`.`amount_to_pay`, `tbl_loan`.`loan_type`, `tbl_loan`.`balance`, `tbl_loan`.`status` FROM tbl_loan JOIN tbl_users ON `tbl_loan`.`user` = `tbl_users`.`id`  WHERE 1";
        return $this->db->query($sql)->result_array();
    }
    public function getLoanWhere($where){
        return $this->db->get_where("tbl_loan",$where)->row_array();
    }
    public function payLoan_Step_one($id,$ref){
        $loan = $this->getLoanWhere(array(
            "id" => $ref,
            "status" => "approved",
            "state" => "unpaid"
        ));
        if (count($loan) > 0){
            $this->load->model("user_model");
            $user = $this->user_model->findOneByNationalId($id);
            if (count($user) > 0){
                if ($loan["user"] == $user["id"]){
                    return array(
                        "loan" => $loan,
                        "user" => $user
                    );
                }else{
                    return array();
                }
            }else{
                return array();
            }
        }else{
            return array();
        }
    }
    public function applyLoan($id,$amount,$type){
        $this->load->model("user_model");

        $user = $this->user_model->findOneByNationalId($id);
        $counter = $this->getLoanById($type);
        if (count($counter) > 0 && count($user) > 0){
            if ($user['status'] == 'active'){
                $data = array(
                    'user' => $user['id'], 
                    'loan_type' => $counter['id'],
                    'amount_borrowed' => $this->db->escape_str($amount), 
                    'interest' => $counter['interest'], 
                    'amount_to_pay' => ((($counter['interest']/100) * $amount) + $amount), 
                    'balance' => ((($counter['interest']/100) * $amount) + $amount)
                );

                if ($this->db->insert("tbl_loan",$data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}