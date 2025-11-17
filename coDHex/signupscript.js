document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('signupForm');

    // Campi di input
    const nameInput = document.getElementById('name');
    const surnameInput = document.getElementById('surname');
    const dateInput = document.getElementById('date');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');

    // Messaggi di errore
    const nameError = document.getElementById('nameError');
    const surnameError = document.getElementById('surnameError');
    const dateError = document.getElementById('dateError');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');

    // Toggle visibilità password
    const passwordToggle = document.getElementById('passwordToggle');
    const confirmPasswordToggle = document.getElementById('confirmPasswordToggle');

    // Funzione per mostrare/nascondere l'errore
    const showError = (errorElement, show) => {
        if (show) {
            errorElement.classList.add('show');
        } else {
            errorElement.classList.remove('show');
        }
    };

    // --- FUNZIONI DI VALIDAZIONE ---

    const validateName = () => {
        const isValid = nameInput.value.trim() !== '';
        showError(nameError, !isValid);
        return isValid;
    };

    const validateSurname = () => {
        const isValid = surnameInput.value.trim() !== '';
        showError(surnameError, !isValid);
        return isValid;
    };

    const validateDate = () => {
        const isValid = dateInput.value.trim() !== '';
        showError(dateError, !isValid);
        return isValid;
    };

    const validateEmail = () => {
        // Semplice regex per validazione email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isValid = emailRegex.test(emailInput.value.trim());
        showError(emailError, !isValid);
        return isValid;
    };

    const validatePassword = () => {
        const isValid = passwordInput.value.trim().length >= 8;
        showError(passwordError, !isValid);
        return isValid;
    };

    const validateConfirmPassword = () => {
        const passwordsMatch = passwordInput.value.trim() === confirmPasswordInput.value.trim();
        const isNotEmpty = confirmPasswordInput.value.trim() !== '';

        const isValid = passwordsMatch && isNotEmpty;

        // Mostra errore solo se il campo non è vuoto e le password non corrispondono
        showError(confirmPasswordError, !passwordsMatch && isNotEmpty);
        return isValid;
    };

    const validateForm = () => {
        // Esegui tutte le validazioni
        const isNameValid = validateName();
        const isSurnameValid = validateSurname();
        const isDateValid = validateDate();
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();
        const isConfirmPasswordValid = validateConfirmPassword();

        // Ritorna true solo se tutti i campi sono validi
        return isNameValid && isSurnameValid && isDateValid && isEmailValid && isPasswordValid && isConfirmPasswordValid;
    };

    // --- GESTIONE TOGGLE PASSWORD ---

    const setupPasswordToggle = (toggleButton, inputField) => {
        toggleButton.addEventListener('click', () => {
            const eyeIcon = toggleButton.querySelector('.eye-icon');
            const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
            inputField.setAttribute('type', type);

            // Cambia l'icona
            if (type === 'text') {
                eyeIcon.classList.add('show-password');
            } else {
                eyeIcon.classList.remove('show-password');
            }
        });
    };

    // Attiva entrambi i toggle
    setupPasswordToggle(passwordToggle, passwordInput);
    setupPasswordToggle(confirmPasswordToggle, confirmPasswordInput);


    // --- EVENT LISTENERS ---

    // Aggiungi listener per validazione in tempo reale (mentre l'utente scrive)
    // per nascondere l'errore non appena inizia a correggere
    nameInput.addEventListener('input', () => showError(nameError, false));
    surnameInput.addEventListener('input', () => showError(surnameError, false));
    dateInput.addEventListener('input', () => showError(dateError, false));
    emailInput.addEventListener('input', () => showError(emailError, false));
    passwordInput.addEventListener('input', () => {
        showError(passwordError, false);
        // Se la conferma password era già stata inserita, rivalida
        if (confirmPasswordInput.value) {
            validateConfirmPassword();
        }
    });
    confirmPasswordInput.addEventListener('input', () => showError(confirmPasswordError, false));

    // Aggiungi listener per validazione "on blur" (quando l'utente esce dal campo)
    nameInput.addEventListener('blur', validateName);
    surnameInput.addEventListener('blur', validateSurname);
    dateInput.addEventListener('blur', validateDate);
    emailInput.addEventListener('blur', validateEmail);
    passwordInput.addEventListener('blur', validatePassword);
    confirmPasswordInput.addEventListener('blur', validateConfirmPassword);


    // Gestione invio form
    form.addEventListener('submit', (e) => {
        e.preventDefault(); // Impedisci l'invio tradizionale del form

        const isFormValid = validateForm();

        if (isFormValid) {
            console.log('Form valido, invio in corso...');

            // Mostra animazione di caricamento sul pulsante
            const submitBtn = form.querySelector('.login-btn');
            submitBtn.classList.add('loading');

            // Simula un invio di rete (es. 2 secondi)
            setTimeout(() => {
                // Nascondi il form
                form.style.display = 'none';

                // Mostra il messaggio di successo
                const successMessage = document.getElementById('successMessage');
                successMessage.classList.add('show');

                console.log('Registrazione completata!');
            }, 2000);

        } else {
            console.log('Form non valido, controlla i campi.');
            // Scuoti il form per indicare l'errore
            const card = document.querySelector('.login-card');
            card.style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => {
                card.style.animation = '';
            }, 500);
        }
    });
});