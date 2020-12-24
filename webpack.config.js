/**
 * As our first step, we'll pull in the user's webpack.mix.js
 * file. Based on what the user requests in that file,
 * a generic config object will be constructed for us.
 */
//let mix = require('../src/index');
let mix = require('./node_modules/laravel-mix/src/index');

let ComponentFactory = require('./node_modules/laravel-mix/src/components/ComponentFactory');

new ComponentFactory().installAll();

require(Mix.paths.mix());

/**
 * Just in case the user needs to hook into this point
 * in the build process, we'll make an announcement.
 */

Mix.dispatch('init', Mix);

/**
 * Now that we know which build tasks are required by the
 * user, we can dynamically create a configuration object
 * for Webpack. And that's all there is to it. Simple!
 */

let WebpackConfig = require('./node_modules/laravel-mix/src/builder/WebpackConfig');


let path = require('path');
let glob = require('glob');
let webpack = require('webpack');
//??//let Mix = require('laravel-mix').config;
let webpackPlugins = require('laravel-mix').plugins;
let dotenv = require('dotenv');
let workboxPlugin = require('workbox-webpack-plugin');
//let SWPrecacheWebpackPlugin = require('sw-precache-webpack-plugin');


module.exports = new WebpackConfig().build();

module.exports.plugins.push(
  new workboxPlugin.GenerateSW(

    {

      //cacheId: '3',
	  cacheId: 'strails-' + Math.floor(Date.now() / 1000),

      globDirectory: 'public/',
      globIgnores: ['public/*.map', 'public/manifest.json', 'public/mix-manifest.json', 'public/*.config'],
      globPatterns: [
        "**/*.{css,ico,eot,svg,ttf,woff,woff2,js,json}",
        "img/*.{png,jpg,jpeg,gif,bmp}",
      ],

      modifyUrlPrefix: {
        // Remove a '/dist' prefix from the URLs:
        '/public': ''
      },

      clientsClaim: true,
      skipWaiting: true,


      runtimeCaching: [
/*
        {
          urlPattern: new RegExp(`${process.env.APP_URL}`),
          handler: 'networkFirst',
          options: {
            cacheName: `${process.env.APP_NAME}-${process.env.APP_ENV}`
          }
        },
*/
        {
          urlPattern: new RegExp('https://fonts.gstatic.com'),
          handler: 'cacheFirst',
          options: {
	    cacheName: 'google-fonts',
            expiration: {
              maxAgeSeconds: 60 * 60 * 24 * 365,
            },
          }
        },
        {
          //urlPattern: new RegExp('https://fonts.(googleapis|gstatic).com'),
          urlPattern: new RegExp('https://fonts.googleapis.com'),
          handler: 'staleWhileRevalidate',
          options: {
            cacheName: 'google-stylesheets',
          }
        },

	    {
          urlPattern: new RegExp('https://(a|b|c).tile.openstreetmap.org'),
          handler: 'staleWhileRevalidate',
          options: {
            cacheName: 'map',
          }
        },
		
		{
         urlPattern: new RegExp('https://(a|b|c).tile.thunderforest.com'),
         handler: 'staleWhileRevalidate',
         options: {
           cacheName: 'map',
         }
        },


        //images
        {
          // To match cross-origin requests, use a RegExp that matches
          // the start of the origin:
          urlPattern: new RegExp('.(?:jpg|png|gif|svg|ico)$'),
	  handler: 'cacheFirst',
          options: {
            cacheName: 'img',
            expiration: {
              maxAgeSeconds: 60 * 60 * 24 * 30,
              maxEntries: 100,
            },
            cacheableResponse: {
              statuses: [0, 200]
            }
          }
       },

      
//css js
//      // To match cross-origin requests, use a RegExp that matches
//              // the start of the origin:
      {
        urlPattern: new RegExp('.(?:js|css)$'),
        handler: 'staleWhileRevalidate',
        options: {
          cacheName: 'css-js',
        },
      },

      {
        urlPattern: new RegExp('/auth'),
        handler: 'networkOnly',
      },
      {
        urlPattern: new RegExp('/login'),
        handler: 'networkOnly',
      },
      {
        urlPattern: new RegExp('/logout'),
        handler: 'networkOnly',
      },

        // root site
        {
          // Match any same-origin request that contains 'ajax'.
          urlPattern: '/',
          // Apply a network-first strategy.
          //handler: 'networkFirst',
          handler: 'staleWhileRevalidate',
          options: {
            // Fall back to the cache after 10 seconds.
            //networkTimeoutSeconds: 12,
            // Use a custom cache name for this route.
            cacheName: 'root',
            // Configure custom cache expiration.
            expiration: {
              maxEntries: 150,
              maxAgeSeconds: 60 * 60 * 24 * 3,
            },
            // Configure which responses are considered cacheable.
            cacheableResponse: {
              statuses: [0, 200],
              //        headers: {'x-test': 'true'},
            },
            // Configure the broadcast cache update plugin.
            broadcastUpdate: {
              channelName: 'my-update-channel',
            },
            // Add in any additional plugin logic you need.
            plugins: [
              //        {cacheDidUpdate: () => /* custom plugin code */}
            ],
            // matchOptions and fetchOptions are used to configure the handler.
            fetchOptions: {
              credentials: 'include',
              //mode: 'cors',
              mode: 'same-origin',
            },
            //      matchOptions: {
            //        ignoreSearch: true,
            //      },
          },
        },



        {
           urlPattern: new RegExp('https://cdn.jsdelivr.net'),
        handler: 'cacheFirst',
        options: {
          cacheName: 'cdn',
          expiration: {
            maxAgeSeconds: 60 * 60 * 24 * 365,
            maxEntries: 100,
          },
          cacheableResponse: {
            statuses: [0, 200]
          }
        }
      },
      
      {
        urlPattern: new RegExp('https://cdnjs.cloudflare.com'),
        handler: 'cacheFirst',
        options: {
          cacheName: 'cdn',
          expiration: {
            maxAgeSeconds: 60 * 60 * 24 * 365,
            maxEntries: 100,
          },
          cacheableResponse: {
            statuses: [0, 200]
          }
        }
      },
      
      {
        urlPattern: new RegExp('https://cdn.rawgit.com'),
        handler: 'cacheFirst',
        options: {
          cacheName: 'cdn',
          expiration: {
            maxAgeSeconds: 60 * 60 * 24 * 365,
            maxEntries: 100,
          },
          cacheableResponse: {
            statuses: [0, 200]
          }
        }
      },
      
      
      {
        urlPattern: new RegExp('https://unpkg.com'),
        handler: 'cacheFirst',
        options: {
          cacheName: 'cdn',
          expiration: {
            maxAgeSeconds: 60 * 60 * 24 * 365,
            maxEntries: 100,
          },
          cacheableResponse: {
            statuses: [0, 200]
          }
        }
      },
      
      
      
    ],
    
    importScripts: ['./js/push_message.js'],
    
  }
  
  )
  );
  
  
