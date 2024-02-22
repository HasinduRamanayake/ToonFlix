<?php

class User_Model extends CI_Model {

    public function login($username, $password) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');

        if ($query->num_rows() > 0) {
            $user = $query->row_array();

            // Verify the password (assume using password_hash for storing passwords)
            if (password_verify($password, $user['password'])) {
                return $user; // Authentication successful
            }
        }

        return false; // Authentication failed
    }
}

?>