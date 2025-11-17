
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

    // Gestione invio form
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const isFormValid = validateForm();

        if (isFormValid) {
            console.log('Form valido, invio in corso...');
            const submitBtn = form.querySelector('.login-btn');
            submitBtn.classList.add('loading');

            setTimeout(() => {
                // Nascondi form e link registrazione
                form.style.display = 'none';
                document.querySelector('.signup-link').style.display = 'none';

                // Mostra messaggio successo
                const successMessage = document.getElementById('successMessage');
                successMessage.classList.add('show');

                console.log('Login completato!');
            }, 2000);

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