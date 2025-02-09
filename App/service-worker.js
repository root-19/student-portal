const CACHE_NAME = "student-portal-v1";
const STATIC_ASSETS = [
  "/",
  "/index.php",
  "/style.css",
  "./Public/js/index.js",
  "./icons/icon-192x192.png",
  "./icons/icon-512x512.png"
];

self.addEventListener("install", event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return cache.addAll(STATIC_ASSETS);
    })
  );
});

self.addEventListener("fetch", event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request);
    })
  );
});
