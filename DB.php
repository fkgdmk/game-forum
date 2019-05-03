<?php
    class DB{
        
        public $connection;
        
        public function connectToDb()
        {
            $servername = "localhost";
            $username = "root";
            $password = "password";
            $database = "game_forum";

            $this->connection = new mysqli($servername, $username, $password, $database);

            if ($this->connection->connect_error) {
                die("Connection failed: " . $this->connection->connect_error);
            }
            echo '<script>console.log("connected succesfully")</script>';
            return $this->connection;
        }

        public function closeServer()
        {
            $this->connection->close();
        }
    }
?>