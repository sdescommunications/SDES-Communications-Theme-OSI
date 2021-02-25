import orgInfo from './quiz_org_info.js';

// The number of questions in the quiz,
// used to determine when the user has completed the quiz.
const quizLength = 4;

// Shuffle results to increase variety of responses
const shuffle = (arr) => {
    let j, x, i;
    for (i = arr.length - 1; i > 0; i--) {
        j = Math.floor(Math.random() * (i + 1));
        x = arr[i];
        arr[i] = arr[j];
        arr[j] = x;
    }
    return arr;
};

export default class QuizHandler {
    constructor() {
        this.jumboList = [];
        this.choiceList = [];
        //this.form = null;
        this.results = [];
    }

    /**
     * Initialize and activate the quiz
     */
    init() {
        // Gather list of Jumbotrons to scroll to
        this.jumboList = document.querySelectorAll('.jumbotron');

        // Gather list of choice elements
        this.choiceList = document.querySelectorAll('.quiz-choice');

        // Get form element
        //this.form = document.querySelector('#consultationForm');

        // Set event handlers
        this.bindEvents();
    }

    /**
     * Set up event handlers
     */
    bindEvents() {
        // Add click handlers to all quiz choices
        this.choiceList.forEach((e) => {
            e.addEventListener('click', () => this.handleChoiceSelection(e));
        });

        // Form submission
        //this.form.addEventListener('submit', () => this.submitForm());
    }

    /**
     * Handle form submission
     
    submitForm() {
        // Fetch form data
        const userName = document.getElementById('nameInput').value;
        const userEmail = document.getElementById('emailInput').value;
        const userYear = document.getElementById('yearInput').value;

        // Prepare mailto link
        const href = `mailto:Rahsaan.Graham@ucf.edu?cc=${userEmail}&subject=AmeriCorps Appointment&body=Hello! I am a ${userYear}, and I got '${this.results[0].name}' as my result on the AmeriCorps Quiz. Could I schedule an appointment to learn more about it and my other options to get involved? Sincerely, ${userName}`;

        // Open mailto
        window.location.href = href;

        // Prevent default form submission behavior
        return false;
    }*/

    /**
     * Respond to the user selecting a choice
     */
    handleChoiceSelection(element) {
        // Find parent question ID
        const parentQuestion = element.closest('.unstyled-list');
        const questionID = parentQuestion.dataset.quizQuestion;

        // Disable all choices for this question
        parentQuestion.querySelectorAll('.quiz-choice').forEach((e) => {
            e.classList.remove('active');
            e.classList.add('disabled');
        });

        // Activate only the selected choice
        element.classList.remove('disabled');
        element.classList.add('active');

        // Check if the quiz is completed
        const answeredQuestions = document.querySelectorAll('.active').length;
        if (answeredQuestions >= quizLength) {
            // Show the results
            this.showResult();
        } else {
            // Smoothly scroll to the next question
            const nextQuestion = this.jumboList[questionID];
            nextQuestion.scrollIntoView({
                behavior: 'smooth',
            });
        }
    }

    /**
     * Tallies user results and returns the info on the
     * highest scoring organizations as an array.
     *
     * Multiple orgs are returned if there is a tie.
     */
    getResults() {
        // Initialize results object
        let results = [];
        orgInfo.forEach((info) => {
            results.push({ ...info, score: 0 });
        });

        // Record user selections
        let userChoices = [];
        document.querySelectorAll('.active').forEach((e) => {
            userChoices.push(e.dataset.choiceValue);
        });

        // Tally scores from each choice
        userChoices.forEach((choiceValue, choiceIndex) => {
            // Check for a match in the index of each organization's
            // choice array that corresponds to this choice
            orgInfo.forEach((org, orgIndex) => {
                if (org.scores[choiceIndex].includes(choiceValue)) {
                    results[orgIndex].score += 1;
                }
            });
        });

        // Keep only the highest scoring orgs
        let topScore = 0;
        for (const res of results) {
            topScore = Math.max(res.score, topScore);
        }
        const filteredResults = results.filter((e) => e.score === topScore);

        // Shuffle the results for increased variation
        const shuffledResults = shuffle(filteredResults);

        this.results = shuffledResults;
    }

    /**
     * Display and scroll to the result of the completed quiz
     */
    showResult() {
        this.getResults();

        // Add content to results container
        const resultsContainer = document.querySelector('#resultsContainer');
        const resultBox = document.querySelector('.result');
        resultBox.innerHTML = `
            <h1 class="mb-4">
                <p>Here is your result!</p>
                <p>You matched with <i>${this.results[0].name}</i></p>
            </h1>

            <p>${this.results[0].info}<p>
            <p class="text-center">
                <a class="my-3 mx-auto btn btn-primary btn-lg" href="${this.results[0].website}" target="_blank">
                    <strong>Visit Website</strong>
                </a>
            </p>
        `;

        // Add accordion with extra results
        if (this.results.length > 1) {
            let addlResults = '';
            this.results.forEach((r, i) => {
                addlResults += `
                    <li>
                        <a class="collapsed" href="#collapseInfo-${i}" data-toggle="collapse" role="button" aria-expanded="false" id="info-${i}">
                            <h4><strong><u>${r.name}</u></strong></h4>
                        </a>
                    </li>
                    <p class="collapse text-center" id="collapseInfo-${i}">
                        ${r.info}<br />
                        <a class="btn btn-primary btn-lg my-3 mx-auto text-center" href="${r.website}" target="_blank"><strong>Visit Website</strong></a>
                    </p>
                `;
            });
            resultBox.innerHTML += `
                <hr class="my-4">
                <h2><strong>You also matched with:</strong></h2>
                <ul id="accordion">
                    ${addlResults}
                </ul>
                <br />
            `;
        }

        // Make results visible
        resultsContainer.classList.remove('hidden');

        // Scroll to results
        resultBox.scrollIntoView({
            behavior: 'smooth',
        });
    }
}
