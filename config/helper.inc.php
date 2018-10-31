<?php

function flash(){

	$session = $GLOBALS['session'];

	if($session->has_flash('success')){
		$success = '<div class="alert alert-success alert-dismissible" role="alert">';
		$success .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$success .= '<b>'.$session->flash('success').'</b>';
		$success .= '</div>';
		echo $success;
	}
	if($session->has_flash('error')){
		$error = '<div class="alert alert-danger alert-dismissible" role="alert">';
		$error .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$error .= '<b>'.$session->flash('error').'</b>';
		$error .= '</div>';
		echo $error;
	}
}

function dump($data=null){
	echo "<pre>";
	var_dump($data);
	echo "</pre>";
}

function get_token($len = 6){
	$str = 'ab1cd2efg3hi4jkl5mn6opq7rs8tuv9wx0yz';
	$str = str_shuffle($str);
    $arr = str_split($str);
    $result = '';
    for ($i=0; $i < $len; $i++) { 
        $result .= $arr[mt_rand(0,35)];
    }
    return $result;
}

if (!function_exists('bulan')) {
    function bulan($bulan){
        switch ($bulan) {
            case 1:
                $bulan = " Januari ";
                break;
            case 2:
                $bulan = " Februari ";
                break;
            case 3:
                $bulan = " Maret ";
                break;
            case 4:
                $bulan = " April ";
                break;
            case 5:
                $bulan = " Mei ";
                break;
            case 6:
                $bulan = " Juni ";
                break;
            case 7:
                $bulan = " Juli ";
                break;
            case 8:
                $bulan = " Agustus ";
                break;
            case 9:
                $bulan = " September ";
                break;
            case 10:
                $bulan = " Oktober ";
                break;
            case 11:
                $bulan = " November ";
                break;
            case 12:
                $bulan = " Desember ";
                break;

            default:
                $bulan = Date('F');
                break;
        }
        return $bulan;
    }
}

function fix_date($date=null, $dt=false) {
    if($date!=null){
        $date = date_create($date);
        return date_format($date,"d").bulan(date_format($date,"m")).date_format($date,"Y");
    }
    else{
        return 'Tidak Ada';
    }
}

function date_now(){
    $date = date("Y-m-d");
    return $date;
}



/*
 * HARD SCRIPT
 */

function get_code($len=6){
	$str = 'AB1CD2EFG3HI4JKL5MN6OPQ7RS8TUV9WX0YZ';
	$str = str_shuffle($str);
    $arr = str_split($str);
    $result = '';
    for ($i=0; $i < $len; $i++) { 
        $result .= $arr[mt_rand(0,35)];
    }
    return $result;
}


function flip_number($str){
	$data = str_split('QPWOEIRUTY');
	if(empty(intval($str))){
		$data = array_flip($data);
	}
	$str = str_split($str);
	$result = '';
	foreach ($str as $key) {
		$result .= @$data[$key];
	}
	return $result;
}

function rand_num($len=4){
	$exp = pow(10, $len);
	$min = $exp*0.1;
	$max = $exp-1;
    return mt_rand($min,$max);
}

function enc($str=null){
	if(!empty(intval($str))){
		$len = mt_rand(1,5);

		$key = rand_num($len);
		$lock = flip_number($key);

		$str = flip_number($str);
		$str = $str.$lock;

		$str = strrev(get_code(6).$str);
		$str = str_rot13($str);
		$str = base64_encode($str);
		$str = flip_number($len).$str;
		return $str;
	}
}

function dec($str){
	if($str = strval($str)){
		$str = urldecode($str);

		$len = substr($str, 0, 1);
		$str = substr($str, 1);
		$len = flip_number($len);

		$str = base64_decode($str);
		$str = str_rot13($str);
		$str = strrev($str);
		$str = substr($str,6);

		$lock = substr($str, strlen($str)-$len);
		$key = flip_number($lock);
		$str = substr($str, 0, strlen($str)-$len);

		$str = flip_number($str);
		if(!empty(intval($str))){
			return $str;
		}
	}
}