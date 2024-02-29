<?php

class User_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function login($username, $password) {
        // This is a basic example. You should use password hashing and more secure practices.

        
        $this->db->where('username', $username);
        $this->db->where('password', $password); // Consider using password_verify for hashed passwords
        $query = $this->db->get('users');

        if ($query->num_rows() > 0) {
            return $query->row_array(); // Return the user data
        } else {
            return false;
        }
    }

        // In application/models/User_model.php

    public function exists($username, $email) {
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->or_where('email', $email);
        return $this->db->count_all_results() > 0;
    }

    public function signup($username, $password, $email) {
        $data = [
            'username' => $username,
            'password' => $password, 
            'email' => $email
        ];
        $this->db->insert('users', $data);
        return $this->db->insert_id(); // Return the ID of the new user
    }

}

?>