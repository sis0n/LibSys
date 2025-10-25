document.addEventListener('DOMContentLoaded', () => {
    const statusBtn = document.getElementById('statusFilterBtn');
    const statusMenu = document.getElementById('statusFilterMenu');
    const statusValue = document.getElementById('statusFilterValue');

    // Toggle dropdown
    statusBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        statusMenu.classList.toggle('hidden');
    });

    // Click outside to close
    document.addEventListener('click', () => {
        statusMenu.classList.add('hidden');
    });

    // Select dropdown item
    statusMenu.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', () => {
            statusValue.textContent = item.dataset.value;
            statusMenu.classList.add('hidden');
        });
    });
});