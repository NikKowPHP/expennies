import { Modal } from "bootstrap";
import { post, get, del } from "./ajax";

window.addEventListener("DOMContentLoaded", function () {
  const editCategoryModal = new Modal(
    document.getElementById("editCategoryModal")
  );

  document.querySelectorAll(".edit-category-btn").forEach((button) => {
    button.addEventListener("click", function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");

      get(`/categories/${categoryId}`)
        .then((response) => response.json())
        .then((response) => {
          openEditCategoryModal(editCategoryModal, response);
        });
    });
  });

  document.querySelectorAll(".delete-category-btn").forEach((button) => {
    button.addEventListener("click", function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");
    if (confirm("are you sure you want to delete a category?"))
      del(`/categories/${categoryId}`);
    });
  });

  document
    .querySelector(".save-category-btn")
    .addEventListener("click", function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");

      post(
        `/categories/${categoryId}`,
        {
          name: editCategoryModal._element.querySelector('input[name="name"]')
            .value,
        },
        editCategoryModal._element
      ).then((response) => {
        if (response.ok) {
          editCategoryModal.hide();
        }
      });
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
