<?php


class UserController extends CI_Controller {
    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            // If the user is not logged in, redirect to the login page
            redirect('login');
        } else {
            // Load the dashboard view
            $this->load->view('dashboard');
        }
    }
}
?>