<?php
$cnp = '1970710060028';

function isCnpValid($cnp_input) {
    // verificam daca cnp-ul contine doar cifre
    if (is_numeric($cnp_input)) {
        // verificam cnp-ul daca contine exact 13 cifre
        if (strlen($cnp_input) === 13) {
            $gender_and_century = substr($cnp_input, 0, -12);
            // anul nasterii
            $birth_year = substr($cnp_input, 1, -10);
            // luna nasterii
            $birth_month = substr($cnp_input, 3, -8);
            // ziua nasterii
            $birth_day = substr($cnp_input, 5, -6);
            $is_valid_date = false;
            $current_year = date("y");
            $before_2000 = ['1', '2', '3', '4', '7', '8', '9'];
            
            // verificam ca prima cifra sa nu fie 0
            if ($gender_and_century !== '0') {
                // verificam doar daca anul curent > anul nasterii, in rest nu cred ca alte verificari au rost pentru anul nasterii :-??
                // verificare pentru cei nascuti dupa 2000 - (presupunem ca anul curent nu are cum sa fie prin 1900)
                if (!in_array($gender_and_century, $before_2000)) {
                    if ($birth_year > $current_year) {
                        return 'falseee';
                    }
                }
                
                // verificam daca data nasterii exista in calendar
                if ($gender_and_century === '1' || $gender_and_century === '2' || $gender_and_century === '7' || $gender_and_century === '8' || $gender_and_century === '9') {
                    $is_valid_date = checkdate($birth_day, $birth_month, '19' . $birth_year);
                } else if ($gender_and_century === '3' || $gender_and_century === '4') {
                    $is_valid_date = checkdate($birth_day, $birth_month, '18' . $birth_year);
                } else if ($gender_and_century === '5' || $gender_and_century === '6') {
                    $is_valid_date = checkdate($birth_day, $birth_month, '20' . $birth_year);
                }
                
                if ($is_valid_date === true) {
                    $birth_country = substr($cnp_input, 7, -4);
                    
                    // verificam daca judetul nasterii este valid
                    if (($birth_country >= 1 && $birth_country < 47) || $birth_country === '51' || $birth_country === '52') {
                        // nu cred ca are rost sa mai verificam si NNN :-??
                        $control_number_explode = str_split('279146358279');
                        $cnp_explode = str_split($cnp_input);
                        $sum_n = 0;
                        
                        for ($x = 0; $x <= count($control_number_explode); $x++) {
                            $sum_n += $control_number_explode[$x] * $cnp_explode[$x];
                        }
                        
                        // impartim la suma 11 cu 2 zecimale rest
                        $sum_n = bcdiv($sum_n, 11, 2);
                        // separam numarul intreg de zecimale
                        $sum_n = explode('.', $sum_n);
                        
                        // separam si zecimalele
                        $zecimale_separate = str_split($sum_n[1]);
                        
                        // verificam daca a doua zecimala este 0 sau nu (daca nu e zero atunci rotunjim prima la numarul mai mare)
                        if ((int)$zecimale_separate[1] !== 0) {
                            $control_number = $zecimale_separate[0] + 1;
                        } else {
                            $control_number = $zecimale_separate[0];
                        }
                        
                        // daca numarul de control coincide
                        if ((int)$cnp_explode[12] === (int)$control_number) {
                            return 'trueee';
                        }
                    }
                    
                }
            } else {
                // daca prima cifra este 0 atunci cnp-ul nu e valid
                return 'falseee';
            }
        }
    }
    return 'falseee';
}

echo isCnpValid($cnp);
?>
