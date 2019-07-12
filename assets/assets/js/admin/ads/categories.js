const label = document.getElementById('label');
const slug = document.getElementById('slug');

label.addEventListener('keyup', () => {
  let { value } = label;

  value = value.toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '');

  slug.value = value.replace(/[^a-z0-9]+/ig, '-');
});
