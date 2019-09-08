const $ = require('jquery');
const select2 = require('select2');

$(document).ready(function() {
  $('.js-data-cities').select2({
    theme: 'bootstrap4',
    ajax: {
      url: '/search/city',
      dataType: 'json',
      delay: 250,
      data: function(params) {
        return {
          q: params.term,
          page: params.page,
        };
      },
      processResults: function(data, params) {
        params.page = params.page || 1;
        return {
          results: data,
          pagination: {
            mode: (params.page * 30) < data.length,
          },
        };
      },
      cache: true,
    },
    escapeMarkup: function(markup) { return markup; },
    minimumResultsForSearch: -1,
    minimumInputLength: 2,
    templateResult: formatRepo,
    templateSelection: formatRepoSelection,
  });
});

function formatRepo(repo) {
  if (repo.loading) {
    return repo.text;
  }

  const $container = $(`
  <div class='select2-result-repository clearfix'>
    <div class='select2-result-repository__meta'>
        <div class='select2-result-repository__title'></div>
      </div>
    </div>`);

  $container.find('.select2-result-repository__title')
      .text(`${repo.zip_code} - ${repo.name}`);

  return $container;
}

function formatRepoSelection(repo) {
  return repo.name || repo.text;
}
