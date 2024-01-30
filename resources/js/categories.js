import { Modal } from "bootstrap";

window.addEventListener("DOMContentLoaded", function () {
  const editCategoryModal = new Modal(
    document.getElementById("editCategoryModal")
  );

  document.querySelectorAll(".edit-category-btn").forEach((button) => {
    button.addEventListener("click", function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");

			console.log(editCategoryModal)
      fetch(`/categories/${categoryId}`)
        .then((response) => response.json())
        .then((response) =>
          openEditCategoryModal(editCategoryModal, response)
        );
    });
  });

  document
    .querySelector(".save-category-btn")
    .addEventListener("click", function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");

    });
});

function openEditCategoryModal(modal, { id, name }) {
  const nameInput = modal._element.querySelector('input[name="name"]');

  nameInput.value = name;

  modal._element
    .querySelector(".save-category-btn")
    .setAttribute("data-id", id);

  modal.show();
}
