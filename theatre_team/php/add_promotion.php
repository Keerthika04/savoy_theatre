<?php
session_start();
require "../../php/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bannerPoster = $_FILES['banner_poster'];
    $movieId = $_POST['movie_id'];


    // Handle the file upload
    $banner_poster = $_FILES['banner_poster']['name'];
    $banner_poster_target = "../../uploaded_banner_images/" . basename($banner_poster);

    if (move_uploaded_file($_FILES['banner_poster']['tmp_name'], $banner_poster_target)) {
        // Generating Id for banners
        $query = $db->query("SELECT banner_id FROM banner ORDER BY banner_id DESC LIMIT 1");
        $newID = "b001";

        if ($query->num_rows > 0) {
            $row = $query->fetch_assoc();
            $last_id = $row['banner_id'];
            $num = (int) substr($last_id, 1) + 1;
            $newID = "b" . str_pad($num, 3, "0", STR_PAD_LEFT);
        }

        // File upload successful, insert data into the database
        $sql = "INSERT INTO banner (banner_id, banner_poster, movie_id) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sss", $newID, $banner_poster, $movieId);

        if ($stmt->execute()) {
            $_SESSION['alert_message'] = "New promotion added successfully";
                header("Location: ../promotion.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    $stmt->close();
}
?>
