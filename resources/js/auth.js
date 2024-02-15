import "../css/auth.scss";
import { get, post, del } from "./ajax";

window.addEventListener("DOMContentLoaded", function () {

  document
    .querySelector(".btn-logout")
    .addEventListener("click", function (event) {
      post(
        `/logout`,
      )
			.then(response => {
				if(response.ok) {
					window.location.href = "/"
				}
			})
    });





});
