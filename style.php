body {
    font-family: "Segoe UI", Arial, sans-serif;
    background-color: #eaf3fc;
    color: #0d3b66;
    margin: 0;
    padding: 0;
}

.form-container {
    max-width: 600px;
    margin: 15px auto;
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(13, 59, 102, 0.15);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 1.4rem;
}

.question-block {
    margin-bottom: 25px;
}

h3 {
    font-size: 1.1rem;
    margin-bottom: 10px;
    color: #0d3b66;
}

select {
    width: 100%;
    padding: 14px;
    border: 2px solid #0d6efd;
    border-radius: 8px;
    background-color: #f8fbff;
    font-size: 1rem;
    margin-bottom: 20px;
}

label {
    display: block;
    margin: 8px 0;
    padding: 12px;
    border-radius: 8px;
    background-color: #f1f7ff;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s, transform 0.1s;
}

label:hover {
    background-color: #dce9f7;
    transform: scale(1.02);
}

input[type="checkbox"] {
    margin-right: 10px;
    transform: scale(1.3); /* увеличенные чекбоксы для телефона */
}

button {
    width: 100%;
    padding: 16px;
    background-color: #0d6efd;
    color: #ffffff;
    border: none;
    border-radius: 10px;
    font-size: 1.2rem;
    cursor: pointer;
    transition: background 0.3s, transform 0.1s;
}

button:hover {
    background-color: #0b5ed7;
    transform: scale(1.02);
}

/* Мобильная адаптация */
@media (max-width: 600px) {
    .form-container {
        padding: 15px;
        margin: 10px;
    }

    h2 {
        font-size: 1.2rem;
    }

    h3 {
        font-size: 1rem;
    }

    select, label, button {
        font-size: 1rem;
    }

    button {
        padding: 14px;
    }
}
.error-block {
    background-color: #ffe5e5; /* светло-красный фон */
    border: 2px solid #ff4d4d; /* красная рамка */
    border-radius: 8px;
    padding: 10px;
}

.error-message {
    color: #b30000;
    font-weight: bold;
    margin-top: 5px;
    font-size: 0.95rem;
}
.qr-code img {
    margin: 10px 0;
    border: 4px solid #0d6efd;
    border-radius: 8px;
}

.qr-code a {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 15px;
    background-color: #0d6efd;
    color: #fff;
    border-radius: 6px;
    text-decoration: none;
}

.qr-code a:hover {
    background-color: #0b5ed7;
}
.links input {
    border: 1px solid #0d6efd;
    border-radius: 6px;
    font-size: 1rem;
    margin-bottom: 10px;
}

.links button {
    padding: 8px 12px;
    background-color: #0d6efd;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.links button:hover {
    background-color: #0b5ed7;
}
.action-btn {
    display: inline-block;
    margin: 8px;
    padding: 10px 15px;
    background-color: #0d6efd;
    color: #fff;
    border-radius: 6px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    transition: background 0.3s;
}

.action-btn:hover {
    background-color: #0b5ed7;
}
.action-btn {
    display: inline-block;
    margin: 8px;
    padding: 10px 15px;
    background-color: #0d6efd;
    color: #fff;
    border-radius: 6px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    transition: background 0.3s;
}

.action-btn:hover {
    background-color: #0b5ed7;
}
.hint { font-size: 0.85rem; color: #555; display: block; margin-top: 4px; }
