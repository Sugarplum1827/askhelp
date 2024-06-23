<?php  
// Check if username, password, valid_id, and name are submitted
if(isset($_POST['username']) && isset($_POST['password']) && isset($_FILES['valid_id']) && isset($_POST['name'])){

   // Include the database connection file
   include '../db.conn.php';
   
   // Get data from POST request and store them in variables
   $name = $_POST['name'];
   $password = $_POST['password'];
   $username = $_POST['username'];
   $valid_id_file = $_FILES['valid_id'];

   // Making URL data format for passing data back in case of an error
   $data = 'name='.urlencode($name).'&username='.urlencode($username);

   // Simple form validation
   if (empty($name)) {
      $em = "Name is required";
      header("Location: ../../signup.php?error=$em");
      exit;
   } else if (empty($username)){
      $em = "Username is required";
      header("Location: ../../signup.php?error=$em&$data");
      exit;
   } else if (empty($password)){
      $em = "Password is required";
      header("Location: ../../signup.php?error=$em&$data");
      exit;
   } else if ($valid_id_file['error'] !== UPLOAD_ERR_OK){
      $em = "Valid ID is required";
      header("Location: ../../signup.php?error=$em&$data");
      exit;
   } else {
      // Check if the username is already taken
      $sql = "SELECT username FROM users WHERE username = ?";
      $stmt = $conn->prepare($sql);
      $stmt->execute([$username]);

      if($stmt->rowCount() > 0){
         $em = "The username ($username) is taken";
         header("Location: ../../signup.php?error=$em&$data");
         exit;
      } else {
         // Handle valid_id file upload
         $valid_id_name = $valid_id_file['name'];
         $valid_id_tmp_name = $valid_id_file['tmp_name'];
         $valid_id_error = $valid_id_file['error'];

         if ($valid_id_error === 0) {
            $valid_id_ex = pathinfo($valid_id_name, PATHINFO_EXTENSION);
            $valid_id_ex_lc = strtolower($valid_id_ex);
            $allowed_exs = array("jpg", "jpeg", "png");

            if (in_array($valid_id_ex_lc, $allowed_exs)) {
               $new_valid_id_name = $username . '_valid_id.' . $valid_id_ex_lc;
               $valid_id_upload_path = '../../uploads/valid_ids/' . $new_valid_id_name;
               move_uploaded_file($valid_id_tmp_name, $valid_id_upload_path);
            } else {
               $em = "You can't upload files of this type for valid ID";
               header("Location: ../../signup.php?error=$em&$data");
               exit;
            }
         } else {
            $em = "Error uploading valid ID";
            header("Location: ../../signup.php?error=$em&$data");
            exit;
         }

         // Handle profile picture upload if provided
         if (isset($_FILES['pp'])) {
            $img_name = $_FILES['pp']['name'];
            $tmp_name = $_FILES['pp']['tmp_name'];
            $error = $_FILES['pp']['error'];

            if ($error === 0) {
               $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
               $img_ex_lc = strtolower($img_ex);
               $allowed_exs = array("jpg", "jpeg", "png");

               if (in_array($img_ex_lc, $allowed_exs)) {
                  $new_img_name = $username . '.' . $img_ex_lc;
                  $img_upload_path = '../../uploads/' . $new_img_name;
                  move_uploaded_file($tmp_name, $img_upload_path);
               } else {
                  $em = "You can't upload files of this type for profile picture";
                  header("Location: ../../signup.php?error=$em&$data");
                  exit;
               }
            }
         }

         // Hash the password
         $password = password_hash($password, PASSWORD_DEFAULT);

         // Insert data into the database
         if (isset($new_img_name)) {
            $sql = "INSERT INTO users (name, username, password, p_p, valid_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$name, $username, $password, $new_img_name, $new_valid_id_name]);
         } else {
            $sql = "INSERT INTO users (name, username, password, valid_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$name, $username, $password, $new_valid_id_name]);
         }

         // Success message
         $sm = "Account created successfully";
         header("Location: ../../verification.php?success=$sm");
         exit;
      }
   }
} else {
   header("Location: ../../signup.php");
   exit;
}
?>
