// Inisialisasi PWA Service Worker
function registerServiceWorker() {
  if (!("serviceWorker" in navigator)) return;

  window.addEventListener("load", () => {
    navigator.serviceWorker
      .register("./sw.js")
      .catch((err) => {
        console.warn("ServiceWorker registration failed:", err);
      });
  });
}

registerServiceWorker();
