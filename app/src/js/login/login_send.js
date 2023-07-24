const loginEndPoint = "/src/php/auth/login_auth.php";
const reg1EndPoint = "/src/php/register/reg1Form.php";
const reg2EndPoint = "/src/php/register/reg2Form.php";


function resetButton(button, text) {
    button.innerHTML = '';
    button.textContent = text;
}

function displayError(element, message) {
    element.textContent = message;
    console.error(message);
}


// Login submit
document.getElementById('loginForm').addEventListener('submit', function(event) {

    const submitBtn = document.getElementById('login-submit');
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

    const loginErrText = document.getElementById('login-error');
    event.preventDefault(); // Prevent default form submission

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    const base64PasswordPass1 = btoa(password);
    const base64PasswordPass2 = btoa(base64PasswordPass1);

    const formData = new FormData();
    formData.append('username', username);
    formData.append('password', base64PasswordPass2);

    fetch(loginEndPoint, {
          method: 'POST',
          body: formData

    }) .then(response => {

          if (response.status === 201) {
                resetButton(submitBtn, 'Login');
                displayError(loginErrText, '');
                window.location.href = "/home";

          } else if (response.status === 403) {
                resetButton(submitBtn, 'Login');
                displayError(loginErrText, 'Incorect Username or Password');

          } else {
                resetButton(submitBtn, 'Login');
                displayError(loginErrText, 'Login Failed');
          }

    }) .catch(error => {
            resetButton(submitBtn, 'Login');
            displayError(loginErrText, 'Registration failed');
            console.error('Error:', error);
            return;
    });
});



// Register page 1 submit
document.getElementById('reg1Form').addEventListener('submit', function(event) {
      const regErrText = document.getElementById('reg1-error');
      regErrText.textContent = "";
      const submitBtn = document.getElementById('reg1Form-submit');
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

      event.preventDefault();

      const firstname = document.getElementById('firstname').value;
      const lastname = document.getElementById('lastname').value;
      const fullname = firstname + '|' + lastname;
      const email = document.getElementById('email').value;
      const username = document.getElementById('reg-username').value;
      const password = document.getElementById('reg-password').value;
      const regverpassword = document.getElementById('reg-verpassword').value;
  
      // Check username for invalid characters
      const usernameRegEx = /^[a-zA-Z0-9]+$/;
      if (!usernameRegEx.test(username)) {
          resetButton(submitBtn, 'Continue');
          displayError(regErrText, 'Username should not contain spaces or special characters.');
          return;
      }
  
      // Check email format
      const emailRegEx = /^[^\s@]+@[^\s@]+\.[^\s@]{2,7}$/;
      if (!emailRegEx.test(email)) {
          resetButton(submitBtn, 'Continue')
          displayError(regErrText, 'Invalid Email Format')
          return;
      }
  
      // Check if passwords match and validate password
      const passwordRegEx = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,32}$/;
      if (password !== regverpassword) {
          resetButton(submitBtn, 'Continue');
          displayError(regErrText, 'Passwords do not match');
          return;
      } else if (!passwordRegEx.test(password)) {
          resetButton(submitBtn, 'Continue');
          displayError(regErrText, 'Password must contain at least one uppercase letter, lowercase letter, number, special character and be between 8-32 characters long.');
          return;
      }

      const base64PasswordPass1 = btoa(password);
      const base64PasswordPass2 = btoa(base64PasswordPass1);
      
      const formData = new FormData();
        formData.append('fullname', fullname);
        formData.append('email', email);
        formData.append('username', username);
        formData.append('password', base64PasswordPass2);

      fetch(reg1EndPoint, {
          method: 'POST',
          body: formData
      }) .then(response => {

            if (response.status === 406) {
                    var msg = response.statusText;
                    resetButton(submitBtn, 'Continue');

                if (msg === "Username Taken") {
                        displayError(regErrText, 'Username is unavailable');
                        return;

                } else if (msg === "Email Taken") {
                        displayError(regErrText, 'Email is unavailable');
                        return;
                }
            
            } else if (response.status === 200) {
                  resetButton(submitBtn, 'Continue');
                  displayError(regErrText, 'Server parse error, try again later');
            
            } else if (response.status === 201) {
                  resetButton(submitBtn, 'Continue');
                  document.getElementById("reg1-CloseBtn").click();
                  document.getElementById("reg2-OpenBtn").click();
            
            
            } else { // Other http codes not known
                  resetButton(submitBtn, 'Continue');
                  displayError(regErrText, 'Registration failed.');
                  return;
            }

      }) .catch(error => {
              resetButton(submitBtn, 'Continue');
              displayError(regErrText, 'Registration failed.');
              console.error('Error:', error);
              return;
      });
});


// Register page 2 submit
document.getElementById('reg2Form').addEventListener('submit', function(event) {
      const regErrText = document.getElementById('reg2-error');
      event.preventDefault();
      console.log("Attempting to submit");


      const submitBtn = document.getElementById('reg2Form-submit');
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
      submitBtn.textContent = '';

      const emailOTP = document.getElementById('emailOTP').value;
      const username = document.getElementById('reg-username').value;
      const email = document.getElementById('email').value;

      const formData = new FormData();
        formData.append('username', username);
        formData.append('email', email);
        formData.append('emailOTP', emailOTP);

    fetch(reg2EndPoint, {
        method: 'POST',
        body: formData

    }) .then(response => {

          if (response.status === 406) {
                resetButton(submitBtn, 'Continue');
                displayError(regErrText, 'Verification code is invalid');
                return;

          } else if (response.status === 500) {
                resetButton(submitBtn, 'Continue');
                displayError(regErrText, 'Internal Server Error');
                return;

          } else if (response.status === 200) {
                resetButton(submitBtn, 'Continue');
                displayError(regErrText, 'Server parse error, try again later');
                return;
            
          } else if (response.status === 201) {
                resetButton(submitBtn, 'Continue');
                displayError(regErrText, '');

                document.getElementById("reg2-CloseBtn").click();
                setTimeout(function() {    // This is to add some smooth animation and allow time for the modal to appear closing
                    window.location.href = "/home";
                }, 1000);
                return;

          } else {
                resetButton(submitBtn, 'Continue');
                displayError(regErrText, 'Registration failed');
                return;
          }

    }) .catch(error => {
          resetButton(submitBtn, 'Continue');
          displayError(regErrText, 'Registration failed');
          console.error('Error:', error);
          return;
  });
});
