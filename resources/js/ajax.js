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
    options.body = JSON.stringify({ ...data, ...getCsrfFields() });
  } else if (method === "get") {
    url += "?" + new URLSearchParams(data).toString();
  }

  return fetch(url, options).then((response) => {
    if (!response.ok) {
      if (response.status === 422) {
        response.json().then((errors) => {
          handleValidationErrors(errors, domElement);
        });
      }
    }
    return response;
  });
};
function handleValidationErrors(errors, domElement) {
  for (const name in errors) {
    const element = domElement.querySelector(`input[name="${name}"]`);
    element.classList.add("is-invalid");

    for (const error of errors[name]) {
      const errorDiv = document.createElement("div");
      errorDiv.classList.add("invalid-feedback");
      errorDiv.textContent = error;
      element.parentNode.append(errorDiv);
    }
  }
}

const get = (url, data) => ajax(url, "get", data);
const post = (url, data, domElement) => ajax(url, "post", data, domElement);

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

export { ajax, get, post };
