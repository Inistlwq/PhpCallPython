<?php
	public function my_method($sentence){
		……
		exec("绝对路径/python2.7  绝对路径/Mypython.py ". escapeshellarg(json_encode($sentence)). " 2>&1",$resultData,$ret);
		$res=end($resultData);//get the result	
		……
		return $ret_arr;
	}
}
