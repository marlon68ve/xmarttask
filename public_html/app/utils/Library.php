<?php

class Library {
    public function hasAccess($userType, $roles) {
        return in_array($userType, $roles);
    }    
    
    public static function uploadImage($f3, $file) {
        die('aqui');
        $uploadDir = $f3->get('UPLOADS');
        $targetFile = $uploadDir . basename($file['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the file is an image
        $check = getimagesize($file['tmp_name']);
        if ($check !== false) {
            $f3->set('uploadStatus', "File is an image - " . $check['mime']);
            $uploadOk = 1;
        } else {
            $f3->set('uploadStatus', "File is not an image.");
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($targetFile)) {
            $f3->set('uploadStatus', "Sorry, file already exists.");
            $uploadOk = 0;
        }

        // Check file size (limit: 500KB)
        if ($file['size'] > 500000) {
            $f3->set('uploadStatus', "Sorry, your file is too large.");
            $uploadOk = 0;
        }

        // Allow certain file formats
        $allowedFormats = ['jpg', 'png', 'jpeg', 'gif'];
        if (!in_array($imageFileType, $allowedFormats)) {
            $f3->set('uploadStatus', "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
            $uploadOk = 0;
        }

        // Attempt to upload the file
        if ($uploadOk === 1) {
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                $f3->set('uploadStatus', "The file " . htmlspecialchars(basename($file['name'])) . " has been uploaded.");
                return ['status' => true, 'path' => $targetFile];
            } else {
                $f3->set('uploadStatus', "Sorry, there was an error uploading your file.");
                return ['status' => false, 'path' => null];
            }
        }

        return ['status' => false, 'path' => null];
    }
    
    public static function load(){
        die('load');
    }

}