import axios from 'axios';
import _ from 'lodash';

/**
 * Affichage des villes par départements
 * @type {HTMLElement}
 */
const departmentsSelect = document.getElementById('departments');
const citiesSelect = document.getElementById('cities');

departmentsSelect.addEventListener('change', () => getCitiesByDepartments(), false);

function getCitiesByDepartments() {
  citiesSelect.disabled = true;
  clearSelect();
  axios.get(`/cities/get-by-department?department=${departmentsSelect.value}`)
    .then((response) => {
      _.chain(response.data)
        .sortBy(['zip_code', 'name'])
        .map((data) => {
          const opt = document.createElement('option');
          opt.value = data._id;
          opt.innerHTML = `${data.zip_code} - ${data.name}`;
          citiesSelect.appendChild(opt);

          return true;
        })
        .value();
      citiesSelect.disabled = false;
    })
    .catch(e => new Error(e));
}

/**
 * Réinitialisation du select
 */
function clearSelect() {
  citiesSelect.innerHTML = '';
  citiesSelect.innerHTML = '<option disabled selected>Sélectionnez une ville</option>';
}
