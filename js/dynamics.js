document.addEventListener("DOMContentLoaded", () => {
    const container = document.querySelector(".quiz-container");
    const jeu = container.dataset.jeu;
    const niveau = container.dataset.niveau;
    const title = document.getElementById("quiz-title");
    const block = document.getElementById("question-block");
    const scoreDisplay = document.getElementById("score-display");

    let score = 0;
    let total = 0;
    let timerInterval;
    let timeLeft = 10;
    const maxQuestions = 10;

    function loadQuestion() {
        clearInterval(timerInterval);
        fetch(`question.php?jeu=${jeu}&niveau=${niveau}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    block.innerHTML = "<p>Aucune question trouvée.</p>";
                    return;
                }

                title.textContent = `${data.question.jeu} - Niveau ${niveau}`;

                const html = `
                    <p class="question">${data.question.question}</p>
                    ${data.reponses.map(rep => `
                        <button class="btn-reponse" data-id="${rep.id}">${rep.reponse}</button>
                    `).join("")}
                `;
                block.innerHTML = html;
                startTimer();

                document.querySelectorAll(".btn-reponse").forEach(btn => {
                    btn.addEventListener("click", () => {
                        document.querySelectorAll(".btn-reponse").forEach(b => b.disabled = true);
                        checkAnswer(btn.dataset.id);
                    });
                });
            })
            .catch(error => {
                console.error("Erreur lors du chargement de la question :", error);
            });
    }

    function startTimer() {
        timeLeft = 10;
        const timer = document.getElementById("timer");
        timer.textContent = `⏳ Temps : ${timeLeft}s`;

        timerInterval = setInterval(() => {
            timeLeft--;
            timer.textContent = `⏳ Temps : ${timeLeft}s`;

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                block.innerHTML += `<p class="feedback" style="color: orange;">⏰ Temps écoulé !</p>`;
                document.querySelectorAll(".btn-reponse").forEach(btn => btn.disabled = true);
                total++;
                scoreDisplay.textContent = `Score : ${score} / ${total}`;
                setTimeout(() => {
                    if (total >= maxQuestions) {
                        endQuiz();
                    } else {
                        loadQuestion();
                    }
                }, 1500);
            }
        }, 1000);
    }

    function checkAnswer(reponse_id) {
        fetch("reponse.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `reponse=${reponse_id}`
        })
        .then(res => res.text())
        .then(isCorrect => {
            clearInterval(timerInterval);
            total++;
            const isGood = isCorrect.trim() === "1";
            if (isGood) score++;

            const feedback = document.createElement("p");
            feedback.className = "feedback";
            feedback.textContent = isGood ? "✅ Bonne réponse !" : "❌ Mauvaise réponse...";
            feedback.style.color = isGood ? "limegreen" : "red";
            block.appendChild(feedback);

            scoreDisplay.textContent = `Score : ${score} / ${total}`;

            setTimeout(() => {
                if (total >= maxQuestions) {
                    endQuiz();
                } else {
                    loadQuestion();
                }
            }, 1500);
        });
    }

    function endQuiz() {
        block.innerHTML = `<p class="final-score">Quiz terminé !<br>Score : ${score} / ${total}</p>`;
        scoreDisplay.innerHTML = "";
        document.getElementById("timer").textContent = "";

        const sessionPseudo = container.dataset.pseudo;
        const pseudo = sessionPseudo || prompt(`Quiz terminé ! Score : ${score}/${total}\n\nEntre ton pseudo pour sauvegarder ton score :`);
        if (!pseudo || pseudo.trim() === "") return;

        fetch("save_score.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `jeu=${encodeURIComponent(jeu)}&niveau=${encodeURIComponent(niveau)}&score=${score}&pseudo=${encodeURIComponent(pseudo.trim())}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                block.innerHTML += `<p class="feedback" style="color: limegreen;">✅ Score sauvegardé pour <strong>${pseudo.trim()}</strong> !</p>
                    <p><a href="score.php" style="color: cyan;">Voir le classement →</a></p>`;
            }
        })
        .catch(() => {});
    }

    loadQuestion();
});
