(function () {
	// document.addEventListener("click", (e) => {
	// 	const target = e.target;
	// 	if (!target.closest(".envision-blocks-nav-tab-wrapper a")) {
	// 		return;
	// 	}
	// 	e.preventDefault();
	// 	const href = target.getAttribute("href");
	// 	const currentUrl = window.location.href; // Get the current page URL
	// 	var newUrl = currentUrl.split("#")[0] + href; // Append the tab's href to the current URL
	// 	history.pushState(null, null, newUrl); // Change the URL without reloading the page
	// 	document.querySelectorAll(".envision-blocks-nav-tab-wrapper a").forEach((tablink) => {
	// 		tablink.classList.remove("nav-tab-active");
	// 	});
	// 	target.classList.add("nav-tab-active");
	// 	targetTab = target.getAttribute("data-tab");
	// 	document
	// 		.querySelectorAll(".envision-blocks-settings-page .envision-blocks-tab-content")
	// 		.forEach((item) => {
	// 			if (item.classList.contains(`envision-blocks-tab-content-${targetTab}`)) {
	// 				item.style.display = "block";
	// 			} else {
	// 				item.style.display = "none";
	// 			}
	// 		});
	// });
	// document.addEventListener("DOMContentLoaded", () => {
	// 	const hash = window.location.hash;
	// 	console.log(window.location.href);
	// 	if (hash && hash.startsWith("#tab")) {
	// 		const tabId = hash.substring(1);
	// 		const tabLink = document.querySelector(
	// 			`.envision-blocks-nav-tab-wrapper a[href="${hash}"]`
	// 		);
	// 		document.querySelectorAll(".envision-blocks-nav-tab-wrapper a").forEach((tablink) => {
	// 			tablink.classList.remove("nav-tab-active");
	// 		});
	// 		if (tabLink) {
	// 			tabLink.classList.add("nav-tab-active");
	// 			const targetTab = tabLink.getAttribute("data-tab");
	// 			document
	// 				.querySelectorAll(".envision-blocks-settings-page .envision-blocks-tab-content")
	// 				.forEach((item) => {
	// 					if (item.classList.contains(`envision-blocks-tab-content-${targetTab}`)) {
	// 						item.style.display = "block";
	// 					} else {
	// 						item.style.display = "none";
	// 					}
	// 				});
	// 		}
	// 	}
	// });
})();
