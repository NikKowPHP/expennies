import { Modal } from "bootstrap";
import { post, get, del } from "./ajax";
import DataTable from "datatables.net";

window.addEventListener("DOMContentLoaded", function () {
  const editCategoryModal = new Modal(
    document.getElementById("editCategoryModal")
  );
  const createCategoryModal = new Modal(
    document.getElementById("newCategoryModal")
  );

  const renderTableActionBtns = (row) => `
        <div class="d-flex flex-">
          <button type="submit" class="btn btn-outline-primary delete-category-btn" data-id="${row.id}">
            <i class="bi bi-trash3-fill"></i>
          </button>
          <button class="ms-2 btn btn-outline-primary edit-category-btn" data-id="${row.id}">
            <i class="bi bi-pencil-fill"></i>
          </button>
        </div>
  `;
  const table = new DataTable("#categoriesTable", {
    serverSide: true,
    ajax: "/categories/load",
    orderMulti: false,
    columns: [
      { data: "name" },
      { data: "createdAt" },
      { data: "updatedAt" },
      {
        sortable: false,
        data: (row) => renderTableActionBtns(row),
      },
    ],
  });

  document
    .querySelector("#categoriesTable")
    .addEventListener("click", function (event) {
      const editBtn = event.target.closest(".edit-category-btn");
      const deleteBtn = event.target.closest(".delete-category-btn");
      if (editBtn) {
        const categoryId = editBtn.getAttribute("data-id");
        get(`/categories/${categoryId}`)
          .then((response) => response.json())
          .then((response) => {
            openEditCategoryModal(editCategoryModal, response);
          });
      } else {
        const categoryId = deleteBtn.getAttribute("data-id");
        if (confirm("are you sure you want to delete a category?"))
          del(`/categories/${categoryId}`).then(() => {
            if (response.ok) {
              table.draw();
            }
          });
      }
    });

  document
    .querySelector("#createNewCategoryBtn")
    .addEventListener("click", function (event) {
      post(
        `/categories`,
        {
          name: createCategoryModal._element.querySelector('input[name="name"]')
            .value,
        },
        createCategoryModal._element
      ).then((response) => {
        if (response.ok) {
          table.draw();
          createCategoryModal.hide();
        }
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
          table.draw();
          editCategoryModal.hide();
        }
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
});
