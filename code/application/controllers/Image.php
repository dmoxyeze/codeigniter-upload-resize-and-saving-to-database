<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image extends CI_Controller {

		public function __construct(){
		parent::__construct(); 
		//loads the form helper which we will use in our views
		//note: if this is not loaded, the code igniter form tag will throw an error
		$this->load->helper(array('form', 'url'));
		}

	public function index($error=null)
	{	
		//this loads the view('image');
		//we set page data error to dispalay our success or failure message after upload
		$pagedata['error'] = $error;
		$this->load->helper(array('form', 'url'));
		$this->load->view('image',$pagedata);
	}
	public function do_upload(){

		//You can chose to set the configuration variables in your config.php ../config/config.php
		//upload folder must be created in your root directory and must not be an address,must be a file path
		$config['upload_path']          = './uploads/';//this is the folder where we will place the  uploaded files
        $config['allowed_types']        = 'gif|jpg|png|jpeg';//array of allowed file formats
         $config['max_filename'] = '255';
        //whether file name should be encrypted or not
        $config['encrypt_name'] = TRUE;
        //store image info once uploaded
        $config['overwrite'] = TRUE;
		$config['remove_spaces'] = true;
        
        $config['max_size']             = 1024;//sets max size
        $config['max_width']            = 1024;//max width
        $config['max_height']           = 1000;//max height
		
		$this->load->library('upload',$config);//this loads the image upload library which actually does the upload
		 if ( ! $this->upload->do_upload('userfile'))//if the upload fails
                {
                        $error = array('error' => $this->upload->display_errors());// this returns an error message
                        $this->load->helper(array('form', 'url'));
                        $this->load->view('image', $error);//takes us back to the views page
                }
                else
                {		

                		$config['image_library'] = 'gd2';//this loads the library for image resize where upload is successful
						$config['source_image'] = $this->upload->upload_path.$this->upload->file_name;//path to the image we want to resize which is the image we just uploaded
						$config['create_thumb'] = TRUE;
						//$config['thumb_marker'] = false;
						$config['maintain_ratio'] = FALSE;
						$config['width'] = 318;//the width to resize to;
						$config['height'] = 108;//height to resize to;

		                $this->load->library('image_lib', $config);//this loads the image resize library
		                $this->image_lib->resize();//the resize function
		               //check if the resize succeeds
		                if ( ! $this->image_lib->resize()){
		                	
			            $data = array('error'=> $this->image_lib->display_errors());
			            $this->load->view('image', $data);
				}		else{
						$image = $this->upload->file_name;// assign a variable to the image we just resized
						
						$image = str_ireplace('.', '_thumb.',$image);//this is to get the name of the newly created thumbnail as that is what we will be uploading to database;
						//your database query
						$query = $this->db->query("insert into table_name(image) values('$image')");
						//note: you can choose to do the insert query OOP style

						unlink($this->upload->upload_path.$this->upload->file_name);//this deletes the original image from which the thumbail was created;
                        $data = array('upload_data' => $this->upload->data(),'error'=>'file uploaded','image'=>$image);
                        
                        $this->load->view('image', $data);//return to the views page;
                    		}
                }
		return 1;
		
	}
}
