const notyf = new Notyf({
  position: {
    x: "right",
    y: "top",
  },
});
const flashContainer = document.getElementById("flash-messages");
const flashes = flashContainer
  ? JSON.parse(flashContainer.dataset.flashes)
  : [];

flashes.forEach((flash) => {
  switch (flash.type) {
    case "success":
      notyf.success(flash.message);
      break;
    case "danger":
    case "error":
      notyf.error(flash.message);
      break;
    case "info":
      notyf.open({
        type: "info",
        message: flash.message,
        background: "#3b82f6",
        icon: '<i class="fa-solid fa-circle-info"></i>',
      });
      break;
    default:
      notyf.open({ message: flash.message });
  }
});
