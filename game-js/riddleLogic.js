document.addEventListener("DOMContentLoaded", function () {
    // DOM Elements
    const wordDisplay = document.getElementById("word-display");
    const collectButton = document.getElementById("submit-btn");
    const userInput = document.getElementById("user-input");
    const scoreDisplay = document.getElementById("coins");
    const choicesContainer = document.getElementById("choices-container");

    // Game state variables
    let currentUserIndex = 0;
    let shuffledIndices = [];
    let usedIndices = new Set();
    let userScore = 0;

    const style = document.createElement('style');
    style.textContent = `
            @keyframes letterAppear {
                0% {
                    opacity: 0;
                    transform: translateY(10px);
                }
                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .letter-animation {
                display: inline-block;
                animation: letterAppear 0.3s ease forwards;
                opacity: 0;
            }
        `;
    document.head.appendChild(style);

    // Initialize the game
    function initGame() {
        // Try to load saved state
        const savedState = sessionStorage.getItem('riddleGameState');

        if (savedState) {
            // Restore game state
            const state = JSON.parse(savedState);
            shuffledIndices = state.shuffledIndices;
            usedIndices = new Set(state.usedIndices);
            currentUserIndex = state.currentUserIndex;

            displayCurrentQuestion();
        } else {
            shuffleQuestions();
            changeQuestion();
        }

        fetchUserProgress();
    }

    function saveGameState() {
        const state = {
            shuffledIndices: shuffledIndices,
            usedIndices: Array.from(usedIndices),
            currentUserIndex: currentUserIndex
        };
        sessionStorage.setItem('riddleGameState', JSON.stringify(state));
    }

    // Display current question (without changing)
    function displayCurrentQuestion() {
        const currentWord = words[currentUserIndex];
        animateTextChange(wordDisplay, currentWord.text);
        displayChoices(currentWord.choices);
    }

    // Handle user input
    collectButton.addEventListener("click", async function () {
        const userAnswer = userInput.value.trim();
        const correctAnswer = words[currentUserIndex].answer;

        if (userAnswer.toUpperCase() === correctAnswer.toUpperCase()) {
            CorrectSaveProgress();
            userInput.value = "";
            changeQuestion();
        } else {
            userInput.value = "";
            await Swal.fire({
                icon: "error",
                text: `Incorrect answer. Try another`,
                width: "250px",
            });
            changeQuestion();
        }
    });

    // Save user progress to the server if correct
    function CorrectSaveProgress() {
        fetch(`DB_DATA/riddleSectionProgress.php`, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `acc_number=${encodeURIComponent(acc_number)}&email=${encodeURIComponent(userEmail)}&score=${encodeURIComponent(1)}`,
        })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    fetchUserProgress();
                } else {
                    console.error("Failed to save progress:", data.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
    }


    // Fetch and display user progress from the server
    function fetchUserProgress() {
        fetch(`DB_DATA/coinbased.php?acc_number=${encodeURIComponent(acc_number)}&email=${encodeURIComponent(userEmail)}`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    userScore = data.earned_coins;
                    scoreDisplay.textContent = userScore;
                } else {
                    console.error("Failed to fetch progress:", data.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
    }

    // Shuffle questions
    function shuffleQuestions() {
        shuffledIndices = Array.from({ length: words.length }, (_, i) => i);
        for (let i = shuffledIndices.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [shuffledIndices[i], shuffledIndices[j]] = [shuffledIndices[j], shuffledIndices[i]];
        }
    }

    // Style choice buttons
    function styleChoiceButtons() {
        const buttons = document.querySelectorAll(".choice-btn");
        buttons.forEach(button => {
            // Base styles
            button.style.cssText = `
                border: 2px solid #3a3aff;
                border-radius: 10px;
                padding: 5px 10px;
                margin: 5px;
                font-size: 14px;
                background-color: #f0f8ff;
                color: #333;
                cursor: pointer;
                transition: all 0.3s ease;
                min-width: 60px;
                text-align: center;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            `;

            // Hover effects
            button.addEventListener("mouseenter", () => {
                button.style.transform = "translateY(-2px)";
                button.style.boxShadow = "0 4px 8px rgba(0,0,0,0.2)";
                button.style.backgroundColor = "#e0f7ff";
            });

            button.addEventListener("mouseleave", () => {
                if (!button.classList.contains("selected")) {
                    button.style.transform = "translateY(0)";
                    button.style.boxShadow = "0 2px 5px rgba(0,0,0,0.1)";
                    button.style.backgroundColor = "#f0f8ff";
                }
            });

            // Click effects
            button.addEventListener("click", function () {
                // Remove selected class from all buttons
                buttons.forEach(btn => {
                    btn.classList.remove("selected");
                    btn.style.backgroundColor = "#f0f8ff";
                    btn.style.transform = "translateY(0)";
                    btn.style.boxShadow = "0 2px 5px rgba(0,0,0,0.1)";
                });

                // Add selected class to clicked button
                this.classList.add("selected");
                this.style.backgroundColor = "#d0e0ff";
                this.style.boxShadow = "0 4px 10px rgba(0,0,0,0.3)";
                this.style.transform = "translateY(-2px)";
            });
        });
    }

    // Change to the next question and display choices
    function changeQuestion() {
        if (usedIndices.size >= words.length) {
            usedIndices.clear();
            shuffleQuestions();
            sessionStorage.removeItem('riddleGameState');
        }

        if (shuffledIndices.length === 0) {
            shuffleQuestions();
        }

        let nextIndex;
        do {
            if (shuffledIndices.length === 0) shuffleQuestions();
            nextIndex = shuffledIndices.pop();
        } while (usedIndices.has(nextIndex) && usedIndices.size < words.length);

        usedIndices.add(nextIndex);
        currentUserIndex = nextIndex;

        const currentWord = words[currentUserIndex];
        animateTextChange(wordDisplay, currentWord.text);
        displayChoices(currentWord.choices);

        saveGameState();
    }


    function animateTextChange(element, newText) {
        element.innerHTML = ''; // Clear current content
        const container = document.createElement('div');
        container.className = 'word-display-container';
        element.appendChild(container);

        // Split text into words to preserve spacing
        const words = newText.split(/(\s+)/);

        words.forEach((word, wordIndex) => {
            if (word === ' ') {
                container.appendChild(document.createTextNode('\u00A0'));
                return;
            }

            const wordSpan = document.createElement('span');
            wordSpan.style.display = 'inline-block';
            wordSpan.style.marginRight = '0.3em';

            const letters = word.split('');
            letters.forEach((letter, letterIndex) => {
                const span = document.createElement('span');
                span.textContent = letter;
                span.className = 'letter-animation';
                span.style.animationDelay = `${(wordIndex * 0.2) + (letterIndex * 0.08)}s`;
                wordSpan.appendChild(span);
            });

            container.appendChild(wordSpan);
        });
    }

    // Display answer choices
    function displayChoices(choices) {
        choicesContainer.innerHTML = ''; // Clear previous choices

        choices.forEach(choice => {
            const choiceElement = document.createElement('button');
            choiceElement.className = 'choice-btn';
            choiceElement.textContent = choice;
            choiceElement.addEventListener('click', () => {
                userInput.value = choice;
            });
            choicesContainer.appendChild(choiceElement);
        });

        // Apply styling after creating buttons
        styleChoiceButtons();
    }

    // Start the game
    initGame();
});