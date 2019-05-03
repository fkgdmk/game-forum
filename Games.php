<?php
    class games{
        var $title;
        var $description;
        var $genre;
        var $releaseYear;

        public function __construct($title, $description, $genre, $releaseYear)
        {
            $this->title = $title;
            $this->description = $description;
            $this->genre = $genre;
            $this->releaseYear = $releaseYear;
        }

        //getters
        public function getTitle(){
            return $this->title;
        }
        public function getDescription(){
            return $this->description;
        }
        public function getGenre(){
            return $this->genre;
        }
        public function getReleaseYear(){
            return $this->releaseYear;
        }

        //setters
        public function setTitle($title){
            $this->title = $title;
        }
        public function setDescription($description){
            $this->description = $description;
        }
        public function setGenre($genre){
            $this->genre = $genre;
        }
        public function setReleaseYear($releaseYear){
            $this->releaseYear = $releaseYear;
        }
    }
?>