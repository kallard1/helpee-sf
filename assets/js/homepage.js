require('../scss/homepage.scss')

const $ = require('jquery')
require('popper.js')
require('bootstrap')

$(document).ready(() => {
  $('input[name=search]').on('change', (e) => {
    if ($('#search').is(':checked')) {
      $('#form-search').show();
      $('#form-propose').hide();
    }

    if ($('#propose').is(':checked')) {
      $('#form-search').hide();
      $('#form-propose').show();
    }
  })
})
