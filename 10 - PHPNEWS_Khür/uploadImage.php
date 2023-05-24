<?php
//
//require_once 'Model/Database.php';
//require_once 'Model/ArticleRepository.php';
//
//$db = new Database();
//$articleRepository = new ArticleRepository($db);
//
//$statusMsg = '';
//$targetDir = "uploads/";
//
//if (isset($_POST['submit'])){
//    if (!empty($_FILES['image']['name'])){
//        $fileName = basename($_FILES['image']['name']);
//        $targetFilePath = $targetDir . $fileName;
//        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
//
//        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
//        if (in_array($fileType, $allowTypes)){
//            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)){
//                $insert = $articleRepository->uploadImage($fileName);
//                if ($insert){
//                    $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
//                } else {
//                    $statusMsg = "File upload failed, please try again.";
//                }
//            } else {
//                $statusMsg = "Sorry, there was an error uploading your file.";
//            }
//        } else {
//            $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF & PNG files are allowed to upload.';
//        }
//    } else {
//        $statusMsg = 'Please select a file to upload.';
//    }
//}