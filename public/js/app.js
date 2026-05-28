const commentModal = document.getElementById('commentModal');
const modalTitle = document.getElementById('modalTitle');
const modalPostId = document.getElementById('modalPostId');
const closeModal = document.getElementById('closeModal');
const commentButtons = document.querySelectorAll('[data-post-id]');

commentButtons.forEach(button => {
    button.addEventListener('click', () => {
        const postId = button.getAttribute('data-post-id');
        const postTitle = button.getAttribute('data-post-title');
        modalTitle.textContent = `Comentário para: ${postTitle}`;
        modalPostId.value = postId;
        commentModal.classList.add('active');
        commentModal.setAttribute('aria-hidden', 'false');
    });
});

function hideModal() {
    commentModal.classList.remove('active');
    commentModal.setAttribute('aria-hidden', 'true');
}

closeModal.addEventListener('click', hideModal);
commentModal.addEventListener('click', event => {
    if (event.target === commentModal) {
        hideModal();
    }
});

document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && commentModal.classList.contains('active')) {
        hideModal();
    }
});
