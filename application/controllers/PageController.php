<?php
defined ('BASEPATH') OR exit('No direct script access allowed');

class PageController extends CI_Controller{
    public function index(){
        echo "I am index Method - Page Controller";
    }

    public function aboutus(){
        echo "I am about page";
    }

    public function blog($blog_url = ''){
        echo "$blog_url";
        $this->load->view('blogview');
    }
}