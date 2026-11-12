(() => {
    const modal = document.getElementById('deleteConfirmModal');
    const item = document.getElementById('deleteConfirmItem');
    const cancelButton = modal?.querySelector('[data-delete-cancel]');
    const confirmButton = modal?.querySelector('[data-delete-submit]');

    if (!modal || !item || !cancelButton || !confirmButton) {
        return;
    }

    let pendingForm = null;
    let trigger = null;

    const closeModal = () => {
        modal.hidden = true;
        pendingForm = null;
        trigger?.focus();
        trigger = null;
    };

    document.addEventListener('submit', (event) => {
        const form = event.target.closest('form[data-delete-confirm]');

        if (!form) {
            return;
        }

        event.preventDefault();
        pendingForm = form;
        trigger = form.querySelector('button[type="submit"]');
        item.textContent = form.dataset.deleteName || 'data ini';
        modal.hidden = false;
        cancelButton.focus();
    });

    cancelButton.addEventListener('click', closeModal);
    confirmButton.addEventListener('click', () => {
        if (pendingForm) {
            HTMLFormElement.prototype.submit.call(pendingForm);
        }
    });

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.hidden) {
            closeModal();
        }
    });
})();
