<?php

function ImageUpload($input_file_name,$file_name,$path)
{
    $ci=&get_instance();

   	$ci->load->library('upload'); 
    
    $config['file_name'] =$file_name; 
    $config['upload_path'] =$path;
    $config['allowed_types'] = 'gif|jpg|png|jpeg';
    $config['min_width'] = '128';
    $config['max_width'] = '512';
    $config['min_height'] = '128';
    $config['max_height'] = '512';
    $config['overwrite'] = false;
    $config['remove_spaces'] = true;
    $config['file_ext_tolower'] = true;
    $ci->upload->initialize($config); 
    if ($ci->upload->do_upload($input_file_name))
    {
        return ['status'=>'1' ,'msg' => 'File Uploaded'];
    }
    else {
        return ['status'=>'0' ,'msg' => $ci->upload->display_errors()];
    }
}