<?php  
// Check if required form fields are submitted
if (isset($_POST['username'], $_POST['password'], $_FILES['valid_id'], $_POST['name'])) {

   // Include the database connection file
   include '../db.conn.php';

   // Get data from POST request
   $name = $_POST['name'];
   $password = $_POST['password'];
   $username = $_POST['username'];
   $valid_id_file = $_FILES['valid_id'];

   // URL encode data for redirection
   $data = 'name=' . urlencode($name) . '&username=' . urlencode($username);

   // Form validation
   if (empty($name)) {
      $em = "Name is required";
      header("Location: ../../signup.php?error=$em");
      exit;
   } elseif (empty($username)) {
      $em = "Username is required";
      header("Location: ../../signup.php?error=$em&$data");
      exit;
   } elseif (empty($password)) {
      $em = "Password is required";
      header("Location: ../../signup.php?error=$em&$data");
      exit;
   } elseif ($valid_id_file['error'] !== UPLOAD_ERR_OK) {
      $em = "Valid ID is required";
      header("Location: ../../signup.php?error=$em&$data");
      exit;
   } else {
      // Check if the username is already taken
      $sql = "SELECT username FROM users WHERE username = ?";
      $stmt = $conn->prepare($sql);
      $stmt->execute([$username]);

      if ($stmt->rowCount() > 0) {
         $em = "The username ($username) is taken";
         header("Location: ../../signup.php?error=$em&$data");
         exit;
      } else {
         // Handle valid ID file upload
         $valid_id_name = $valid_id_file['name'];
         $valid_id_tmp_name = $valid_id_file['tmp_name'];
         $valid_id_error = $valid_id_file['error'];

         if ($valid_id_error === 0) {
            $valid_id_ex = pathinfo($valid_id_name, PATHINFO_EXTENSION);
            $valid_id_ex_lc = strtolower($valid_id_ex);
            $allowed_exs = ["jpg", "jpeg", "png"];

            if (in_array($valid_id_ex_lc, $allowed_exs)) {
               $new_valid_id_name = $username . '_valid_id.' . $valid_id_ex_lc;
               $valid_id_upload_path = '../../uploads/valid_ids/' . $new_valid_id_name;
               if (move_uploaded_file($valid_id_tmp_name, $valid_id_upload_path)) {
                  echo "File uploaded successfully to: $valid_id_upload_path";
               } else {
                  echo "Failed to move uploaded file.";
               }
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
         $hashed_password = password_hash($password, PASSWORD_DEFAULT);

         // Insert data into the database
         $sql = isset($new_img_name) ?
            "INSERT INTO users (name, username, password, p_p, valid_id) VALUES (?, ?, ?, ?, ?)" :
            "INSERT INTO users (name, username, password, valid_id) VALUES (?, ?, ?, ?)";
         
         $stmt = $conn->prepare($sql);
         $params = isset($new_img_name) ?
            [$name, $username, $hashed_password, $new_img_name, $new_valid_id_name] :
            [$name, $username, $hashed_password, $new_valid_id_name];
         
         $stmt->execute($params);

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
