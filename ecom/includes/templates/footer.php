<footer class="text-center text-lg-start bg-light text-muted">
  <!-- Copyright -->
  <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
    © 2021 Copyright:
    <strong>UoB</strong>
  </div>
  <!-- Copyright -->
</footer>
<!-- Footer -->
<script>
$(function() {
    $("#name_error_message").hide();
    $("#email_error_message").hide();
    $("#password_error_message").hide();
    $("#retype_password_error_message").hide();

    var error_fname = false;
    var error_email = false;
    var error_password = false;
    var error_retype_password = false;

    $("#form_fname").focusout(function(){
       check_fname();
    });

    $("#form_email").focusout(function() {
       check_email();
    });
    $("#form_password").focusout(function() {
       check_password();
    });
    $("#form_retype_password").focusout(function() {
       check_retype_password();
    });

    function check_fname() {
       var pattern = /^[a-zA-Z]*$/;
       var fname = $("#form_fname").val();
       if (pattern.test(fname) && fname !== '') {
          $("#name_error_message").hide();
          $("#form_fname").css("border-bottom","2px solid #34F458");
          $('#register').attr("disabled", false);

       } 

       else {
          $("#name_error_message").html("Should contain only Characters");
          $("#name_error_message").show();
          $("#form_fname").css("border-bottom","2px solid #F90A0A");
          $('#register').attr("disabled", true);
          error_fname = true;
       }
    }
    
    function check_password() {
       var password_length = $("#form_password").val().length;
       if (password_length < 8) {
          $("#password_error_message").html("Atleast 8 Characters");
          $("#password_error_message").show();
          $("#form_password").css("border-bottom","2px solid #F90A0A");
          error_password = true;
       } else {
          $("#password_error_message").hide();
          $("#form_password").css("border-bottom","2px solid #34F458");
          $('#register').attr("disabled", false);

       }
    }

    function check_retype_password() {
       var password = $("#form_password").val();
       var retype_password = $("#form_retype_password").val();
       if (password !== retype_password) {
          $("#retype_password_error_message").html("Passwords do not match");
          $("#retype_password_error_message").show();
          $("#form_retype_password").css("border-bottom","2px solid #F90A0A");
          error_retype_password = true;
          $('#register').attr("disabled", true);

       } else {
          $("#retype_password_error_message").hide();
          $("#form_retype_password").css("border-bottom","2px solid #34F458");
          $('#register').attr("disabled", false);

       }
    }

    function check_email() {
       var pattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
       var email = $("#form_email").val();
       if (pattern.test(email) && email !== '') {
          $("#email_error_message").hide();
          $("#form_email").css("border-bottom","2px solid #34F458");
          $('#register').attr("disabled", false);

       } else {
          $("#email_error_message").html("Invalid Email");
          $("#email_error_message").show();
          $("#form_email").css("border-bottom","2px solid #F90A0A");
          error_email = true;
          $('#register').attr("disabled", true);

       }
    }
    

    $("#registration_form").submit(function() {
       error_fname = false;
       error_email = false;
       error_password = false;
       error_retype_password = false;

       check_fname();
       check_email();
       check_password();
       check_retype_password();

       if (error_fname === false && error_email === false && error_password === false && error_retype_password === false) {
          return true;
       }
    });
 });
 
</script>
<script src="<?php echo $js ?>jquery-1.12.1.min.js"></script>
<script src="<?php echo $js ?>jquery-ui.min.js"></script>
<script src="<?php echo $js ?>bootstrap.min.js"></script>
<script src="<?php echo $js ?>front.js"></script>
<script src="<?php echo $js ?>validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js" integrity="sha384-lpyLfhYuitXl2zRZ5Bn2fqnhNAKOAaM/0Kr9laMspuaMiZfGmfwRNFh8HlMy49eQ" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/8654e7327c.js" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/cesiumjs/1.78/Build/Cesium/Cesium.js"></script>
</body>
</html>
