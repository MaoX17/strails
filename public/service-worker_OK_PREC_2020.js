/**
 * Welcome to your Workbox-powered service worker!
 *
 * You'll need to register this file in your web app and you should
 * disable HTTP caching for this file too.
 * See https://goo.gl/nhQhGp
 *
 * The rest of the code is auto-generated. Please don't update this file
 * directly; instead, make changes to your Workbox build configuration
 * and re-run your build process.
 * See https://goo.gl/2aRDsh
 */


importScripts("https://storage.googleapis.com/workbox-cdn/releases/3.6.3/workbox-sw.js");

importScripts(
  "./js/push_message.js",
  "/precache-manifest.38147f4b5a850f97b91b0c02cb07e1a1.js"
);

workbox.core.setCacheNameDetails({prefix: "strails-1589202917"});

workbox.skipWaiting();
workbox.clientsClaim();

/**
 * The workboxSW.precacheAndRoute() method efficiently caches and responds to
 * requests for URLs in the manifest.
 * See https://goo.gl/S9QRab
 */
self.__precacheManifest = [
  {
    "url": "css/backend.css",
    "revision": "5694ddaa82637d668916520b2ddfce14"
  },
  {
    "url": "css/backend.rtl.css",
    "revision": "26ef01bc25127ea33f34fe2a502021e8"
  },
  {
    "url": "css/backend/plugin/datatables/dataTables.bootstrap.min.css",
    "revision": "90e568434967792c6b3bb9650ae6ebc8"
  },
  {
    "url": "css/frontend.css",
    "revision": "1f8c32f595d9350eeac37e2f29d5c4a1"
  },
  {
    "url": "css/frontend.rtl.css",
    "revision": "886d4995ed130cf5ccdedebd9e353aa9"
  },
  {
    "url": "css/main.css",
    "revision": "d62bedf868dcfbcf58d971d1bfc58347"
  },
  {
    "url": "css/OverPassLayer.css",
    "revision": "63fa15817e44acfddc80748f4f69ad63"
  },
  {
    "url": "favicon.ico",
    "revision": "d41d8cd98f00b204e9800998ecf8427e"
  },
  {
    "url": "fonts/vendor/bootstrap-sass/bootstrap/glyphicons-halflings-regular.eot",
    "revision": "f4769f9bdb7466be65088239c12046d1"
  },
  {
    "url": "fonts/vendor/bootstrap-sass/bootstrap/glyphicons-halflings-regular.svg",
    "revision": "89889688147bd7575d6327160d64e760"
  },
  {
    "url": "fonts/vendor/bootstrap-sass/bootstrap/glyphicons-halflings-regular.ttf",
    "revision": "e18bbf611f2a2e43afc071aa2f4e1512"
  },
  {
    "url": "fonts/vendor/bootstrap-sass/bootstrap/glyphicons-halflings-regular.woff",
    "revision": "fa2772327f55d8198301fdb8bcfc8158"
  },
  {
    "url": "fonts/vendor/bootstrap-sass/bootstrap/glyphicons-halflings-regular.woff2",
    "revision": "448c34a56d699c29117adc64c43affeb"
  },
  {
    "url": "fonts/vendor/font-awesome/fontawesome-webfont.eot",
    "revision": "674f50d287a8c48dc19ba404d20fe713"
  },
  {
    "url": "fonts/vendor/font-awesome/fontawesome-webfont.svg",
    "revision": "912ec66d7572ff821749319396470bde"
  },
  {
    "url": "fonts/vendor/font-awesome/fontawesome-webfont.ttf",
    "revision": "b06871f281fee6b241d60582ae9369b9"
  },
  {
    "url": "fonts/vendor/font-awesome/fontawesome-webfont.woff",
    "revision": "fee66e712a8a08eef5805a46892932ad"
  },
  {
    "url": "fonts/vendor/font-awesome/fontawesome-webfont.woff2",
    "revision": "af7ae505a9eed503f8b8e6982036873e"
  },
  {
    "url": "images/gps-icon.svg",
    "revision": "dfd730fd6c0c52ee93029eb64645b737"
  },
  {
    "url": "img/api_logo_cptblWith_strava_horiz_gray.svg",
    "revision": "6c526069ad6de1e66c4251dd3c5f50a1"
  },
  {
    "url": "img/api_logo_cptblWith_strava_horiz_light.svg",
    "revision": "09d2c8e22101032ef723b64e4331c1b2"
  },
  {
    "url": "img/api_logo_cptblWith_strava_horiz_white.svg",
    "revision": "03435463aa60188b7901d7658de8208c"
  },
  {
    "url": "img/api_logo_cptblWith_strava_stack_gray.svg",
    "revision": "fc587df3f8170d26bfc6492ea4aa11a7"
  },
  {
    "url": "img/api_logo_cptblWith_strava_stack_light.svg",
    "revision": "180e4829000dca0de77cb5d251ced392"
  },
  {
    "url": "img/api_logo_cptblWith_strava_stack_white.svg",
    "revision": "712c61d4816a5ad92066405d5e34b18c"
  },
  {
    "url": "img/btn_strava_connectwith_light.svg",
    "revision": "2ab8cfd31c4502d8c44a64dca10b3ad8"
  },
  {
    "url": "img/logo/favicon.ico",
    "revision": "aa44fcfbbcb68dbeb68f537eb141e407"
  },
  {
    "url": "img/logo/manifest.json",
    "revision": "b58fcfa7628c9205cb11a1b2c3e8f99a"
  },
  {
    "url": "js/ajax_mappa_nostrava.js",
    "revision": "4a731dcf16872cb0e954a27b09ebd63e"
  },
  {
    "url": "js/ajax_mappa.js",
    "revision": "a09c9853f4afd84496cd6631639a1626"
  },
  {
    "url": "js/ajax_position_and_map_flyto.js",
    "revision": "8638b3327b34fd3f96c7f73d386fbcd7"
  },
  {
    "url": "js/ajax_rel_visible_leaf.js",
    "revision": "fd72ebfab2191ae2dd07480caf7937ec"
  },
  {
    "url": "js/ajax_rel_visible.js",
    "revision": "517bba3d0d51eaff5c0f42905c4f9fcc"
  },
  {
    "url": "js/ajax_segment_visible_leaf.js",
    "revision": "d5fd959b11d891aa02e494eeedb9d119"
  },
  {
    "url": "js/ajax_set_position.js",
    "revision": "6b601e7a0285bdbd5160cafcac2e2f23"
  },
  {
    "url": "js/ajax_set_type.js",
    "revision": "b3d51937f4cd3e9d7d96ef4bf863d9ad"
  },
  {
    "url": "js/ajax_strava_leaf.js",
    "revision": "29c39ba66a4f6d370a86fb2890ac5c13"
  },
  {
    "url": "js/backend.js",
    "revision": "fcbae3a7ffc2a8ce10b78e8f743be7a2"
  },
  {
    "url": "js/backend/access/roles/script.js",
    "revision": "9f1fd2f19fef96690c9dd9f12250f5ed"
  },
  {
    "url": "js/backend/access/users/script.js",
    "revision": "ac3b8bcf56db8712485b73509e0a8a9a"
  },
  {
    "url": "js/backend/plugin/datatables/dataTables-extend.js",
    "revision": "19c5a190ac7cdbd7da2bf5fc8e6a7e20"
  },
  {
    "url": "js/backend/plugin/datatables/dataTables.bootstrap.min.js",
    "revision": "63b062ca2b0c3f964a6441b525195161"
  },
  {
    "url": "js/backend/plugin/datatables/jquery.dataTables.min.js",
    "revision": "68e14434ba097ba3db4f0a1b3041842e"
  },
  {
    "url": "js/dtExtend.js",
    "revision": "91f6a35fa623ad34c56e423ca7566b36"
  },
  {
    "url": "js/frontend.js",
    "revision": "b4c1ad3d6883973e12901ef4b778578c"
  },
  {
    "url": "js/geoPosition.js",
    "revision": "6711568fba5df773c07e94a2502f80f6"
  },
  {
    "url": "js/leaflet-color-markers.js",
    "revision": "3200d69657787eb6fcaf1609fac7499b"
  },
  {
    "url": "js/leaflet.active-layers.min.js",
    "revision": "6e6bef2f8efc79e5509f39ca9eb2881b"
  },
  {
    "url": "js/leaflet.select-layers.min.js",
    "revision": "b4fd3b5c93a22d2bcf208b8577805bf3"
  },
  {
    "url": "js/notifiche.js",
    "revision": "e0102f76159388933d2e3e63a73e1c9a"
  },
  {
    "url": "js/OverPassLayer.js",
    "revision": "295990620561800fee5094755aaa70c1"
  },
  {
    "url": "js/OverPassLayer.min.js",
    "revision": "4eb0ee053ac15682d4f427a423ad2b64"
  },
  {
    "url": "js/push_message.js",
    "revision": "8e4c1d9df948ba07fac4b404d511d70b"
  },
  {
    "url": "js/structured_data.js",
    "revision": "ca4189d635964f5240dc0617bb98d6c1"
  },
  {
    "url": "manifest.json",
    "revision": "a60c58ed754304633a6f5fdafa5ab6ea"
  },
  {
    "url": "mix-manifest.json",
    "revision": "162ddca31d634337741a50c9516b620c"
  },
  {
    "url": "precache-manifest.38147f4b5a850f97b91b0c02cb07e1a1.js",
    "revision": "77c0ba050cb512b7d9668672ccc672c4"
  },
  {
    "url": "service-worker.js",
    "revision": "4df0521b9af75151792b688f482e4c0c"
  },
  {
    "url": "storage/full_0_100.json",
    "revision": "ede4ed9a1ba286d35fa9850fdbc78cbf"
  },
  {
    "url": "storage/test.json",
    "revision": "fc8d91f6112cbb1a6e46ae441f9b25b1"
  },
  {
    "url": "img/ajax-loader.gif",
    "revision": "73e57937304d89f251e7e540a24b095a"
  },
  {
    "url": "img/api_logo_cptblWith_strava_horiz_gray.png",
    "revision": "4157878ccdfdd36535c2f5c3f3090a1a"
  },
  {
    "url": "img/api_logo_cptblWith_strava_horiz_light.png",
    "revision": "431c970a65ebfd3956dc21f05452d9e6"
  },
  {
    "url": "img/api_logo_cptblWith_strava_horiz_white.png",
    "revision": "a2e782a0a786374df17ac2b35b891a78"
  },
  {
    "url": "img/api_logo_cptblWith_strava_stack_gray.png",
    "revision": "8ced4012c3b3ce426a71a7400ea1795e"
  },
  {
    "url": "img/api_logo_cptblWith_strava_stack_light.png",
    "revision": "f38c7908f433dc04735ffccdd13322c5"
  },
  {
    "url": "img/api_logo_cptblWith_strava_stack_white.png",
    "revision": "3e3f6da46baf5208089841c485307951"
  },
  {
    "url": "img/btn_strava_connectwith_light.png",
    "revision": "cef6581f1a4c0f77f2608fbd6dba41a9"
  },
  {
    "url": "img/btn_strava_connectwith_light@2x.png",
    "revision": "87dda78fde827ddd54c949a97f42b9b3"
  },
  {
    "url": "img/mapbox-icon.png",
    "revision": "8ea6aeab8d08fc9d04790391e8cf6ce7"
  },
  {
    "url": "img/marker-icon-2x-black.png",
    "revision": "3cea109550add81d0e496413597b53c5"
  },
  {
    "url": "img/marker-icon-2x-blue.png",
    "revision": "1c824216f354218b04b25a57e0f7ab1f"
  },
  {
    "url": "img/marker-icon-2x-green.png",
    "revision": "f1d1fa459667562954aa55e308dd458e"
  },
  {
    "url": "img/marker-icon-2x-grey.png",
    "revision": "fbaf21bd96b7ebb9bcdc2752434645d8"
  },
  {
    "url": "img/marker-icon-2x-orange.png",
    "revision": "1f2da930c1dc642df9471f6b28f2d8d2"
  },
  {
    "url": "img/marker-icon-2x-red.png",
    "revision": "07aea9c9e256331b73e8b57f42ba4b59"
  },
  {
    "url": "img/marker-icon-2x-violet.png",
    "revision": "edb6cda1ff830da64e17bdfa4ea4d2b8"
  },
  {
    "url": "img/marker-icon-2x-yellow.png",
    "revision": "681191b239bffb43f1c1066172e2f7ad"
  },
  {
    "url": "img/marker-icon-black.png",
    "revision": "45174cbbb283291b83cbe1c2a7e88fd3"
  },
  {
    "url": "img/marker-icon-blue.png",
    "revision": "87f6ca46ac356e81dc438589630ae107"
  },
  {
    "url": "img/marker-icon-green.png",
    "revision": "fd617c65b600b88557ea0be2aa276622"
  },
  {
    "url": "img/marker-icon-grey.png",
    "revision": "6b6c22c40da12951441256c8165daefd"
  },
  {
    "url": "img/marker-icon-orange.png",
    "revision": "33debfeaa7ab89550638477b4c248ffb"
  },
  {
    "url": "img/marker-icon-red.png",
    "revision": "d6be1b369d9fc5406e417a5b29b4ea1b"
  },
  {
    "url": "img/marker-icon-violet.png",
    "revision": "4208273d4e0e22ec5646e1f038d681b6"
  },
  {
    "url": "img/marker-icon-yellow.png",
    "revision": "8ab891caec2cc46ca93984c15b3e2ef8"
  },
  {
    "url": "img/marker-shadow.png",
    "revision": "44014eaa598e7050354603adfb44f812"
  }
].concat(self.__precacheManifest || []);
workbox.precaching.suppressWarnings();
workbox.precaching.precacheAndRoute(self.__precacheManifest, {});

workbox.routing.registerRoute(/https:\/\/fonts.gstatic.com/, workbox.strategies.cacheFirst({ "cacheName":"google-fonts", plugins: [new workbox.expiration.Plugin({"maxAgeSeconds":31536000,"purgeOnQuotaError":false})] }), 'GET');
workbox.routing.registerRoute(/https:\/\/fonts.googleapis.com/, workbox.strategies.staleWhileRevalidate({ "cacheName":"google-stylesheets", plugins: [] }), 'GET');
workbox.routing.registerRoute(/https:\/\/(a|b|c).tile.openstreetmap.org/, workbox.strategies.staleWhileRevalidate({ "cacheName":"map", plugins: [] }), 'GET');
workbox.routing.registerRoute(/https:\/\/(a|b|c).tile.thunderforest.com/, workbox.strategies.staleWhileRevalidate({ "cacheName":"map", plugins: [] }), 'GET');
workbox.routing.registerRoute(/.(?:jpg|png|gif|svg|ico)$/, workbox.strategies.cacheFirst({ "cacheName":"img", plugins: [new workbox.expiration.Plugin({"maxAgeSeconds":2592000,"maxEntries":100,"purgeOnQuotaError":false}), new workbox.cacheableResponse.Plugin({"statuses":[0,200]})] }), 'GET');
workbox.routing.registerRoute(/.(?:js|css)$/, workbox.strategies.staleWhileRevalidate({ "cacheName":"css-js", plugins: [] }), 'GET');
workbox.routing.registerRoute(/\/auth/, workbox.strategies.networkOnly(), 'GET');
workbox.routing.registerRoute(/\/login/, workbox.strategies.networkOnly(), 'GET');
workbox.routing.registerRoute(/\/logout/, workbox.strategies.networkOnly(), 'GET');
workbox.routing.registerRoute("/", workbox.strategies.staleWhileRevalidate({ "cacheName":"root","fetchOptions":{"credentials":"include","mode":"same-origin"}, plugins: [new workbox.expiration.Plugin({"maxEntries":150,"maxAgeSeconds":259200,"purgeOnQuotaError":false}), new workbox.cacheableResponse.Plugin({"statuses":[0,200]}), new workbox.broadcastUpdate.Plugin("my-update-channel")] }), 'GET');
workbox.routing.registerRoute(/https:\/\/cdn.jsdelivr.net/, workbox.strategies.cacheFirst({ "cacheName":"cdn", plugins: [new workbox.expiration.Plugin({"maxAgeSeconds":31536000,"maxEntries":100,"purgeOnQuotaError":false}), new workbox.cacheableResponse.Plugin({"statuses":[0,200]})] }), 'GET');
workbox.routing.registerRoute(/https:\/\/cdnjs.cloudflare.com/, workbox.strategies.cacheFirst({ "cacheName":"cdn", plugins: [new workbox.expiration.Plugin({"maxAgeSeconds":31536000,"maxEntries":100,"purgeOnQuotaError":false}), new workbox.cacheableResponse.Plugin({"statuses":[0,200]})] }), 'GET');
workbox.routing.registerRoute(/https:\/\/cdn.rawgit.com/, workbox.strategies.cacheFirst({ "cacheName":"cdn", plugins: [new workbox.expiration.Plugin({"maxAgeSeconds":31536000,"maxEntries":100,"purgeOnQuotaError":false}), new workbox.cacheableResponse.Plugin({"statuses":[0,200]})] }), 'GET');
workbox.routing.registerRoute(/https:\/\/unpkg.com/, workbox.strategies.cacheFirst({ "cacheName":"cdn", plugins: [new workbox.expiration.Plugin({"maxAgeSeconds":31536000,"maxEntries":100,"purgeOnQuotaError":false}), new workbox.cacheableResponse.Plugin({"statuses":[0,200]})] }), 'GET');
