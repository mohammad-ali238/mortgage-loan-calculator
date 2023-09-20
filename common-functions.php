<?php

function mcalc_currency_sign() {
    $currency = '$';

    return $currency;
}

function mcalc_months_arr_data() {
    $data = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    return $data;
}

function mcalc_formatted_amount($amount, $currency = true) {
    $amount = ($currency ? mcalc_currency_sign() : '') . number_format($amount, 2);

    return $amount;
}

function mcalc_check_numberfield($num) {
    $num = preg_replace('/[^0-9.]+/', '', $num);

    return $num;
}

function mcalc_calculate_loan() {
    $is_posted = false;
    $home_val = '400000';
    $down_payment = '80000';
    $downpay_type = 'amount';
    $loan_amount = '320000';
    $interest_rate = '4';
    $loan_term = '5';
    $start_month = date('m') - 1;
    $start_year = date('Y');
    $property_tax = '2000';
    $pmi = '0.5';
    $home_ins = '1000';
    $monthly_hoa = '0';
    $loan_type = 'Conventional';
    $purchase_type = 'Buy';

    if (isset($_POST['mcalc_loan_submit']) &&  $_POST['mcalc_loan_submit'] == 1) {
        $is_posted = true;
        if (isset($_POST['home_val'])) {
            $home_val = $_POST['home_val'];
        }
        if (isset($_POST['down_payment'])) {
            $down_payment = $_POST['down_payment'];
        }
        if (isset($_POST['downpay_type'])) {
            $downpay_type = $_POST['downpay_type'];
        }
        if (isset($_POST['loan_amount'])) {
            $loan_amount = $_POST['loan_amount'];
        }
        if (isset($_POST['interest_rate'])) {
            $interest_rate = $_POST['interest_rate'];
        }
        if (isset($_POST['loan_term'])) {
            $loan_term = $_POST['loan_term'];
        }
        if (isset($_POST['start_month'])) {
            $start_month = $_POST['start_month'];
        }
        if (isset($_POST['start_year'])) {
            $start_year = $_POST['start_year'];
        }
        if (isset($_POST['property_tax'])) {
            $property_tax = $_POST['property_tax'];
        }
        if (isset($_POST['pmi'])) {
            $pmi = $_POST['pmi'];
        }
        if (isset($_POST['home_ins'])) {
            $home_ins = $_POST['home_ins'];
        }
        if (isset($_POST['monthly_hoa'])) {
            $monthly_hoa = $_POST['monthly_hoa'];
        }
        if (isset($_POST['loan_type'])) {
            $loan_type = $_POST['loan_type'];
        }
        if (isset($_POST['purchase_type'])) {
            $purchase_type = $_POST['purchase_type'];
        }
    }
    if ($loan_term < 1) {
        $loan_term = 1;
    }

    $errors = [];

    $home_val = mcalc_check_numberfield($home_val);
    $down_payment = mcalc_check_numberfield($down_payment);
    $loan_amount = mcalc_check_numberfield($loan_amount);
    if ($home_val <= 0) {
        $errors[] = 'Home value must be greater than 0.';
    }
    if ($loan_amount <= 0) {
        $errors[] = 'Loan amount must be greater than 0.';
    }
    if ($interest_rate <= 0) {
        $errors[] = 'Interest rate must be greater than 0.';
    }

    if (!empty($errors)) {
        return [
            'is_error' => true,
            'errors' => $errors
        ];
    }

    if ($downpay_type == 'perc') {
        $down_payment_rate = $down_payment;
        $down_payment = ($home_val*$down_payment_rate) / 100;
    } else {
        $down_payment_rate = ($down_payment*100) / $loan_amount;
    }

    $result_property_tax = $property_tax > 0 ? $property_tax/12 : 0;
    $result_insurance = $home_ins > 0 ? $home_ins/12 : 0;

    // Loan to value LTV
    $loan_to_val = ($loan_amount*100)/$home_val;

    $result_pmi = 0;
    if ($loan_to_val > 80.5 && $pmi > 0) { // if above 80
        $result_pmi = (($loan_amount * $pmi)/100) / 12; // per month
    }
    //var_dump($result_property_tax);
    //var_dump($result_insurance);
    //var_dump($result_pmi);

    $d3_value = $loan_term * 12;
    $d8_value = ($interest_rate/100)/12;
    $d8_plus1 = $d8_value + 1;

    $principal_and_interst = $loan_amount*(($d8_value*pow($d8_plus1,$d3_value))/(pow($d8_plus1,$d3_value) - 1));
    //var_dump($principal_and_interst);
    $monthly_paymet_result = $principal_and_interst + $result_property_tax + $result_insurance + $monthly_hoa + $result_pmi;
    //var_dump($monthly_paymet_result);

    $monthly_payment = mcalc_formatted_amount($monthly_paymet_result);

    // monthly calcs
    $startin_month = ($start_month + 1);
    $startin_month = $startin_month < 10 ? '0' . $startin_month : $startin_month;
    $start_date = '01-' . $startin_month . '-' . $start_year;
    //var_dump($start_date);
    $start_date_time = strtotime($start_date);

    $total_months = $loan_term * 12;

    $property_tax_total = $result_property_tax > 0 ? $result_property_tax * $total_months : 0;

    $inurance_total = $result_insurance > 0 ? $result_insurance * $total_months : 0;

    $hoa_fee_total = $monthly_hoa > 0 ? $monthly_hoa * $total_months : 0;

    $converted_start_date = date('Y-m-d', $start_date_time);
    $payoff_date = strtotime("+ $total_months months", strtotime($converted_start_date));

    $remainin_yearly_balnce = $yearly_balance = $yearly_totalpay = $yearly_extrapay = $principle_cumlative = $interst_cumlative = 0;
    $major_total = $all_interest_total = 0;
    $yearly_intrsts_arr = $yearly_princple_arr = $yearly_balance_arr = array();
    $table_data = array();
    $months_countr = 1;
    for ($date = $start_date_time; $date <= $payoff_date; $date = strtotime("+1 month", $date)) {
        //echo date('d-m-Y', $date); echo '<br>';
        if ($date == $start_date_time) {
            $remianing_balance_amount = $loan_amount;

            $this_month_interst = (($loan_amount * $interest_rate)/100) / 12;
        } else {
            $this_month_interst = (($remianing_balance_amount * $interest_rate)/100) / 12;
        }
        $this_month_totalpay = $this_month_interst;


        $principle_pay = $principal_and_interst - $this_month_interst;
        $this_month_totalpay += $principle_pay;
        $remianing_balance_amount = $remianing_balance_amount - $principle_pay;

        $principle_cumlative += $principle_pay;
        $interst_cumlative += $this_month_interst;
        //
        $yearly_totalpay += $this_month_totalpay;

        //
        $yearly_extrapay += $result_property_tax;
        $yearly_extrapay += $result_insurance;
        $yearly_extrapay += $result_pmi;

        $this_month_numb = date('m', $date);
        $this_year_numb = date('Y', $date);
        
        if ($this_month_numb == 1 || $date == $start_date_time) {
            //
        }

        if ($this_month_numb == 12 || $date == $payoff_date) {
            //
            $yearly_intrsts_arr[] = number_format($interst_cumlative, 2, '.', '');
            $yearly_princple_arr[] = number_format($principle_cumlative, 2, '.', '');
            
            //
            $major_total += $yearly_totalpay;
            $all_interest_total += $interst_cumlative;
            if ($remainin_yearly_balnce == 0) {
                $remainin_yearly_balnce = $loan_amount - $principle_cumlative;
                $yearly_balance = $remainin_yearly_balnce;
            } else {
                $remainin_yearly_balnce = $remainin_yearly_balnce - $principle_cumlative;
                $yearly_balance = $remainin_yearly_balnce;
            }
            if ($yearly_balance < 0) {
                $major_total += $yearly_balance;
                $yearly_balance = 0;
            }
            $table_data[$this_year_numb]['total'] = array(
                'principal' => $principle_cumlative,
                'interest' => $interst_cumlative,
                'total_pay' => $yearly_totalpay,
                'extra_pay' => $yearly_extrapay,
                'balance' => $yearly_balance,
            );

            //
            $yearly_balance_arr[] = number_format($yearly_balance, 2, '.', '');

            //
            $yearly_balance = $yearly_totalpay = $yearly_extrapay = $principle_cumlative = $interst_cumlative = 0;
        }
        $months_countr++;
    }
    $major_total += $property_tax_total;
    $major_total += $inurance_total;
    $major_total += $hoa_fee_total;

    //var_dump($property_tax_total);
    //var_dump($inurance_total);
    //var_dump($hoa_fee_total);
    //var_dump($major_total);
    //echo '<pre>';
    //var_dump($table_data);
    //echo '</pre>';
    //

    $data = [
        'total_months' => $total_months,
        'yearly_intrsts_arr' => $yearly_intrsts_arr,
        'yearly_princple_arr' => $yearly_princple_arr,
        'yearly_balance_arr' => $yearly_balance_arr,
        'start_date_time' => $start_date_time,
        'payoff_date' => $payoff_date,
        'down_payment' => mcalc_formatted_amount($down_payment),
        'downpay_rate' => mcalc_formatted_amount($down_payment_rate, false),
        'monthly_payment' => $monthly_payment,
        'total_pay' => mcalc_formatted_amount($major_total),
        'interest' => mcalc_formatted_amount($all_interest_total),
    ];
    if ($result_pmi > 0) {
        $data['have_pmi'] = 1;
        $data['pmi_amount'] = mcalc_formatted_amount($result_pmi);
    } else {
        $data['pmi_amount'] = 'not required';
    }
    if ($property_tax_total > 0) {
        $data['monthly_property_tax'] = mcalc_formatted_amount($result_property_tax);
        $data['property_tax'] = mcalc_formatted_amount($property_tax_total);
    }
    if ($inurance_total > 0) {
        $data['monthly_insurance'] = mcalc_formatted_amount($result_insurance);
        $data['insurance'] = mcalc_formatted_amount($inurance_total);
    }
    if ($hoa_fee_total > 0) {
        $data['monthly_hoa'] = mcalc_formatted_amount($monthly_hoa);
        $data['hoa'] = mcalc_formatted_amount($hoa_fee_total);
    }

    return [
        'is_error' => false,
        'data' => $data
    ];
}
