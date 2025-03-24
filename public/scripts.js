document.addEventListener('DOMContentLoaded', function () {
    // Kysymysten muokkaus
    document.getElementById('editQuestionBtn').addEventListener('click', function () {
        const questionDropdown = document.getElementById('questionDropdown');
        const selectedQuestionId = questionDropdown.value;

        if (selectedQuestionId) {
            // Lähetetään valittu kysymys
            alert("Muokkaa kysymystä: " + selectedQuestionId);
            // Tähän voit lisätä toiminnallisuuksia, kuten kysymyksen näyttämisen ja muokkaamisen
        } else {
            alert("Valitse kysymys muokattavaksi.");
        }
    });

    // Käyttäjien tulokset
    document.getElementById('showResultsBtn').addEventListener('click', function () {
        fetch('/results.php')
            .then(response => response.json())
            .then(data => {
                let resultContainer = document.getElementById('content');
                let content = "<h3>Käyttäjien tulokset</h3><table><tr><th>Käyttäjä</th><th>Tulos</th><th>Päivämäärä</th></tr>";
                data.forEach(function (result) {
                    content += `<tr><td>${result.username}</td><td>${result.score}</td><td>${result.created_at}</td></tr>`;
                });
                content += "</table>";
                resultContainer.innerHTML = content;
                resultContainer.classList.remove('hidden-content');
            })
            .catch(error => {
                console.error('Virhe tulosten haussa:', error);
            });
    });

    // Lisää kysymys
    document.getElementById('addQuestionBtn').addEventListener('click', function () {
        alert("Lisää kysymys -nappia klikattu!");
    });
});
