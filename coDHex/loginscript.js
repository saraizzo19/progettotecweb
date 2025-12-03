document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');

    // Campi di input
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    // Messaggi di errore
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');

    // Toggle visibilità password
    const passwordToggle = document.getElementById('passwordToggle');

    // Funzione per mostrare/nascondere l'errore
    const showError = (errorElement, show) => {
        if (show) {
            errorElement.classList.add('show');
        } else {
            errorElement.classList.remove('show');
        }
    };

    // --- FUNZIONI DI VALIDAZIONE ---

    const validateEmail = () => {
        // Semplice regex per validazione email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isValid = emailRegex.test(emailInput.value.trim());
        // Aggiorniamo il messaggio di errore per essere più specifici
        emailError.textContent = emailInput.value.trim() === '' ? 'L\'e-mail è obbligatoria' : 'Indirizzo e-mail non valido';
        showError(emailError, !isValid);
        return isValid;
    };

    const validatePassword = () => {
        const isValid = passwordInput.value.trim().length > 0;
        passwordError.textContent = 'La password è obbligatoria';
        showError(passwordError, !isValid);
        return isValid;
    };

    const validateForm = () => {
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();
        return isEmailValid && isPasswordValid;
    };

    // --- GESTIONE TOGGLE PASSWORD ---

    const setupPasswordToggle = (toggleButton, inputField) => {
        toggleButton.addEventListener('click', () => {
            const eyeIcon = toggleButton.querySelector('.eye-icon');
            const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
            inputField.setAttribute('type', type);

            if (type === 'text') {
                eyeIcon.classList.add('show-password');
            } else {
                eyeIcon.classList.remove('show-password');
            }
        });
    };

    setupPasswordToggle(passwordToggle, passwordInput);

    // --- EVENT LISTENERS ---

    emailInput.addEventListener('input', () => showError(emailError, false));
    passwordInput.addEventListener('input', () => showError(passwordError, false));

    emailInput.addEventListener('blur', validateEmail);
    passwordInput.addEventListener('blur', validatePassword);

    // --- GESTIONE INVIO FORM (MODIFICATA CON PHP) ---
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const isFormValid = validateForm();

        if (isFormValid) {
            console.log('Form valido, invio reale al server...');

            // Attiva l'animazione di caricamento sul bottone
            const submitBtn = form.querySelector('.login-btn');
            submitBtn.classList.add('loading');

            // 1. Raccogliamo i dati del form
            const formData = new FormData(form);

            // 2. Inviamo i dati a login.php tramite FETCH
            fetch('login.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text()) // Leggiamo la risposta del server
                .then(data => {

                    // 3. Controlliamo cosa ha risposto il server
                    if (data.includes("Errore")) {
                        // --- CASO ERRORE ---
                        submitBtn.classList.remove('loading'); // Ferma il caricamento

                        // Puliamo il messaggio da eventuali tag HTML e mostriamo l'alert
                        let messaggioPulito = data.replace(/<[^>]*>?/gm, '');
                        alert(messaggioPulito);
                    }
                    else {
                        // --- CASO SUCCESSO ---
                        // Qui manteniamo la tua bella animazione!

                        // Nascondi form e link registrazione
                        form.style.display = 'none';
                        const signupLink = document.querySelector('.signup-link');
                        if(signupLink) signupLink.style.display = 'none';

                        // Mostra messaggio successo (spunta verde)
                        const successMessage = document.getElementById('successMessage');
                        successMessage.classList.add('show');

                        console.log('Login completato! Reindirizzamento...');

                        // Aspettiamo 1.5 secondi per far vedere la spunta verde, poi cambiamo pagina
                        setTimeout(() => {
                            window.location.href = 'pagina_riservata.php';
                        }, 1500);
                    }
                })
                .catch(error => {
                    // Caso errore di rete (server spento, no internet)
                    submitBtn.classList.remove('loading');
                    console.error('Errore:', error);
                    alert("Impossibile connettersi al server. Verifica che XAMPP sia acceso.");
                });

        } else {
            console.log('Form non valido, controlla i campi.');
            const card = document.querySelector('.login-card');
            card.style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => {
                card.style.animation = '';
            }, 500);
        }
    });
});