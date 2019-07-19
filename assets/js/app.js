/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../scss/app.scss');

global.$ = global.jQuery = require('jquery');
require('bootstrap')

let enabled = false
$(document).ready(() => {
  $('.navbar-toggler').on('click', () => {
    enabled = !enabled

    if (enabled) {
      $('.overlay').addClass('active');
    } else {
      $('.overlay').removeClass('active');
    }
  });
});
//
// const buttonBurger = document.getElementById('burger-icon')
// buttonBurger.onclick = active
//
// function active () {
//   const burgerContainer = document.getElementById('burger-nav')
//   const element = document.querySelector('#burger-nav')
//   if (element.classList.contains('active')) {
//     burgerContainer.classList.remove('active')
//     burgerContainer.classList.add('inactive')
//   } else {
//     burgerContainer.classList.remove('inactive')
//     burgerContainer.classList.add('active')
//   }
// }
