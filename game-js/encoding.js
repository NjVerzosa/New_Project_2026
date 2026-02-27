document.addEventListener("DOMContentLoaded", function () {
    const numberDisplay = document.getElementById("number-display");
    const scoreDisplay = document.getElementById("coins");
    const userInput = document.getElementById("user-input");
    const keyboard = document.createElement('div');
    keyboard.className = 'container mt-2 keyboard-container';
    keyboard.style.backgroundSize = 'cover';
    keyboard.style.backgroundPosition = 'center';
    keyboard.style.backgroundRepeat = 'no-repeat';
    keyboard.style.borderRadius = '15px';
    keyboard.style.padding = '20px';
    keyboard.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';

    // Set default background (optional)
    keyboard.style.backgroundImage = 'url("images/keyboard.jpg")';
    keyboard.innerHTML = `
        <div class="row g-2 justify-content-center">
            <div class="col-4">
                <button class="key text-white btn btn-outline-primary btn-lg w-100 py-3" data-key="1">1</button>
            </div>
            <div class="col-4">
                <button class="key text-white btn btn-outline-primary btn-lg w-100 py-3" data-key="2">2</button>
            </div>
            <div class="col-4">
                <button class="key text-white btn btn-outline-primary btn-lg w-100 py-3" data-key="3">3</button>
            </div>
        </div>
        <div class="row g-2 justify-content-center mt-2">
            <div class="col-4">
                <button class="key text-white btn btn-outline-primary btn-lg w-100 py-3" data-key="4">4</button>
            </div>
            <div class="col-4">
                <button class="key text-white btn btn-outline-primary btn-lg w-100 py-3" data-key="5">5</button>
            </div>
            <div class="col-4">
                <button class="key text-white btn btn-outline-primary btn-lg w-100 py-3" data-key="6">6</button>
            </div>
        </div>
        <div class="row g-2 justify-content-center mt-2">
            <div class="col-4">
                <button class="key text-white btn btn-outline-primary btn-lg w-100 py-3" data-key="7">7</button>
            </div>
            <div class="col-4">
                <button class="key text-white btn btn-outline-primary btn-lg w-100 py-3" data-key="8">8</button>
            </div>
            <div class="col-4">
                <button class="key text-white btn btn-outline-primary btn-lg w-100 py-3" data-key="9">9</button>
            </div>
        </div>
        <div class="row g-2 justify-content-center mt-2">
            <div class="col-8">
                <button class="key text-white btn btn-outline-primary btn-lg w-100 py-3" data-key="0">0</button>
            </div>
            <div class="col-4">
                <button id="backspace" class="key text-white btn btn-outline-danger btn-lg w-100 py-3">⌫</button>
            </div>
        </div>
    `;

    // Insert keyboard after input field
    userInput.insertAdjacentElement('afterend', keyboard);

    // Ensure required variables exist
    if (!userId || !userEmail) {
        console.error("userId, userEmail is undefined.");
        return;
    }

    let currentNumber = generateRandomNumber();
    let lastKeystrokeTime = 0;
    let userScore = 0;

    // Fetch user progress
    fetchUserProgress();
    displayNumber();

    userInput.addEventListener('focus', (e) => {
        e.target.blur();
    });

    // Handle virtual keyboard input
    document.querySelectorAll('.key').forEach(key => {
        key.addEventListener('click', function () {
            const keyValue = this.dataset.key;
            const currentTime = new Date().getTime();
            const timeDiff = currentTime - lastKeystrokeTime;

            if (this.id === 'backspace') {
                userInput.value = userInput.value.slice(0, -1);
            } else if (userInput.value.length < 7) {
                userInput.value += keyValue;

                if (timeDiff < 100 && userInput.value.length > 0 && Math.random() < 0.4) {
                    const randomIndex = Math.floor(Math.random() * userInput.value.length);
                    const charToDuplicate = userInput.value[randomIndex] || "";
                    userInput.value = userInput.value.substring(0, randomIndex) +
                        charToDuplicate +
                        userInput.value.substring(randomIndex);
                }

                if (userInput.value.length === 7) {
                    checkAnswer();
                }
            }

            lastKeystrokeTime = currentTime;
        });
    });

    // Restrict physical keyboard to numbers only
    userInput.addEventListener('keydown', function (e) {
        if (!/[0-9]|Backspace|Arrow|Delete/.test(e.key) && !e.ctrlKey) {
            e.preventDefault();
        }
    });

    // Your existing functions
    function generateRandomNumber() {
        return Math.floor(1000000 + Math.random() * 9000000);
    }

    function applyDiceEffect(number) {
        const chars = number.toString().split("");
        const container = document.createElement('div');

        // Create rolling animation for each digit
        chars.forEach((char, index) => {
            const digitContainer = document.createElement('div');
            digitContainer.style.display = 'inline-block';
            digitContainer.style.width = '30px';
            digitContainer.style.height = '30px';
            digitContainer.style.lineHeight = '30px';
            digitContainer.style.textAlign = 'center';
            digitContainer.style.margin = '0 2px';
            digitContainer.style.border = '1px solid #666';
            digitContainer.style.borderRadius = '4px';
            digitContainer.style.backgroundColor = 'rgb(3, 40, 77)';
            digitContainer.style.boxShadow = '0 1px 3px rgba(0,0,0,0.2)';
            digitContainer.style.overflow = 'hidden';
            digitContainer.style.position = 'relative';

            // Create rolling animation
            const roller = document.createElement('div');
            roller.style.position = 'absolute';
            roller.style.width = '100%';
            roller.style.transition = 'transform 0.5s ease-out';

            // Generate random numbers for rolling effect
            const randomNumbers = [];
            for (let i = 0; i < 5; i++) {
                randomNumbers.push(Math.floor(Math.random() * 10));
            }
            randomNumbers.push(char); // The actual number at the end

            roller.innerHTML = randomNumbers.map(num =>
                `<div style="height:30px;">${num}</div>`
            ).join('');

            digitContainer.appendChild(roller);
            container.appendChild(digitContainer);

            // Trigger the roll animation after a slight delay for each digit
            setTimeout(() => {
                const finalPosition = -(randomNumbers.length - 1) * 30;
                roller.style.transform = `translateY(${finalPosition}px)`;
            }, index * 100);
        });

        // Clear previous content and add new animated digits
        numberDisplay.innerHTML = '';
        numberDisplay.appendChild(container);
    }


    function displayNumber() {
        currentNumber = generateRandomNumber();
        applyDiceEffect(currentNumber);
        userInput.value = "";
    }

    function fetchUserProgress() {
        fetch(`./includes/coinbased.php?acc_number=${encodeURIComponent(acc_number)}&email=${encodeURIComponent(userEmail)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    userScore = data.earned_coins;
                    scoreDisplay.textContent = userScore;
                }
            })
            .catch(console.error);
    }

    function CorrectNumber() {
        fetch(`./includes/numberSectionProgress.php`, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `acc_number=${encodeURIComponent(acc_number)}&email=${encodeURIComponent(userEmail)}&score=${encodeURIComponent(1)}`,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    userScore += 0.25;
                    fetchUserProgress();
                }
            })
            .catch(console.error);
    }


    function checkAnswer() {
        if (userInput.value === currentNumber.toString()) {
            CorrectNumber();
        }
        displayNumber();
        scoreDisplay.textContent = userScore;
    }
});