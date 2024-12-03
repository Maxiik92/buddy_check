const notyf = new Notyf();
const flashContainer = document.getElementById("flash-messages");
const flashes = flashContainer
  ? JSON.parse(flashContainer.dataset.flashes)
  : [];

flashes.forEach((flash) => {
  switch (flash.type) {
    case "success":
      notyf.success(flash.message);
      break;
    case "error":
      notyf.error(flash.message);
      break;
    case "info":
      notyf.open({
        type: "info",
        message: flash.message,
        background: "#3b82f6",
        icon: flashes,
      });
      break;
    default:
      notyf.open({ message: flash.message });
  }
});
