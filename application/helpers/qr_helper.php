<?php

function generate_qr($contents,$upload_dir) {

	$ci=&get_instance();

   	$ci->load->library('Ciqrcode');

   	$params['data'] = $contents;
   	$params['savename'] = $upload_dir;
   	$params['size'] = 5;

   	$code = $ci->ciqrcode->generate($params);
   	
   	return $code;
}

?>