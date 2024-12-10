// Example starter JavaScript for disabling form submissions if there are invalid fields
(() => {
  "use strict";

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll(".needs-validation");

  // Loop over them and prevent submission
  Array.from(forms).forEach((form) => {
    form.addEventListener(
      "submit",
      (event) => {
        const customCheck = checkForCustomValidity(form);
        validatePasswords(form);
        if (!customCheck && !form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }

        form.classList.add("was-validated");
      },
      false
    );
  });
})();

function checkForCustomValidity(form) {
  const errors = form.querySelectorAll(
    '.invalid-feedback.error[style="display: block;"]'
  );
  if (errors) {
    Array.from(errors).forEach((error) => {
      error.previousElementSibling.setCustomValidity("error");
    });
    return false;
  }
  return true;
}

function validatePasswords(form) {
  const confirmPassword = form.querySelector("#passwordConfirm");
  if (confirmPassword) {
    const password = form.querySelector("#password");
    if (password.value == confirmPassword.value) {
      password.setCustomValidity("");
      confirmPassword.setCustomValidity("");
    } else {
      password.setCustomValidity("Passwords do not match");
      confirmPassword.setCustomValidity("Passwords do not match");
    }
  }
}

document.addEventListener("DOMContentLoaded", function () {
  Nette.showModal = function (modal) {
    return;
  };

  const passInputs = document.querySelectorAll("i.togglePassword");

  if (passInputs) {
    passInputs.forEach((input) => {
      input.addEventListener("click", function (event) {
        const icon = event.target;
        const passInput = icon.previousElementSibling;
        passInput.type = passInput.type == "text" ? "password" : "text";
        icon.classList.toggle("fa-eye-slash");
        icon.classList.toggle("fa-eye");
      });
    });
  }
});
