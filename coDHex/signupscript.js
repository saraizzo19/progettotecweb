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
        showError(confirmPasswordError, !passwordsMatch && isNotEmpty);
        return isValid;
    };

    const validateForm = () => {
        const isNameValid = validateName();
        const isSurnameValid = validateSurname();
        const isDateValid = validateDate();
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();
        const isConfirmPasswordValid = validateConfirmPassword();
        return isNameValid && isSurnameValid && isDateValid && isEmailValid && isPasswordValid && isConfirmPasswordValid;
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
    setupPasswordToggle(confirmPasswordToggle, confirmPasswordInput);

    // --- EVENT LISTENERS (Input & Blur) ---
    nameInput.addEventListener('input', () => showError(nameError, false));
    surnameInput.addEventListener('input', () => showError(surnameError, false));
    dateInput.addEventListener('input', () => showError(dateError, false));
    emailInput.addEventListener('input', () => showError(emailError, false));
    passwordInput.addEventListener('input', () => {
        showError(passwordError, false);
        if (confirmPasswordInput.value) validateConfirmPassword();
    });
    confirmPasswordInput.addEventListener('input', () => showError(confirmPasswordError, false));

    nameInput.addEventListener('blur', validateName);
    surnameInput.addEventListener('blur', validateSurname);
    dateInput.addEventListener('blur', validateDate);
    emailInput.addEventListener('blur', validateEmail);
    passwordInput.addEventListener('blur', validatePassword);
    confirmPasswordInput.addEventListener('blur', validateConfirmPassword);


    // --- GESTIONE INVIO FORM (MODIFICATA) ---
    form.addEventListener('submit', (e) => {
        e.preventDefault(); // Ferma il ricaricamento della pagina

        const isFormValid = validateForm();

        if (isFormValid) {
            console.log('Form valido, invio reale al server...');

            // Mostra animazione di caricamento
            const submitBtn = form.querySelector('.login-btn');
            submitBtn.classList.add('loading');

            // 1. Raccogliamo i dati del form
            const formData = new FormData(form);

            // 2. Usiamo FETCH per inviare i dati a signup.php


            fetch('signup.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    submitBtn.classList.remove('loading');

                    // Se il PHP risponde con un errore
                    if (data.includes("Errore")) {
                        // Pulizia del messaggio HTML per l'alert
                        let messaggioPulito = data.replace(/<[^>]*>?/gm, '');
                        alert(messaggioPulito);
                    }
                    else {
                        // --- SUCCESSO! ---
                        // Qui avviene il reindirizzamento
                        window.location.href = "pagina_riservata.php";
                    }
                })

                .catch(error => {
                    submitBtn.classList.remove('loading');
                    console.error('Errore di connessione:', error);
                    alert("C'è stato un problema di connessione al server.");
                });

        } else {
            console.log('Form non valido');
            // Shake effect
            const card = document.querySelector('.login-card');
            card.style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => { card.style.animation = ''; }, 500);
        }
    });
});