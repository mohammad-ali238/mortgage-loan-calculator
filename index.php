<?php include 'common-functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculator</title>

    <link href="./css/style.css?ver=<?php echo rand(10000, 50000) ?>" rel="stylesheet" media="all">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <?php
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
    $calc_processed = false;
    ?>
    <div class="page-main">
        <div class="calculator-main-con">
            <div class="calculator-figures">
                <form method="post">
                    <div class="calculate-figitem">
                        <div class="calc-item-label">
                            <label>Home Value</label>
                        </div>
                        <div class="calc-item-val">
                            <div class="calc-item-field">
                                <input type="number" name="home_val" value="<?php echo ($home_val) ?>" placeholder="400000">
                            </div>
                            <div class="calc-item-subfield">
                                <span><?php echo mcalc_currency_sign() ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="calculate-figitem">
                        <div class="calc-item-label">
                            <label>Down Payment</label>
                        </div>
                        <div class="calc-item-val">
                            <div class="calc-item-field">
                                <input type="number" name="down_payment" value="<?php echo ($down_payment) ?>" placeholder="80000">
                            </div>
                            <div class="calc-item-subfield">
                                <div class="chkradio-field">
                                    <input type="radio" name="downpay_type" value="amount"<?php echo ($downpay_type == 'amount' ? ' checked' : '') ?>>
                                    <span>$</span>
                                </div>
                                <div class="chkradio-field">
                                    <input type="radio" name="downpay_type" value="perc"<?php echo ($downpay_type == 'perc' ? ' checked' : '') ?>>
                                    <span>%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="calculate-figitem">
                        <div class="calc-item-label">
                            <label>Loan Amount</label>
                        </div>
                        <div class="calc-item-val">
                            <div class="calc-item-field">
                                <input type="number" name="loan_amount" value="<?php echo ($loan_amount) ?>" placeholder="0">
                            </div>
                            <div class="calc-item-subfield">
                                <span>$</span>
                            </div>
                        </div>
                    </div>
                    <div class="calculate-figitem">
                        <div class="calc-item-label">
                            <label>Interest Rate</label>
                        </div>
                        <div class="calc-item-val">
                            <div class="calc-item-field">
                                <input type="number" name="interest_rate" value="<?php echo ($interest_rate) ?>" placeholder="4">
                            </div>
                            <div class="calc-item-subfield">
                                <span>%</span>
                            </div>
                        </div>
                    </div>
                    <div class="calculate-figitem">
                        <div class="calc-item-label">
                            <label>Loan Term</label>
                        </div>
                        <div class="calc-item-val">
                            <div class="calc-item-field">
                                <input type="number" name="loan_term" value="<?php echo ($loan_term) ?>" placeholder="5">
                            </div>
                            <div class="calc-item-subfield">
                                <span>years</span>
                            </div>
                        </div>
                    </div>
                    <div class="calculate-figitem">
                        <div class="calc-item-label">
                            <label>Start Date</label>
                        </div>
                        <?php
                        $curr_year = date('Y');
                        $max_year = $curr_year + 4;
                        $months_data = mcalc_months_arr_data();
                        //var_dump($months_data);
                        ?>
                        <div class="calc-item-val">
                            <div class="calc-item-field">
                                <select name="start_month" class="loan-start-month">
                                    <?php
                                    foreach ($months_data as $month_val => $mnth_labl) {
                                        $selected = '';
                                        if ($start_month == $month_val) {
                                            $selected = ' selected';
                                        }
                                        ?>
                                        <option value="<?php echo ($month_val) ?>"<?php echo ($selected) ?>><?php echo ($mnth_labl) ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <select name="start_year" class="loan-start-year">
                                    <?php
                                    for ($strt_year = $curr_year; $strt_year <= $max_year; $strt_year++) {
                                        ?>
                                        <option value="<?php echo ($strt_year) ?>"<?php echo ($start_year == $strt_year ? ' selected' : '') ?>><?php echo ($strt_year) ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="calculate-figitem">
                        <div class="calc-item-label">
                            <label>Property Tax</label>
                        </div>
                        <div class="calc-item-val">
                            <div class="calc-item-field">
                                <input type="number" name="property_tax" value="<?php echo ($property_tax) ?>" placeholder="2000">
                            </div>
                            <div class="calc-item-subfield">
                                <span>$/yr</span>
                            </div>
                        </div>
                    </div>
                    <div class="calculate-figitem">
                        <div class="calc-item-label">
                            <label>PMI</label>
                        </div>
                        <div class="calc-item-val">
                            <div class="calc-item-field">
                                <input type="number" name="pmi" value="<?php echo ($pmi) ?>" placeholder="0.5">
                            </div>
                            <div class="calc-item-subfield">
                                <span>%</span>
                            </div>
                        </div>
                    </div>
                    <div class="calculate-figitem">
                        <div class="calc-item-label">
                            <label>Home Ins</label>
                        </div>
                        <div class="calc-item-val">
                            <div class="calc-item-field">
                                <input type="number" name="home_ins" value="<?php echo ($home_ins) ?>" placeholder="1000">
                            </div>
                            <div class="calc-item-subfield">
                                <span>$/yr</span>
                            </div>
                        </div>
                    </div>
                    <div class="calculate-figitem">
                        <div class="calc-item-label">
                            <label>Monthly HOA</label>
                        </div>
                        <div class="calc-item-val">
                            <div class="calc-item-field">
                                <input type="number" name="monthly_hoa" value="<?php echo ($monthly_hoa) ?>" placeholder="0">
                            </div>
                            <div class="calc-item-subfield">
                                <span>$</span>
                            </div>
                        </div>
                    </div>
                    <div class="calculate-figitem">
                        <div class="calc-item-label">
                            <label>Loan Type</label>
                        </div>
                        <div class="calc-item-val">
                            <div class="calc-item-field">
                                <select name="loan_type">
                                    <option<?php echo ($loan_type == 'Conventional' ? ' selected' : '') ?>>Conventional</option>
                                    <option<?php echo ($loan_type == 'FHA' ? ' selected' : '') ?>>FHA</option>
                                    <option<?php echo ($loan_type == 'VA' ? ' selected' : '') ?>>VA</option>
                                    <option<?php echo ($loan_type == 'USDA' ? ' selected' : '') ?>>USDA</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="calculate-figitem">
                        <div class="calc-item-label">
                            <label>Buy or Refi</label>
                        </div>
                        <div class="calc-item-val">
                            <div class="calc-item-field">
                                <select name="purchase_type">
                                    <option<?php echo ($purchase_type == 'Buy' ? ' selected' : '') ?>>Buy</option>
                                    <option<?php echo ($purchase_type == 'Refi' ? ' selected' : '') ?>>Refi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="calculate-figitem loanfrm-submit-btncon">
                        <input type="hidden" name="mcalc_loan_submit" value="1">
                        <button type="submit">Calculate</button>
                    </div>
                </form>
            </div>
            <?php
            $calc_summry_data = mcalc_calculate_loan();
            ?>
            <div class="calculator-details">
                <div class="loan-summary-heading"><h2>Loan Summary</h2></div>
                <?php
                if (isset($calc_summry_data['is_error']) && $calc_summry_data['is_error'] === true) {
                    $_summry_err = $calc_summry_data['errors'];
                    if (!empty($_summry_err)) {
                        echo '<div class="calc-process-errs" style="color: #f00;">';
                        echo '<div>Please fix following errors;</div>';
                        foreach ($_summry_err as $err_txt) {
                            echo '<div>' . $err_txt . '</div>';
                        }
                        echo '</div>';
                    }
                }
                if (isset($calc_summry_data['is_error']) && $calc_summry_data['is_error'] === false) {
                    $calc_processed = true;
                    $_summry_data = $calc_summry_data['data'];
                    //var_dump($_summry_data);
                    $payof_date = $_summry_data['payoff_date'];
                    $payof_date = date('M Y', $payof_date);
                    ?>
                    <div class="loan-summry-ditms">
                        <div class="summry-ditm-con">
                            <div><span><?php echo ($_summry_data['monthly_payment']) ?></span></div>
                            <div><strong>Total Monthly Payment</strong></div>
                        </div>
                        <?php
                        if (isset($_summry_data['have_pmi'])) {
                            ?>
                            <div class="summry-ditm-con">
                                <div><strong><?php echo ($_summry_data['pmi_amount']) ?></strong></div>
                                <div><span>PMI Payment</span></div>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="summry-ditm-con">
                                <div><strong>PMI</strong></div>
                                <div><span>not required</span></div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="loan-summry-ditms">
                        <div class="summry-ditm-con">
                            <div><span><?php echo ($_summry_data['down_payment']) ?></span></div>
                            <div><strong>Down Payment Amount</strong></div>
                        </div>
                        <div class="summry-ditm-con">
                            <div><span><?php echo ($_summry_data['downpay_rate']) ?>%</span></div>
                            <div><strong>Down Payment %</strong></div>
                        </div>
                    </div>
                    <div class="loan-summry-ditms">
                        <div class="summry-ditm-con">
                            <div><span><?php echo ($payof_date) ?></span></div>
                            <div><strong>Loan pay-off Date</strong></div>
                        </div>
                        <div class="summry-ditm-con">
                            <div><span><?php echo ($_summry_data['interest']) ?></span></div>
                            <div><strong>Total Interest Paid</strong></div>
                        </div>
                    </div>
                    <?php
                    if (isset($_summry_data['property_tax'])) {
                        ?>
                        <div class="loan-summry-ditms">
                            <div class="summry-ditm-con">
                                <div><span><?php echo ($_summry_data['monthly_property_tax']) ?></span></div>
                                <div><strong>Monthly Tax Paid</strong></div>
                            </div>
                            <div class="summry-ditm-con">
                                <div><span><?php echo ($_summry_data['property_tax']) ?></span></div>
                                <div><strong>Total Tax Paid</strong></div>
                            </div>
                        </div>
                        <?php
                    }
                    if (isset($_summry_data['monthly_hoa'])) {
                        ?>
                        <div class="loan-summry-ditms">
                            <div class="summry-ditm-con">
                                <div><span><?php echo ($_summry_data['monthly_hoa']) ?></span></div>
                                <div><strong>Monthly HOA fees</strong></div>
                            </div>
                            <div class="summry-ditm-con">
                                <div><span><?php echo ($_summry_data['hoa']) ?></span></div>
                                <div><strong>Total HOA fees</strong></div>
                            </div>
                        </div>
                        <?php
                    }
                    if (isset($_summry_data['monthly_insurance'])) {
                        ?>
                        <div class="loan-summry-ditms">
                            <div class="summry-ditm-con">
                                <div><span><?php echo ($_summry_data['monthly_insurance']) ?></span></div>
                                <div><strong>Monthly Home Insurance</strong></div>
                            </div>
                            <div class="summry-ditm-con">
                                <div><span><?php echo ($_summry_data['insurance']) ?></span></div>
                                <div><strong>Total Home Insurance</strong></div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="loan-summry-ditms">
                        <div class="summry-ditm-con">
                            <div><span><?php echo ($_summry_data['total_pay']) ?></span></div>
                            <div><strong>Total of <?php echo ($_summry_data['total_months']) ?> Payments</strong></div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
        if ($calc_processed) {
            $rand_id = rand(100000, 999999);

            $start_date_time = $_summry_data['start_date_time'];
            $yearly_intrsts_arr = $_summry_data['yearly_intrsts_arr'];
            $yearly_princple_arr = $_summry_data['yearly_princple_arr'];
            $yearly_balance_arr = $_summry_data['yearly_balance_arr'];

            $yearly_intrsts_list = implode(', ', $yearly_intrsts_arr);
            $yearly_princple_list = implode(', ', $yearly_princple_arr);
            $yearly_balance_list = implode(', ', $yearly_balance_arr);
            
            $start_year = date('Y', $start_date_time);
            $total_years = $loan_term;
            $years_arr = array();
            for ($ye = $start_year; $ye <= ($start_year + $total_years); $ye++) {
                $years_arr[] = $ye;
            }
            $years_labels = implode(', ', $years_arr);
            ?>
            <canvas id="calc-chart-<?php echo ($rand_id) ?>" height="87vh"></canvas>
            <script data-no-optimize="1">
                jQuery(document).ready(function() {
                    const chart_labels = [<?php echo ($years_labels) ?>];
                    const chart_data = {
                        labels: chart_labels,
                        datasets: [
                            {
                                label: 'Balance',
                                data: [<?php echo ($yearly_balance_list) ?>],
                                borderColor: ['#e76f51'],
                                borderWidth: 0,
                                backgroundColor: ['#e76f51']
                            },
                            {
                                label: 'Interest',
                                data: [<?php echo ($yearly_intrsts_list) ?>],
                                borderColor: ['#f4a261'],
                                borderWidth: 0,
                                backgroundColor: ['#f4a261']
                            },
                            {
                                label: 'Principal',
                                data: [<?php echo ($yearly_princple_list) ?>],
                                borderColor: ['#e9c46a'],
                                borderWidth: 0,
                                backgroundColor: ['#e9c46a']
                            }
                        ]
                    };

                    var ctx = document.getElementById('calc-chart-<?php echo ($rand_id) ?>');
                    var POINT_X_PREFIX = '$';
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: chart_data,
                        options: {

                            scales: {
                                y: {
                                    ticks: {
                                        // Include a dollar sign in the ticks
                                        callback: function (value, index, values) {
                                            return '$ ' + value;
                                        }
                                    },
                                    grid: {
                                        display: false
                                    },
                                    stacked: true
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    stacked: true
                                }
                            },
                            responsive: true,
                            plugins: {
                                title: {
                                    display: false,
                                    text: ''
                                },
                                legend: {
                                    display: true,
                                    labels: {
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            var label = context.dataset.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            if (context.parsed.y !== null) {
                                                label += new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(context.parsed.y);
                                            }
                                            return label;
                                        }
                                    }
                                },
                            }
                        },
                    });
                });
            </script>
            <?php
        }
        ?>
    </div>

    <script type="text/javascript" src="./js/chart.min.js"></script>
</body>
</html>