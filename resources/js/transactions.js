import { Modal } from "bootstrap";
import { post, get, del } from "./ajax";
import DataTable from "datatables.net";

window.addEventListener("DOMContentLoaded", function () {
  const newTransactionModal = new Modal(
    document.getElementById("newTransactionModal")
  );
  const editTransactionModal = new Modal(
    document.getElementById("editTransactionModal")
  );

  const renderTableActionBtns = (row) => `
        <div class="d-flex flex-">
          <button type="submit" class="btn btn-outline-primary delete-transaction-btn" data-id="${row.id}">
            <i class="bi bi-trash3-fill"></i>
          </button>
          <button class="ms-2 btn btn-outline-primary edit-transaction-btn" data-id="${row.id}">
            <i class="bi bi-pencil-fill"></i>
          </button>
        </div>
  `;
  const table = new DataTable("#transactionsTable", {
    serverSide: true,
    ajax: "/transactions/load",
    orderMulti: false,
    columns: [
      { data: "description" },
      {
        data: (row) =>
          new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: "USD",
            currencySign: "accounting",
          }).format(row.amount),
      },
      { data: "category", sortable: false },
      { data: "date" },
      {
        sortable: false,
        data: (row) => renderTableActionBtns(row),
      },
    ],
  });

  document
    .querySelector("#transactionsTable")
    .addEventListener("click", function (event) {
      const editBtn = event.target.closest(".edit-transaction-btn");
      const deleteBtn = event.target.closest(".delete-transaction-btn");
      if (editBtn) {
        const transactionId = editBtn.getAttribute("data-id");
        get(`/transactions/${transactionId}`)
          .then((response) => response.json())
          .then((response) => {
            openEditTransactionModal(editTransactionModal, response);
          });
      } else {
        const transactionId = deleteBtn.getAttribute("data-id");

        if (confirm("are you sure you want to delete the transaction?"))
          del(`/transactions/${transactionId}`).then((response) => {
            if (response.ok) {
              table.draw();
            }
          });
      }
    });

  document
    .querySelector(".create-transaction-btn")
    .addEventListener("click", function (event) {
      post(
        `/transactions/`,
        getTransactionFormData(newTransactionModal),
        newTransactionModal._element
      ).then((response) => {
        if (response.ok) {
          table.draw();
          newTransactionModal.hide();
        }
      });
    });

  document
    .querySelector(".save-transaction-btn")
    .addEventListener("click", function (event) {
      const transactionId = event.currentTarget.getAttribute("data-id");

      post(
        `/transactions/${transactionId}`,
        getTransactionFormData(editTransactionModal),
        editTransactionModal._element
      ).then((response) => {
        if (response.ok) {
          table.draw();
          editTransactionModal.hide();
        }
      });
    });
  function getTransactionFormData(modal) {
    console.log(modal)
    let data = {};
    const fields = [
      ...modal._element.getElementsByTagName("input"),
      ...modal._element.getElementsByTagName("select"),
    ];

    fields.forEach((field) => {
      data[field.name] = field.value;
    });
    return data;
  }

  function openEditTransactionModal(modal, { id, ...data }) {
    for (let name in data) {
      const nameInput = modal._element.querySelector(`[name="${name}"]`);
      nameInput.value = data[name];
    }

    modal._element
      .querySelector(".save-transaction-btn")
      .setAttribute("data-id", id);

    modal.show();
  }
});