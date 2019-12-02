<?php
// initial Function
function getStatistics() {
    $data = [];
    $data['users'] = [];
    // 65k rows
    $allTptp = TariffProviderTariffMatch::all();
    foreach ($allTptp->groupBy('user_id') as $each) {
        $one = [];
        $one['name'] = $each[0]->user->first_name . " " . $each[0]->user->last_name;
        $one['valid'] = 0;
        $one['pending'] = 0;
        $one['invalid'] = 0;
        $one['total'] = 0;
        $one['cash'] = 0;
        foreach ($each as $single) {
            switch ($single->active_status) {
                case ActiveStatus::ACTIVE: // 1
                    $one['valid']++;
                    $one['cash'] += floatval(GlobalVariable::getById(GlobalVariable::STANDARDIZATION_UNIT_PRICE)->value);
                    break;
                case ActiveStatus::PENDING: // 2
                    $one['pending']++;
                    break;
                case ActiveStatus::DELETED: // 3
                    $one['invalid']++;
                    break;
            }
            $one['total']++;
        }
        $one['cash'] = number_format($one['cash'],2);
        array_push($data['users'], $one);
    }
    return $data;
}

/* Issues *
 * Line 7 gets all the records from the database without including the users record
 * Line 10 makes a trip to the database to get the user's data for each traffic record
 * */

// update version
function getStatisticsOptimized() {
    $data = [];
    $data['users'] = [];
    // 65k rows
    // eager load the user data as well
    $allTptp = TariffProviderTariffMatch::with('user')->get();

    foreach ($allTptp->groupBy('user_id') as $each) {
        $one = [];
        $one['name'] = $each[0]->user->first_name . " " . $each[0]->user->last_name;
        $one['valid'] = 0;
        $one['pending'] = 0;
        $one['invalid'] = 0;
        $one['total'] = 0;
        $one['cash'] = 0;
        foreach ($each as $single) {
            switch ($single->active_status) {
                case ActiveStatus::ACTIVE: // 1
                    $one['valid']++;
                    $one['cash'] += floatval(GlobalVariable::getById(GlobalVariable::STANDARDIZATION_UNIT_PRICE)->value);
                    break;
                case ActiveStatus::PENDING: // 2
                    $one['pending']++;
                    break;
                case ActiveStatus::DELETED: // 3
                    $one['invalid']++;
                    break;
            }
            $one['total']++;
        }
        $one['cash'] = number_format($one['cash'],2);
        array_push($data['users'], $one);
    }
    return $data;
}
