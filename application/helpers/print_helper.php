<?php
function elipsis($string, $max_lenghth){
    return  strlen($string) > $max_lenghth ? substr($string,0,$max_lenghth)." ..." : $string;
}

function loan_type($type){
    $obj = new stdClass;
    switch ($type) {
        case 564: #brand new
            $obj->x  = 115.5; 
            $obj->y  = 32;
            break;
        case 565: #second hand
            $obj->x  = 115.5; 
            $obj->y  = 35;
            break;
    }
    return $obj;
}

function marital_status($status){
    $obj = new stdClass;
    switch ($status) {
        case 568: #Single
            $obj->x  = 19; 
            $obj->y  = 75;
            break;
        case 569: #Married
            $obj->x  = 19; 
            $obj->y  = 78;
            break;
        case 570: #Widow
            $obj->x  = 31.5; 
            $obj->y  = 75;
            break;
        case 644: #Legally Separated
            $obj->x  = 31.5; 
            $obj->y  = 78;
            break; 
        case 645: #Live In
            $obj->x  = 67; 
            $obj->y  = 75;
            break;      
    }
    return $obj;
}

function Education($id){
    $obj = new stdClass;
    switch ($id) {
        case 580: #elementary
            $obj->x  = 22.8; 
            $obj->y  = 83;
            break;
        case 581: #HS
            $obj->x  = 22.8; 
            $obj->y  = 86;
            break;
        case 582: #College under
            $obj->x  = 44.5; 
            $obj->y  = 83;
            break;
        case 583: #College grad
            $obj->x  = 44.5; 
            $obj->y  = 86;
            break; 
        case 684: #Vocational
            $obj->x  = 66.7; 
            $obj->y  = 83;
            break;
        case 685: #Post Grad
            $obj->x  = 66.7; 
            $obj->y  = 86;
            break;      
    }
    return $obj;
}

function individual_or_enganged_in_business($id){
    $obj = new stdClass;
    switch ($id) {
        #INDIVIDUAL
        case 571: #Workers in Formal Sector (Employed)
            $obj->x  = 9.4; 
            $obj->y  = 98.6;
            break;
        case 572: #Workers in Informal Sector (SelfEmployed)
            $obj->x  = 9.4; 
            $obj->y  = 101.6;
            break;
        case 573: #Migrant workers
            $obj->x  = 9.4; 
            $obj->y  = 105;
            break; 
        case 574: #Pensioner
            $obj->x  = 9.4; 
            $obj->y  = 107.9;
            break;
        case 575: #Driver
            $obj->x  = 9.4; 
            $obj->y  = 111;
            break;
        case 576: #Farmer
            $obj->x  = 9.4; 
            $obj->y  = 114.5;
            break;
        case 578: #Fisher Folk
            $obj->x  = 9.4; 
            $obj->y  = 117.8;
            break;
        case 579: #Recipient of Remittance
            $obj->x  = 9.4; 
            $obj->y  = 120.5;
            break;
        #ENGAGED IN BUSINESS
        case 586: #Single Proprietorship
            $obj->x  = 60.1; 
            $obj->y  = 98.5;
            break;
        case 587: #Partnership
            $obj->x  = 60.1; 
            $obj->y  = 101.5;
            break;
        case 588: #Corporate
            $obj->x  = 60.1; 
            $obj->y  = 104.5;
            break;      
    }
    return $obj;
}

function asset_size_business($id){
    $obj = new stdClass;
    switch ($id) {
        #INDIVIDUAL
        case 571: #Up to P 1.5M
            $obj->x  = 9.4; 
            $obj->y  = 98.6;
            break;
        case 572: #More than P 1.5M up to P 15M
            $obj->x  = 9.4; 
            $obj->y  = 101.6;
            break;
        case 573: #More than P 15M up to P 100M
            $obj->x  = 9.4; 
            $obj->y  = 105;
            break; 
        case 574: #More than P 100M
            $obj->x  = 9.4; 
            $obj->y  = 107.9;
            break;
    }
    return $obj;
}

function nature_of_business($id){
    $obj = new stdClass;
    switch ($id) {
        case 593: #Agriculture, Forestry and Fishing
            $obj->x  = 91; 
            $obj->y  = 98.6;
            break;
        case 594: #Mining And Quarrying
            $obj->x  = 91; 
            $obj->y  = 101.8;
            break;
        case 595: #Manufacturing
            $obj->x  = 91; 
            $obj->y  = 105;
            break; 
        case 596: #Electricity, gas, steam and air-conditioning supply
            $obj->x  = 91; 
            $obj->y  = 108.2;
            break;
        case 597: #Water supply, sewerage, waste
            $obj->x  = 91; 
            $obj->y  = 112.8;
            break;
        case 598: #Construction
            $obj->x  = 91; 
            $obj->y  = 117.4;
            break;
        case 599: #Wholesale and Retail trade, repair of motor vehicles and motortcycles
            $obj->x  = 91; 
            $obj->y  = 120.4;
            break; 
        case 600: #Transportation and storage
            $obj->x  = 133.5; 
            $obj->y  = 96.6;
            break;
        case 601: #Accomodation and food service acitivities
            $obj->x  = 133.5; 
            $obj->y  = 99.5;
            break;
        case 602: #Information and communication
            $obj->x  = 133.5; 
            $obj->y  = 103.6;
            break;
        case 603: #Financial and insurance activities
            $obj->x  = 133.5; 
            $obj->y  = 106.4;
            break;
        case 604: #Real estate activities
            $obj->x  = 133.5; 
            $obj->y  = 109.5;
            break;
        case 605: #Professional, scientifi and technical services
            $obj->x  = 133.5; 
            $obj->y  = 112.8;
            break;
        case 606: #administrative and support service activities
            $obj->x  = 133.5; 
            $obj->y  = 117.3;
            break;
        case 607: #Public administrative and defense compulsory social security
            $obj->x  = 133.5; 
            $obj->y  = 121.5;
            break;
        case 608: #Education
            $obj->x  = 170.1; 
            $obj->y  = 96.8;
            break;
        case 609: #Human health and social work activities
            $obj->x  = 170.1; 
            $obj->y  = 100;
            break;
        case 610: #Arts, entertainment and recreation
            $obj->x  = 170.1; 
            $obj->y  = 103;
            break;
        case 611: #Other service activities
            $obj->x  = 170.1; 
            $obj->y  = 106;
            break;
        case 612: #Activities of private households as employers and undiffirentiated goods and services and producing acitvities of households for own use
            $obj->x  = 170.1; 
            $obj->y  = 109.5;
            break;
        case 613: #Activities of extraterritorial organizations and bodies
            $obj->x  = 170.1; 
            $obj->y  = 119;
            break;
    }
    return $obj;
}

function mortgaged($id){
    $obj = new stdClass;
    switch ($id) {
        case 614: #Owned
        case 842:
            $obj->x      = 9.5; 
            $obj->y      = 149; //155
            $obj->symbol = '/';
            break;
        default:
            $obj->x      = 55.5; 
            $obj->y      = 155.2;
            $obj->symbol = '';
            break;
        break;
    }
    return $obj;
}

function residence_ownership_sub($id){
     $obj = new stdClass;
    switch ($id) {
        case 614: #Owned
            $obj->x      = 13.5; 
            $obj->y      = 152;
            $obj->symbol = '/';
            break;
        case 842: #Mortgaged
            $obj->x      = 13.5; 
            $obj->y      = 155;
            $obj->symbol = '/';
            break;
        default:
            $obj->x      = 0; 
            $obj->y      = 0;
            $obj->symbol = '';
        break;
    }

}
function residence_ownership($id){
    $obj = new stdClass;
    switch ($id) {
        case 614: #Owned Mortgaged
            $obj->x      = 13.5; 
            $obj->y      = 152; 
            $obj->symbol = '/';
            break;
        case 842: #Not Mortgaged
            $obj->x      = 13.5; 
            $obj->y      = 155;
            $obj->symbol = '/';
            break;
        case 619: #Renting
            $obj->x      = 55.5; 
            $obj->y      = 152;
            $obj->symbol = '/';
            break; 
        case 615: #Living With Parents
            $obj->x      = 31.5; 
            $obj->y      = 149;
            $obj->symbol = '/';
            break;
        case 616: #Living With Relatives
            $obj->x      = 31.5; 
            $obj->y      = 152;
            $obj->symbol = '/';
            break;
        case 617: #Bed Spacer
            $obj->x      = 31.5; 
            $obj->y      = 155.2;
            $obj->symbol = '/';
            break;
        case 618: #Company Quarters
            $obj->x      = 55.5; 
            $obj->y      = 149;
            $obj->symbol = '/';
            break;
        case 620: #Others
            $obj->x      = 55.5; 
            $obj->y      = 155.2;
            $obj->symbol = '/';
            break;
        default:
            $obj->x      = 55.5; 
            $obj->y      = 155.2;
            $obj->symbol = '/';
        break;
    }
    return $obj;
}

function mobile_type($id){
    $obj = new stdClass;
    switch ($id) {
        case 623: #Prepaid
            $obj->x  = 119.5; 
            $obj->y  = 153;
            break;
        case 624: #Postpaid
            $obj->x  = 132.7; 
            $obj->y  = 153;
            break;
    }
    return $obj;
}

function business_registered($id){
    $obj = new stdClass;
    switch ($id) {
        case 561: #Yes
            $obj->x  = 176; 
            $obj->y  = 268.3;
            break;
        case 562: #No
            $obj->x  = 186.5; 
            $obj->y  = 268.3;
            break;
    }
    return $obj;
}

function employement_status($id){
    $obj = new stdClass;
    switch ($id) {
        case 625: #Regular / Permanent
            $obj->x  = 9.4; 
            $obj->y  = 277;
            break;
        case 626: #Contractual / Project Hired / Consultant
            $obj->x  = 9.4; 
            $obj->y  = 280;
            break;
        case 627: #Fixed term
            $obj->x  = 9.4; 
            $obj->y  = 283;
            break;
        case 628: #Pensioner
            $obj->x  = 47.8; 
            $obj->y  = 277;
            break;
        case 629: #Recipient of remittance
            $obj->x  = 47.8; 
            $obj->y  = 280;
            break;
        case 630: #self - employed / Freelance
            $obj->x  = 47.8; 
            $obj->y  = 283;
            break;
        case 631: #Probationary / Trainee
            $obj->x  = 75.5; 
            $obj->y  = 277;
            break;
        case 632: #Others (Please Specify)
            $obj->x  = 75.5; 
            $obj->y  = 280;
            break;
    }
    return $obj;
}

function source_of_fund($id){
    $obj = new stdClass;
    switch ($id) {
        case 633: #Employment
            $obj->x  = 9.4; 
            $obj->y  = 19.8;
            break;
        case 634: #Business
            $obj->x  = 9.4; 
            $obj->y  = 23;
            break;
        case 635: #Remittance
            $obj->x  = 27.5; 
            $obj->y  = 19.8;
            break;
        case 636: #Pension
            $obj->x  = 27.5; 
            $obj->y  = 23;
            break;
        case 637: #Others(Please Specify)
            $obj->x  = 44.5; 
            $obj->y  = 19.8;
            break;
    }
    return $obj;
}
?>