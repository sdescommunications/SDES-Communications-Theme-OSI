const QUIZLENGTH = 9;

const ORGINFO = [

    {
        name: "Late Knights",
        info: "Late Knights hosts monthly events that features free food, prizes, and entertainment. The goal of Late Knights is to provide a space that is fun, safe, on-campus, and free of drugs and alcohol. Events are held on campus from 10 p.m. to 1 a.m.",
        link: "https://osi.ucf.edu/agencies/late-knights"
    },

    {
        name: "Knights of the Round Table",
        info: "Knights of the Round Table is an administrative resource to all student organizations and an advisory council to all students pursuing involvement during their collegiate experience. This includes KnightQuest, a one-on-one consultation service designed to help students find an organization on campus, and registering and assigning cubicle and storage space for RSOs.",
        link: "https://osi.ucf.edu/agencies/kort"
    },

    {
        name: "Knight-thon",
        info: "Knight-Thon, Dance Marathon at the University of Central Florida, benefits Orlando Health Arnold Palmer Hospital for Children. We are UCF’s largest student-driven philanthropy and part of the national Miracle Network Dance Marathon movement. Knight-Thon is a yearlong effort in which we raise funds and awareness for our local kids, leading up to our 20-hour celebration in the spring. Last year, our campus joined together to raise over $1.288 million!",
        link: "https://osi.ucf.edu/agencies/knight-thon"
    },

    {
        name: "Campus Activities Board",
        info: "CAB is responsible for the planning and execution of various events on the UCF campus. Our events include large-scale concerts and comedy shows, speaking engagements, the Mr. and Miss UCF Scholarship Pageants, movie screenings, and much more!",
        link: "https://osi.ucf.edu/agencies/cab"
    },

    {
        name: "Knights of the Connection",
        info: "KOTC focuses on serving as a bridge between connection campuses to the Orlando campus. It is dedicated to helping students “Get Involved” while providing quality programs and services at each of UCF’s connector campuses",
        link: "https://osi.ucf.edu/agencies/kotc"
    },

    {
        name: "Volunteer UCF",
        info: "Through a variety of events and programs such as Knights Give Back and the Alternative Break Program, students gain valuable knowledge and experience, become connected to volunteer opportunities of their interest, and expand their community outreach.",
        link: "https://osi.ucf.edu/agencies/vucf"
    },

    {
        name: "Student Engagement",
        info: "Student Engagement is a partnership with OSI, SJA and Valencia College to provide programming, services, student organizations, and student leadership opportunities at the Downtown Campus.",
        link: "https://osi.ucf.edu/agencies/student-engagement"
    },

    {
        name: "Homecoming",
        info: "Homecoming is a fun week that celebrates campus traditions for the campus community. Come and celebrate the black and gold spirit by participating in traditions like: Comedy Knight, Concert Knight, Movie Knight, Knights Got Talent, Spirit Splash, Fireworks Festival, Greek Extravaganza Step Show, and many more.",
        link: "https://osi.ucf.edu/agencies/homecoming"
    },

    {
        name: "Rosen Life",
        info: "Rosen Life serves students at the Rosen College Campus hosting events, programming, and services. These include Rosen’s Get Your Grill On, Rosen’s Got Talent, Mr. & Ms. Hospitality, Relaxation Week and many more.",
        link: "https://osi.ucf.edu/agencies/rosne-life"
    },

    {
        name: "OSI Productions",
        info: "OSI Productions provides high-quality video, photo, and livestream content to various departments and agencies across campus.",
        link: "https://osi.ucf.edu/agencies/osi-productions"
    },

    {
        name: "Knights of the Turntables",
        info: "Knights of the Turntables (KoT) aims to provide the UCF community a fun, low-cost way of bringing DJ, music, and MC services to live events.",
        link: "https://osi.ucf.edu/agencies/kott"
    },

    {
        name: "Black & Gold Studios",
        info: "A professional recording studio available to UCF students. Students can record podcasts, music, and voiceovers based on availability.",
        link: "https://osi.ucf.edu/agencies/black-gold-studios"
    },


    {
        name: "Graduate Outreach",
        info: "Graduate Outreach provides programming, events, and services for Graduate students, connecting students with alumni, faculty, and the community. Events include Monthly First Wednesday Cafe, Mocktail, LinkedIn Photoshoots, and Grad Gathering.",
        link: "https://osi.ucf.edu/agencies/graduate-outreach"
    },

    {
        name: "Design Group",
        info: "Design Group is the team of students that create graphics, illustrations, and promotional material for the Office of Student Involvement. We also handle web design and development for OSI, Student Government, and other organizations. This includes building fun projects like this quiz you just played. :D",
        link: "https://osi.ucf.edu/agencies/design-group"
    },

];

// Bonnieblue would like a dropdown menu for the additional matches for the user
var Quiz = function () {
    var self = this;
    this.init = function () {
        self._bindEvents();
    }

    this._selectAnswer = function ($choice, $question) {
        //Disable all choices
        $question.find(".quiz-choice").removeClass("active");
        $question.find(".quiz-choice").addClass("disabled");

        //Activate selected choice
        $choice.removeClass("disabled");
        $choice.addClass("active");
    }

    //Returns true if each question has a selected response
    this._isComplete = function () {
        let answersChosen = 0;
        $("ul[data-quiz-question]").each(function () {
            if ($(this).find(".quiz-choice.active").length > 0) {
                answersChosen++;
            }
        });
        return (answersChosen >= QUIZLENGTH);
    }

    //Returns array of user responses
    this._tallyResponses = function () {
        let choiceList = new Array();
        $("ul[data-quiz-question]").each(function () {
            choiceList.push($(this).find(".quiz-choice.active").data("choice-value"));
        });
        return choiceList;
    }
    // Answer Key

    this._calcResult = function () {

        // orgsAnswers[0] = Late Knights
        const lk = new Array(["b"], ["a", "b", "c", "d", "e"], ["a", "c"], ["a"], ["a", "c", "f"], ["c", "d"], ["b"], ["g"], ["c"]);

        // orgsAnswers[1] = Knights of the Round Table
        const kort = new Array(["a", "b"], ["a", "b", "c", "d", "e"], ["a"], ["a", "b"], ["a", "e", "f"], ["a", "c", "d"], ["b", "d"], ["f"], ["a", "b", "c", "d", "e"]);

        // orgsAnswers[2] = Knight-Thon
        const kt = new Array(["a", "b"], ["a", "b", "c", "d", "e"], ["a"], ["a"], ["a", "b", "e"], ["b", "c", "d"], ["a", "c"], ["h"], ["a", "b", "c", "d", "e"]);

        // orgsAnswers[3] = Campus Activities Board
        const cab = new Array(["a", "b"], ["a", "b", "c", "d", "e"], ["a", "b"], ["a"], ["a", "b", "e"], ["c", "d"], ["b", "e", "f"], ["e", "f", "i"], ["a", "b", "c"]);

        //orgsAnswers[4] = Knights of the Connection
        const kotc = new Array(["a"], ["a", "b", "c", "d", "e"], ["a", "b", "c", "d"], ["a", "b"], ["a", "b", "f"], ["a", "c", "d"], ["b", "d"], ["f", "i"], ["b", "c"]);

        //orgsAnswers[5] = Volunteer UCF
        const vucf = new Array(["a"], ["a", "b", "c", "d", "e"], ["a", "c"], ["a", "b"], ["a", "b", "e", "f"], ["a", "c", "d"], ["a", "b", "c"], ["f", "g", "h", "i"], ["a", "b", "e"]);

        //orgsAnswers[6] = Student Engagement
        const se = new Array(["a", "b"], ["a", "b", "c", "d", "e"], ["c"], ["a", "b"], ["b", "e", "f"], ["a", "c", "d"], ["b", "e", "f"], ["a", "f", "i"], ["b", "e"]);

        //orgsAnswers[7] = Homecoming
        const hc = new Array(["a", "b"], ["a", "b", "c", "d", "e"], ["a"], ["a"], ["a", "b", "c", "d", "e", "f"], ["b", "c", "d"], ["b", "c"], ["i"], ["a", "b", "c", "d", "e"]);

        //orgsAnswers[8] = Rosen Life
        const rl = new Array(["a", "b"], ["a", "b", "c", "d", "e"], ["a", "b"], ["a"], ["a", "b", "c", "d", "e", "f"], ["b", "c", "d"], ["b", "c"], ["g"], ["a", "b", "c", "d", "e"]);

        //orgsAnswers[9] = OSI Productions
        const prod = new Array(["b"], ["a", "b", "c", "d", "e"], ["a", "c"], ["a"], ["a", "c", "d"], ["b", "c", "d"], ["e", "f"], ["b", "c", "d"], ["a", "b", "c", "d", "e"]);

        //orgsAnswers[10] = Knights of the Turntables
        const kott = new Array(["a", "b"], ["a", "b", "c", "d", "e"], ["a", "b", "c"], ["a"], ["a", "b", "d"], ["b", "c", "d"], ["e", "f"], ["b"], ["a", "b", "c", "d", "e"]);

        //orgsAnswers[11] = Black & Gold Studios
        const bng = new Array(["a", "b"], ["a", "b", "c", "d", "e"], ["a"], ["a", "b"], ["c", "d", "f"], ["a", "c"], ["e", "f"], ["b"], ["a", "b", "c", "d", "e"]);

        //orgsAnswers[12] = Graduate Outreach
        const go = new Array(["a", "b"], ["a", "b", "c", "d", "e"], ["a"], ["b"], ["a", "b", "c", "d", "e", "f"], ["a", "b", "c", "d"], ["a", "b", "c", "d", "e", "f"], ["a", "b", "c", "d", "e", "f", "h", "i"], ["a", "b", "c", "d", "e"]);

        //orgsAnswers[13] = Design Group
        const dg = new Array(["a", "b"], ["a", "b", "c", "d", "e"], ["a", "c"], ["a"], ["c", "d", "f"], ["a", "c"], ["b", "e", "f"], ["a"], ["a"]);

        //add them all into one master array

        const orgsAnswers = new Array(lk, kort, kt, cab, kotc, vucf, se, hc, rl, prod, kott, bng, go, dg);
        
        const userChoices = this._tallyResponses();

        //Create an empty array to populate with 0
        let finalPoints = new Array(orgsAnswers.length).fill(0);
        //end the end of the for loop:
        // shuffle an array and check from the end of the array
        //set j as a random number between the range of the start and the selected end point of the array
        //set x to the value of i, then the value of i to the random selected point of j in the array's value
        //then set j to x, which is the value of i 
        function shuffle(a) {
            var j, x, i;
            for (i = a.length - 1; i > 0; i--) {
                j = Math.floor(Math.random() * (i + 1));
                x = a[i];
                a[i] = a[j];
                a[j] = x;
            }
            return a;
        }
        //this function creates a randomized list of responses with the the most amount of points that are equal
        function shuffledMatchList(a) {
            var i = 0,
                maxList = new Array(),
                max = 0;

            for (i = 0; i < a.length; i++) {
                if (a[i] > max) {
                    max = a[i];
                }
            }

            for (i = 0; i < a.length; i++) {
                if (a[i] == max) {
                    maxList.push(i);
                }
            }

            return shuffle(maxList);
        }

                // will return results with anything except b, so only 

        // this function checks if the user's choice == to value of an organization's answer
        function addPoints(answer, j) {

            for (var i = 0; i < orgsAnswers.length; i++) {
                
                if (orgsAnswers[i][j].includes(answer)) {
                    
                    finalPoints[i]++;
                    //check if user selected graduate for question 4
                    orgsAnswers.forEach((question,i) =>{
                        if(!question[3].includes(userChoices[3])){
                            //entirely removes undergraduate or graduate type responses from appearing
                            finalPoints[i] = -1;
                        }
                    });

                }
            }
        }

        userChoices.forEach(addPoints);
        return shuffledMatchList(finalPoints);
        }
    

    this._showResult = function () {
        let $resultBox = $(".result");
       


        
        let resultList = self._calcResult();
                // array of objects lists all the organization's that were selected
        let results = new Array();
        results.push(ORGINFO[resultList[0]]);


        $resultBox.addClass("resultComplete jumbotron");
        $resultBox.html("<h1 class='mb-4'><p>Here is your result!</p> <p>You matched with <i>" + ORGINFO[resultList[0]].name + '</i></p> </h1> <p>' + ORGINFO[resultList[0]].info + '</p><p class="text-center"><a class="my-3 mx-auto btn btn-warning btn-lg" href="' + ORGINFO[resultList[0]].link + '"target="_blank"><strong>Visit Website</strong></a></p>');

        const resultToString = () => {
            var myStr = '';
            for (let i = 1; i < resultList.length; i++) {
                myStr += '<li ><a class="collapsed" href="#collapseInfo-' + i + '"data-toggle="collapse" role="button" aria-expanded="false" id="info-' + i + '"><h4><strong><u>' + ORGINFO[resultList[i]].name + '</u></strong></h4></a></li><p class="collapse text-center" id="collapseInfo-' + i + '">' + ORGINFO[resultList[i]].info + '<br><a class ="btn btn-warning btn-lg my-3 mx-auto text-center" href="' + ORGINFO[resultList[i]].link + '" target="_blank"><strong>Visit Website</strong></a></p>';

                //add objects to results array
                results.push(ORGINFO[resultList[i]]);
            }
            return myStr;
        }
        // Create an object that has two keys: mail, array of objects
        //results.join(',');       

        if (resultList.length > 1) {
            $resultBox.append('<hr class="my-4"><h2><strong> You also matched with:</strong></h2> <ul id="accordion">' + resultToString() + '</ul><br>');
        }
        $resultBox.append('<hr class="my-4"><form class="text-center form-inline form-row align-items-center form-group-lg input-group input-group-md" name="user-mail" id="mail-form"> <input class="form-control input-lg " aria-label="User email input" type="email" name="email" id="mail" placeholder="Enter email for copy of results"><button class="btn btn-warning btn-md ml-2 font-weight-bold" id="submit" type="submit">Send Results</button></form>');
        $resultBox.append('<hr class="my-4"><p class="text-center">Schedule a consultation with KnightQuest to learn more about your program and many others. Be sure to screenshot your result!</p><p class="text-center"><a class="btn btn-warning btn-lg" href="mailto:kortoutreach@ucf.edu?subject=Schedule KnightQuest Appointment&body=I got ' + ORGINFO[resultList[0]].name + ' as my result from the OSI Quiz. Could I schedule an appointment to learn more about it and my other options to get involved at UCF?"><strong>Schedule Consultation</strong></a></p>');
        
        //Event Listener to check if mail-form has been filled
        if (document.body.contains(document.getElementById("mail-form"))){
            
            let form  = document.getElementById("mail-form");
            form.addEventListener("submit",function(e){
                
                //sets mail to the input of user
                let mail = document.querySelector("#mail").value;
                
                //create object
                let user = {
                    'email' : mail,
                    'results': results
                };

                let json = JSON.stringify(user);
                //Insert location of the php file here
                $.post('../osiquiz-mail.php',{ data: json},(response)=>{
                    alert(response);
                    console.log(response);
                    
                    mail = "";
                    
                });
               e.preventDefault();
                
                
            },/* Allow Bubbling Propagation */ false);
        } else {
            console.log("Doesn't recognize event")
        }
        //Animated scroll
        $("body, html").animate({
            scrollTop: (($resultBox).offset().top - 25) //25px for visual padding
        }, 500);
    }

    this._bindEvents = function () {
        let jumboList = new Array();
        $(".jumbotron").each(function () {
            jumboList.push($(this));
        });
        // console.log(jumboList)
        $(".quiz-choice").on("click", function () {
            // console.log(self._isComplete());
            let $choice = $(this);
            let $question = $choice.closest("ul[data-quiz-question]");
            self._selectAnswer($choice, $question);
            if (self._isComplete()) {
                self._showResult();
                //console.log(self._showResult())
                return;
            }
            //Animated scroll to next Jumbotron element
            $("body, html").animate({
                scrollTop: (jumboList[parseInt($question.data("quiz-question"))]).offset().top - 25
            }, 500);
        });
    }
}

var quiz = new Quiz();
quiz.init();