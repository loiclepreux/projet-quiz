const quizCards = document.querySelectorAll('.quiz-card');
    const popup = document.getElementById('difficulty-popup');
    const closePopup = document.getElementById('close-popup');
    const difficultyLinks = document.querySelectorAll('.btn-difficulty');

    let selectedGame = "";

quizCards.forEach(card => {
    card.addEventListener('click', (e) => {
        e.preventDefault();
        selectedGame = card.getAttribute('href').split('=')[1];
        popup.style.display = 'flex';
    });
});

closePopup.addEventListener('click', () => {
    popup.style.display = 'none';
});

window.addEventListener('click', (e) => {
    if (e.target === popup) {
        popup.style.display = 'none';
    }
});

difficultyLinks.forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const level = btn.dataset.level;
      // Redirige vers quiz.php avec jeu & niveau
    window.location.href = `quiz.php?jeu=${selectedGame}&niveau=${level}`;
    });
});