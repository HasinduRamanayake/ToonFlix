<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;

class Doctrine {

    public $em = null;

    public function __construct()
    {

        // Determine if we are running in development mode
        $this->CI =& get_instance();
       

        $isDevMode = (ENVIRONMENT !== 'production');
        $cache = DoctrineProvider::wrap(new PhpFilesAdapter('doctrine_cache'));
        
        
        // Set up the configuration for Doctrine
        $config = Setup::createAnnotationMetadataConfiguration(
            [APPPATH . 'models/Entity'], // Path to the entity files
            $isDevMode,
            null,
            null,
            false
        );

        $ci_db = $this->CI->db->conn_id;
        
        // Set up the connection options for Doctrine to use CodeIgniter's settings
        $connectionOptions = array(
            'driver'   => 'pdo_mysql',
            'user'     => $this->CI->db->username,
            'password' => $this->CI->db->password,
            'host'     => $this->CI->db->hostname,
            'dbname'   => $this->CI->db->database,
            'charset'  => $this->CI->db->char_set,
            'driverOptions' => [
                1002 => 'SET NAMES ' . $this->CI->db->char_set,
            ],
        );
        
        // Create the EntityManager
        $this->em = EntityManager::create($connectionOptions, $config);
    }
}

?>