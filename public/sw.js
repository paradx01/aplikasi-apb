"use strict";

const SW_VERSION = "v5";
const STATIC_CACHE = `static-${SW_VERSION}`;
const RUNTIME_CACHE = `runtime-${SW_VERSION}`;
const OFFLINE_URL = "/offline.html";

// Hanya pre-cache file yang stabil dan umum dipakai.
const PRECACHE_URLS = [
  "/",
  OFFLINE_URL,
  "/manifest.json",
  "/logo192.png",
  "/logo512.png"
];

// Route buyer yang boleh disimpan untuk fallback offline.
const BUYER_NAV_ALLOWLIST = [
  "/",
  "/search",
  "/dashboard",
  "/carts",
  "/product_transactions",
  "/product_transactions/checkout/success",
  "/addresses/addresslist",
  "/reminders",
  "/expert-system",
  "/expert-system/gejala-umum",
  "/expert-system/gejala-kritis",
  "/expert-system/diagnosa",
  "/expert-system/medicine-recommendation",
  "/expert-system/riwayat-rekomendasi"
];

const BUYER_NAV_PREFIXES = [
  "/details/",
  "/category/",
  "/carts/",
  "/product_transactions/",
  "/addresses/",
  "/expert-system/",
  "/profile/",
  "/profile"
];

const BLOCKED_NAV_PREFIXES = ["/admin", "/login", "/register"];

function isBuyerNavigationPath(pathname) {
  if (BLOCKED_NAV_PREFIXES.some((prefix) => pathname.startsWith(prefix))) {
    return false;
  }

  return (
    BUYER_NAV_ALLOWLIST.includes(pathname) ||
    BUYER_NAV_PREFIXES.some((prefix) => pathname.startsWith(prefix))
  );
}

self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(STATIC_CACHE).then(async (cache) => {
      await Promise.all(
        PRECACHE_URLS.map((url) =>
          cache.add(url).catch(() => {
            // Jangan gagalkan install jika 1 aset pre-cache gagal.
          })
        )
      );
    })
  );
  self.skipWaiting();
});

self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) =>
      Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== STATIC_CACHE && cacheName !== RUNTIME_CACHE) {
            return caches.delete(cacheName);
          }
          return Promise.resolve();
        })
      )
    )
  );
  self.clients.claim();
});

// Memungkinkan frontend memicu aktivasi SW baru tanpa reload berulang.
self.addEventListener("message", (event) => {
  if (event.data && event.data.type === "SKIP_WAITING") {
    self.skipWaiting();
  }
});

self.addEventListener("fetch", (event) => {
  const { request } = event;

  // Hanya tangani request GET.
  if (request.method !== "GET") return;

  const url = new URL(request.url);

  // Navigasi dokumen: network-first, fallback cache/offline.
  if (request.mode === "navigate") {
    event.respondWith(handleNavigationRequest(request, url));
    return;
  }

  // Aset statis: stale-while-revalidate.
  if (["style", "script", "font", "image", "worker"].includes(request.destination)) {
    event.respondWith(staleWhileRevalidate(request));
    return;
  }

  // API / request data: network-first.
  const acceptsJson = request.headers.get("accept")?.includes("application/json");
  if (acceptsJson || url.pathname.startsWith("/api/")) {
    event.respondWith(networkFirst(request));
    return;
  }

  // Lainnya: cache-first ringan untuk same-origin.
  if (url.origin === self.location.origin) {
    event.respondWith(cacheFirst(request));
  }
});

async function handleNavigationRequest(request, url) {
  try {
    const networkResponse = await fetch(request);

    // Simpan hanya halaman buyer; route admin/auth tidak dimasukkan.
    if (networkResponse.ok && url.origin === self.location.origin && isBuyerNavigationPath(url.pathname)) {
      const cache = await caches.open(RUNTIME_CACHE);
      cache.put(request, networkResponse.clone());
    }

    return networkResponse;
  } catch {
    const cachedPage = await caches.match(request);
    if (cachedPage) return cachedPage;

    const offlinePage = await caches.match(OFFLINE_URL);
    return offlinePage || Response.error();
  }
}

async function staleWhileRevalidate(request) {
  const cache = await caches.open(RUNTIME_CACHE);
  const cached = await cache.match(request);

  const networkPromise = fetch(request)
    .then((response) => {
      if (response && response.ok) {
        cache.put(request, response.clone());
      }
      return response;
    })
    .catch(() => null);

  return cached || networkPromise || Response.error();
}

async function networkFirst(request) {
  const cache = await caches.open(RUNTIME_CACHE);

  try {
    const fresh = await fetch(request);
    if (fresh && fresh.ok) {
      cache.put(request, fresh.clone());
    }
    return fresh;
  } catch {
    const cached = await cache.match(request);
    return cached || Response.error();
  }
}

async function cacheFirst(request) {
  const cache = await caches.open(RUNTIME_CACHE);
  const cached = await cache.match(request);
  if (cached) return cached;

  const fresh = await fetch(request);
  if (fresh && fresh.ok) {
    cache.put(request, fresh.clone());
  }
  return fresh;
}

self.addEventListener("push", (event) => {
  let data = {
    title: "Pengingat Obat",
    body: "Waktunya minum obat!",
    icon: "/logo192.png",
    url: "/reminders"
  };

  if (event.data) {
    try {
      data = event.data.json();
    } catch {
      data.body = event.data.text();
    }
  }

  const options = {
    body: data.body,
    icon: data.icon || "/logo192.png",
    badge: "/logo192.png",
    vibrate: [100, 50, 100],
    data: {
      url: data.url || "/reminders"
    },
    actions: [{ action: "open", title: "Lihat" }]
  };

  event.waitUntil(self.registration.showNotification(data.title || "Notifikasi", options));
});

self.addEventListener("notificationclick", (event) => {
  event.notification.close();
  const urlToOpen = event.notification?.data?.url || "/";

  event.waitUntil(
    clients.matchAll({ type: "window", includeUncontrolled: true }).then((windowClients) => {
      for (const client of windowClients) {
        if (client.url.includes(urlToOpen) && "focus" in client) {
          return client.focus();
        }
      }
      if (clients.openWindow) {
        return clients.openWindow(urlToOpen);
      }
      return undefined;
    })
  );
});
