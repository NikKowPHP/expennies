const ajax = (url, method = "get", data = {}, domElement = null) => {
  method = method.toLowerCase();
  let options = {
    method,
    headers: {
      "Content-Type": "application/json",
      "X-Requested-With": "XMLHttpRequest",
    },
  };

  const csrfMethods = new Set(["post", "put", "delete", "patch"]);

  if (csrfMethods.has(method)) {
    let additionalFields = { ...getCsrfFields() };
    if (method !== "post") {
      additionalFields._METHOD = method.toUpperCase();
      options.method = method.toUpperCase();
    }
    if (data instanceof FormData) {
      for (const additionalField in additionalFields) {
        data.append(additionalField, additionalFields[additionalField]);
      }
      delete options.headers["Content-Type"];
      options.body = data;
    } else {
      options.body = JSON.stringify({ ...data, ...getCsrfFields() });
    }
  } else if (method === "get") {
    url += "?" + new URLSearchParams(data).toString();
  }

  return fetch(url, options).then((response) => {
    if (domElement) {
      clearValidationErrors(domElement);
    }
    if (!response.ok) {
      if (response.status === 422) {
        response.json().then((errors) => {
          handleValidationErrors(errors, domElement);
        });
      } else if (response.status === 404) {
        alert(response.statusText);
      }
    }
    return response;
  });
};
function handleValidationErrors(errors, domElement) {
  for (const name in errors) {
    const element = domElement.querySelector(`[name="${name}"]`);
    element.classList.add("is-invalid");

    for (const error of errors[name]) {
      const errorDiv = document.createElement("div");
      errorDiv.classList.add("invalid-feedback");
      errorDiv.textContent = errors[name][0];
      element.parentNode.append(errorDiv);
    }
  }
}

function clearValidationErrors(domElement) {
  const elements = domElement.querySelectorAll(".is-invalid");
  if (elements.length > 0) {
    elements.forEach((element) => {
      element.classList.remove("is-invalid");
      const parentElements =
        element.parentNode.querySelectorAll(".invalid-feedback");
      if (parentElements.length > 0) {
        parentElements.forEach((element) => {
          element.remove();
        });
      }
    });
  }
}
const get = (url, data) => ajax(url, "get", data);
const post = (url, data, domElement) => ajax(url, "post", data, domElement);
const del = (url, data) => ajax(url, "delete", data);

function getCsrfFields() {
  const csrfNameField = document.querySelector("#csrfName");
  const csrfValueField = document.querySelector("#csrfValue");
  const csrfNameKey = csrfNameField.getAttribute("name");
  const csrfValueKey = csrfValueField.getAttribute("name");
  const csrfName = csrfNameField.content;
  const csrfValue = csrfValueField.content;

  return {
    [csrfNameKey]: csrfName,
    [csrfValueKey]: csrfValue,
  };
}

export { ajax, get, post, del };
