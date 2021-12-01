<!doctype html>
<!-- If multi-language site, reconsider usage of html lang declaration here. -->
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Form Validation</title>
  <!-- View-port Basics: http://mzl.la/VYREaP -->
  <!-- This meta tag is used for mobile device to display the page without any zooming,
       so how much the device is able to fit on the screen is what's shown initially. 
       Remove comments from this tag, when you want to apply media queries to the website. -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!-- Place favicon.ico in the root directory: mathiasbynens.be/notes/touch-icons -->
  <link rel="shortcut icon" href="favicon.ico" />
  <!--font-awesome link for icons-->
  <link rel="stylesheet" media="screen" href="assets/vendor/font-awesome/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Default style-sheet is for 'media' type screen (color computer display).  -->
  <link rel="stylesheet" media="screen" href="assets/css/style.css">
</head>
<?php
  $nameErr = $phoneErr = $mailErr = $passErr = $confPassErr = $fileErr = "";
  $name = $phone = $mail = $pass = $confPass = $file = "";
  $genM = "checked";
  $genF = "unchecked";
  $flag = false;
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if($_POST['gender']== "Female"){
      $genM = "unchecked";
      $genF = "checked";
    } else {
      $genM = "checked";
      $genF = "unchecked";
    }
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $mail = $_POST['mail'];
    $pass = $_POST['password'];
    $confPass = $_POST['conform-password'];
    if (isset($_POST['submit'])) {
      if(validate()) {
        $flag = true;
      }
    }
    if (isset($_POST['clear'])) {
      $nameErr = $phoneErr = $mailErr = $passErr = $confPassErr = $fileErr = "";
      $name = $phone = $mail = $pass = $confPass = $file = "";
      $genM = "checked";
      $genF = "unchecked";
    }
  }

  function validate(){
    $flag = 0;
    nameVal() || $flag++ ;
    phoneVal() || $flag++ ;
    mailVal() || $flag++ ;
    passVal() || $flag++ ;
    confPassVal() || $flag++ ;
    fileVal() || $flag++ ;
    ($flag == 0) && move_uploaded_file($_FILES['file']['tmp_name'] ,"uplodede/".$_FILES['file']['name']);
    return $flag == 0;
  }

  function nameVal() { 
    if (trim($_POST['name']) != "") {
      if (preg_match("/^[A-Za-z ]+$/",$_POST['name'])) {
          return true;
      } else {
        $GLOBALS['nameErr'] = "This name is invalid";
        return false;
      }
    } else {
      if ($_POST['name'] == "") {
        $GLOBALS['nameErr'] = "The name is required";
        return false;
      } else {
        $GLOBALS['nameErr'] = "The name can't be blank space";
        return false;
      }
    } 
  }

  function phoneVal() { 
    if (trim($_POST['phone']) != "") {
      if (preg_match("/^((?:(?:\+|0{0,2})91(\s*[\ -]\s*)?|[0]?)?[6789]\d{9}|(\d[ -]?){10}\d)$/",$_POST['phone'])) {
          return true;
      } else {
        $GLOBALS['phoneErr'] = "This phone number is invalid";
        return false;
      }
    } else {
      if ($_POST['phone'] == "") {
        $GLOBALS['phoneErr'] = "The phone number is required";
        return false;
      } else {
        $GLOBALS['phoneErr'] = "The phone number can't be blank space";
        return false;
      }
    }  
  }

  function mailVal() { 
    if (trim($_POST['mail']) != "") {
      if (filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
          return true;
      } else {
        $GLOBALS['mailErr'] = "This email is invalid";
        return false;
      }
    } else {
      if ($_POST['mail'] == "") {
        $GLOBALS['mailErr'] = "The email is required";
        return false;
      } else {
        $GLOBALS['mailErr'] = "The email can't be blank space";
        return false;
      }
    } 
  }

  function passVal() { 
    $pass = $_POST['password'];
    if (trim($pass) != "") {
      $flag = 0;
      if (strlen($pass) < 8) {
        $GLOBALS['passErr'] = "The Password must be at least 8 characters in length.";
        $flag++;
        return false;
      }
      if (!preg_match('@[a-z]@', $pass)) {
        $GLOBALS['passErr'] = "The Password must contain at least one lower case letter.";
        $flag++;
        return false;
      }
      if (!preg_match('@[A-Z]@', $pass)) {
        $GLOBALS['passErr'] = "The Password must contain at least one upper case letter.";
        $flag++;
        return false;
      }
      if (!preg_match('@[0-9]@', $pass)) {
        $GLOBALS['passErr'] = "The Password must contain at least one number";
        $flag++;
        return false;
      }
      if (!preg_match('@[^\w]@', $pass)) {
        $GLOBALS['passErr'] = "The Password must contain at least one special character.";
        $flag++;
        return false;
      }
      return ($flag == 0);
    } else {
      if ($_POST['mail'] == "") {
        $GLOBALS['passErr'] = "The password is required";
        return false;
      } else {
        $GLOBALS['passErr'] = "The password can't be blank space";
        return false;
      }
    }
  }

  function confPassVal() { 
    if($_POST['conform-password'] == $_POST['password']){
      return true;
    } else {
      $GLOBALS['confPassErr'] = "The Password dose not matched.";
      return false;
    } 
  }

  function fileVal() {
    if(isset($_FILES['file'])) {
      $temp = explode(".",$_FILES['file']['name']);
      if(strcasecmp($temp[count($temp)-1], "pdf") != 0) {
        $GLOBALS['fileErr'] = "Please Select .pdf file only for Resume.";
        return false;
      } else {
        return true;
      }
    }
  }
?>
<body>
  <!--container start-->
  <div class="container">
    <!--header section start-->
    <header>
      <p>Form Validation with PHP</p>
    </header>
    <!--header section start-->
    <!--main section start-->
    <main>
      <div class="wrapper">
        <?php 
          if($flag) {
            echo '<div class="pop-up">'.
              "<p class='pop-up-msg hide-me'>Hey $name, now you are regiserd. </p>".
            '</div>';
            ?>
            <ul class="cards">
              <li class="card">
                <p class="display-name"><?php echo $name ?></p>
                <p class="display">Gender : <span class="display-gender"><?php echo $_POST['gender'] ?></span></p>
                <p class="display">Phone Number : <span class="display-number"><?php echo $phone ?></span></p>
                <p class="display">Email : <span class="display-email"><?php echo $mail ?></span></p>
                <p class="display">Password : <span class="display-pass"><?php echo $pass ?></span></p>
              </li>
            </ul>
            <?php
          } else {
            require 'form.php';
          }
        ?>
      </div>
    </main>
    <!--main section end-->
    <!--footer section start-->
    <footer>
      <p>Created By NDG.</p>
    </footer>
    <!--footer section end-->
  </div>
  <!--container end-->
  <script src="/sassets/js/script.js"></script>
</body>
</html>