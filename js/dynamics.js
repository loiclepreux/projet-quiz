document.addEventListener("DOMContentLoaded", () => {
    const container = document.querySelector(".quiz-container");
    const jeu = container.dataset.jeu;
    const niveau = container.dataset.niveau;
    const connecte = container.dataset.connecte === "1";
    const block = document.getElementById("question-block");
    const scoreDisplay = document.getElementById("score-display");
    const timerEl = document.getElementById("timer");
    const progression = document.getElementById("progression");

    const DUREE = 10; // secondes par question
    let questions = [];
    let index = 0;
    let score = 0;
    let timerInterval;
    let timeLeft = DUREE;

    // Crée un élément avec du texte (textContent : le texte n'est jamais lu comme du HTML)
    function el(tag, className, text) {
        const node = document.createElement(tag);
        if (className) node.className = className;
        if (text !== undefined) node.textContent = text;
        return node;
    }

    async function post(url, data) {
        const res = await fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams(data),
        });
        return res.json();
    }

    // ===== Démarrage : on charge les 10 questions d'un coup =====
    async function demarrer() {
        try {
            const res = await fetch(`question.php?jeu=${encodeURIComponent(jeu)}&niveau=${encodeURIComponent(niveau)}`);
            const data = await res.json();
            if (!data.success) throw new Error(data.message);
            questions = data.questions;
            afficherQuestion();
        } catch (err) {
            block.innerHTML = "";
            block.appendChild(el("p", "", "Aucune question trouvée pour ce quiz."));
        }
    }

    // ===== Une question =====
    function afficherQuestion() {
        const q = questions[index];
        progression.textContent = `Question ${index + 1} / ${questions.length}`;
        scoreDisplay.textContent = `Score : ${score}`;

        block.innerHTML = "";
        block.appendChild(el("p", "question", q.question));

        q.reponses.forEach((rep) => {
            const btn = el("button", "btn-reponse", rep.reponse);
            btn.dataset.id = rep.id;
            btn.addEventListener("click", () => repondre(rep.id, btn));
            block.appendChild(btn);
        });

        demarrerTimer();
    }

    function demarrerTimer() {
        clearInterval(timerInterval);
        timeLeft = DUREE;
        timerEl.textContent = `⏳ ${timeLeft}s`;

        timerInterval = setInterval(() => {
            timeLeft--;
            timerEl.textContent = `⏳ ${timeLeft}s`;
            if (timeLeft <= 0) {
                repondre("", null); // temps écoulé : réponse vide
            }
        }, 1000);
    }

    // ===== Réponse : c'est le serveur qui dit si c'est juste =====
    async function repondre(reponseId, bouton) {
        clearInterval(timerInterval);
        block.querySelectorAll(".btn-reponse").forEach((b) => (b.disabled = true));

        let message = "";
        let couleur = "";
        try {
            const data = await post("reponse.php", { question: questions[index].id, reponse: reponseId });
            if (!data.success) throw new Error(data.message);

            score = data.score;
            const bonneBtn = block.querySelector(`[data-id="${data.bonne}"]`);
            if (bonneBtn) bonneBtn.classList.add("good");
            if (bouton && !data.juste) bouton.classList.add("bad");

            if (data.juste) {
                message = "✅ Bonne réponse !";
                couleur = "limegreen";
            } else if (bouton) {
                message = "❌ Mauvaise réponse...";
                couleur = "red";
            } else {
                message = "⏰ Temps écoulé !";
                couleur = "orange";
            }
        } catch (err) {
            message = "Erreur de connexion, question suivante.";
            couleur = "orange";
        }

        const feedback = el("p", "feedback", message);
        feedback.style.color = couleur;
        block.appendChild(feedback);
        scoreDisplay.textContent = `Score : ${score}`;

        setTimeout(() => {
            index++;
            if (index < questions.length) {
                afficherQuestion();
            } else {
                terminer();
            }
        }, 1500);
    }

    // ===== Fin de partie =====
    function terminer() {
        timerEl.textContent = "";
        progression.textContent = "";
        scoreDisplay.textContent = "";
        block.innerHTML = "";

        const resultat = el("p", "final-score", "Quiz terminé !");
        resultat.appendChild(document.createElement("br"));
        resultat.appendChild(document.createTextNode(`Score : ${score} / ${questions.length}`));
        block.appendChild(resultat);

        const zone = el("div", "save-zone");
        block.appendChild(zone);

        if (connecte) {
            sauvegarder({}, zone);
        } else {
            // Pas connecté : petit formulaire pour choisir un pseudo
            const form = el("form", "save-form");
            const input = el("input");
            input.placeholder = "Ton pseudo";
            input.maxLength = 30;
            input.required = true;
            const btn = el("button", "btn-nav", "Enregistrer mon score");
            btn.type = "submit";
            form.append(input, btn);
            form.addEventListener("submit", (e) => {
                e.preventDefault();
                sauvegarder({ pseudo: input.value }, zone);
            });
            zone.appendChild(el("p", "", "Entre un pseudo pour apparaître au classement :"));
            zone.appendChild(form);
        }

        const liens = el("p", "end-links");
        const rejouer = el("a", "btn-nav", "Rejouer");
        rejouer.href = "play.php";
        const classement = el("a", "btn-nav", "Voir le classement");
        classement.href = "score.php";
        liens.append(rejouer, classement);
        block.appendChild(liens);
    }

    async function sauvegarder(data, zone) {
        const retour = await post("save_score.php", data).catch(() => ({ success: false, message: "Erreur de connexion." }));
        const msg = el("p", "feedback");
        if (retour.success) {
            zone.innerHTML = "";
            msg.textContent = `✅ Score enregistré pour ${retour.pseudo} !`;
            msg.style.color = "limegreen";
        } else {
            msg.textContent = retour.message;
            msg.style.color = "orange";
            zone.querySelector(".feedback")?.remove();
        }
        zone.appendChild(msg);
    }

    demarrer();
});
