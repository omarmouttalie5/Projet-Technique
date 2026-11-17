const $ = id => document.getElementById(id);
const form = $('form-categorie'), section = $('section-form'), body = $('table-categories-body');
const toast = $('toast'), submit = $('btn-submit'), api = 'backend/api.php';

$('btn-open-sidebar').addEventListener('click', () => $('sidebar').classList.remove('hidden'));
$('btn-close-sidebar').addEventListener('click', () => $('sidebar').classList.add('hidden'));
$('btn-show-form').addEventListener('click', () => section.classList.toggle('hidden'));
$('btn-cancel-form').addEventListener('click', () => section.classList.add('hidden'));

function notify(message, error = false) {
  toast.textContent = message;
  toast.className = `fixed bottom-5 right-5 z-50 px-4 py-3 text-white ${error ? 'bg-red-600' : 'bg-green-600'}`;
  setTimeout(() => toast.classList.add('hidden'), 3000);
}

async function loadProducts() {
  body.innerHTML = '<tr><td colspan="3" class="p-4 text-center">Loading...</td></tr>';
  try {
    const products = await (await fetch(api)).json();
    body.innerHTML = products.length ? products.map(p => `<tr><td class="p-4">${p.id}</td><td class="p-4">${p.name}</td><td class="p-4">${p.price} $ <span class="ml-2 text-xs font-bold bg-blue-100 rounded p-2">Size: ${p.size}</span></td></tr>`).join('')
      : '<tr><td colspan="3" class="p-4 text-center">No products found.</td></tr>';
  } catch (error) {
    console.error('Error loading products:', error);
    notify('Error loading products', true);
  }
}

form.addEventListener('submit', async event => {
  event.preventDefault();
  const payload = {
    name: $('cat-nom').value,
    price: $('cat-couleur').value,
    size: $('cat-icone').value
  };
  submit.disabled = true;
  submit.textContent = 'Saving...';
  try {
    const response = await fetch(api, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const result = await response.json();
    if (!response.ok || !result.success) throw new Error(result.message || 'Error');
    form.reset();
    section.classList.add('hidden');
    notify('Product added successfully!');
    await loadProducts();
  } catch (error) {
    console.error('Error submitting form:', error);
    notify(error.message || 'Server error', true);
  } finally {
    submit.disabled = false;
    submit.textContent = 'Save';
  }
});

loadProducts();
