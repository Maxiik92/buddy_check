document.addEventListener("DOMContentLoaded", () => {
  const fields = document.querySelectorAll("[data-field]");
  fields.forEach((field) => {
    field.addEventListener("blur", function () {
      const fieldType = this.getAttribute("data-field");
      const fieldValue = encodeURIComponent(this.value);

      if (!fieldValue.trim()) {
        return;
      }

      fetch(`?do=checkUnique&field=${fieldType}&value=${fieldValue}`)
        .then((res) => res.json())
        .then((data) => {
          const errorDiv = document.querySelector(`#${fieldType}-error`);
          if (data.error) {
            errorDiv.textContent = data.error;
            errorDiv.style.display = "block";
            this.classList.add("is-invalid");
          } else {
            errorDiv.textContent = "";
            errorDiv.style.display = "none";
            this.classList.remove("is-invalid");
            errorDiv.previousElementSibling.setCustomValidity("");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
        });
    });
  });
});
