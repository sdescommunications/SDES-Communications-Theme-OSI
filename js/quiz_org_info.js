const orgInfo = [
    {
        name: 'Teach For America',
        info:
            "We're a non-profit, leadership development organization that helps young leaders use their talents to end educational inequality in America. In the long-term, our network of more than 50,000 leaders continue to channel their collective talents to create the systemic-level change needed for kids across the country. As a full-time teacher, your salary typically range from $33k-$58k and are entitled to medical benefits, depending on your location. This program is a great fit for strong leaders & achievers with excellent organizational abilities, interpersonal skills, and deep belief in the potential of all children.",
        website: 'https://www.teachforamerica.org',
        scores: [['a', 'b', 'c', 'd', 'e'], ['a', 'b', 'e'], ['c', 'e'], ['a']],
    },

    {
        name: 'Peace Corps',
        info:
            'Peace Corps is a catalyst for grassroots international development and cultural exchange powered by volunteers ready to use their energy, ingenuity, and skills to work towards reducing inequality and creating opportunities in communities in over 60 countries. After new members complete their 2-3 months pre-service training, they are able to work in several focus areas of service with a living stipend, health & dental care benefits, housing, student loan deferment, and the chance to earn a TEFL certificate. This program is a great fit for students that are adaptable, respectful, open-minded, disclipined, and work well on a team.',
        website: 'https://www.peacecorps.gov/',
        scores: [
            ['a', 'b', 'c', 'd', 'e'],
            ['b', 'd', 'e', 'f'],
            ['b', 'c', 'd', 'e'],
            ['a', 'b', 'f'],
        ],
    },

    {
        name: 'Operation AmeriCorps',
        info:
            'A collaborative partnership between the City of Orlando, Orange Country Public Schools, and After-School All-Stars, the goal of Operation AmeriCorps is to use national service to ensure high school graduation and a post-high school pathway for targeted students towards the paths of military enlistment, 4-year university, trade/technical school, or employment. For one year, members will work with students and be responsible for promoting academic, social, and personal success. Members are paid a salary a living allowance at or above $13,500 a year, along with the options of Loan Forbearance, Child Care allowances, and upon completion of the program, members are eligible for a the Segal Education Award valued at the current amount of the Pell Grant.',
        website:
            "http://www.cityoforlando.net/fpr/operation-americorps/'target='_blank'>Visit Website</a></strong>",
        scores: [['a'], ['e'], ['c'], ['a']],
    },

    {
        name: 'National Health Corps',
        info:
            'National Health Corps has worked to expand access to health care and health education in under-served communities for more than 20 years. For 10 ½ months, members will work to provide an array of health screenings and wellness education to those in need in addition for ways individuals in communities can find resources and benefits to promote a healthy, sustainable life. The volunteer members also receive benefits such as childcare assistance, student loan forbearance, health coverage, a $13,992 stipend over the course of their service, and a $5920.00 education award upon completion of their term. A great fit for individuals who are interested addressing health disparities with a perspective informed by their experience in low-income communities and systemic challenges of poverty and racism.',
        website: 'http://nationalhealthcorps.org/',
        scores: [
            ['a', 'b', 'c', 'd', 'e'],
            ['a', 'b', 'f'],
            ['d', 'e'],
            ['b', 'd', 'f'],
        ],
    },

    {
        name: 'Orlando Cares',
        info:
            'Orlando Cares works to address community issues through volunteerism. Volunteers serve at City Hall or at local nonprofit organizations to develop programs to recruit, screen, train, engage, and monitor volunteers from the community. Mentor students to their improve wellness, science & reading literacy in K-5 schools and preschools. Members able to dedicate a year of full-time service, will have benefits including a living allowance, healthcare benefits, relocation allowance, and a $5,815 Segal Education Award or a $1,500 cash stipend at the end of the service year. This program is a great program for individuals with a passion to promote youth education and development with a devotion to community service.',
        website: 'https://www.cityoforlando.net/oca/orlando-cares/',
        scores: [
            ['a', 'b', 'c', 'd', 'e'],
            ['b', 'e'],
            ['c', 'e'],
            ['e', 'd', 'f'],
        ],
    },

    {
        name: 'City Year',
        info:
            'CityYear is a gap year program from AmeriCorps that invites young leaders to serve 11 months as a tutor and mentor to students Central Florida in K-12 schools and communities. Members receive multiple benefits including a bimonthly living stipend of $630, health insurance, and a $6000 Segal Education award at the end of the service year along with eligibility to many other scholarships. It is a great opportunity for servant leaders interested in civic engagement and community education promotion.',
        website: 'https://www.cityyear.org/about/history-values/americorps/',
        scores: [['a', 'b', 'c', 'd', 'e'], ['b', 'e'], ['c', 'e'], ['a']],
    },

    {
        name: 'Florida Conservation Corps',
        info:
            'Florida Conservation Corps provides hands-on service learning opportunities designed to develop leaders in the fields of cultural preservation, land management, and regional outreach through their A.N.T, T.R.E.C, & R.O.A.R programs. Full-time volunteer members commit themselves to 11 months and 1,700 hours of service (~40hrs/week) and receive benefits including a monthly living allowance, health insurance, childcare assistance, free access to state parks, student loan forbearance, and upon completion of their service receive a Segal Education Award Volunteers develop confidence, knowledge and abilities necessary to become better environmentalists, conservationists, and stewards of their community and environment.',
        website: 'https://www.floridastateparks.org/flcc',
        scores: [['a', 'b', 'c', 'd', 'e'], ['b', 'c', 'f'], ['a'], ['b']],
    },

    {
        name: 'National Civilian Community Corps',
        info:
            'AmeriCorps National Civilian Community Corps & FEMA Corps is a full-time, residential, team-based program for young adults between 18-24 that runs for 10-12 months compromised of teams of 8-10 members. This organization focuses on disaster relief, infrastructure improvement, environmental stewardship, and urban and rural development. Members receive benefits including a living allowance of roughly $90 a week before taxes and team leaders receive $250 dollars before taxes. Members also receive lodging, transportation, uniform, meals, and health-care benefits at no cost. Federal student loan forbearance, childcare allowance, and up to 9 college credit hours. This is a great opportunity for passionate service-minded individuals who wish to travel across the country.',
        website:
            'https://www.nationalservice.gov/programs/americorps/americorps-programs/americorps-nccc',
        scores: [
            ['a', 'b', 'c', 'd', 'e'],
            ['a', 'b', 'f'],
            ['a', 'b'],
            ['b', 'e', 'f'],
        ],
    },

    {
        name: 'Orlando Partnership for School Success',
        info:
            'Orlando-Partnership for School Success is a collaborative partnership between the City of Orlando, Orange County Public School and After-School All-Stars. The mission of the project is to expand academic and social supports for at-risk youth in high poverty neighborhoods during the school day, after school and in summer. Each volunteer is assigned 40 children for whom they will be responsible for promoting their academic, social, and personal success for one year. Best suited for bilingual, community driven individuals. Volunteers receive benefits including a bi-weekly living allowance of $562.50, healthcare benefits, childcare allowance for active members, loan forbearance, and eligibility for the Segal Education Award valued at the current amount of the Pell Grant in that year. Great for individuals with a strong desire to serve with a committment to diversity, teamwork, self-development, and desire to work with youth. Billingual individuals skilled within the Microsoft Office Suite is a plus.',
        website: 'http://www.cityoforlando.net/fpr/operation-americorps/',
        scores: [
            ['a', 'b', 'c', 'd', 'e'],
            ['a', 'b', 'c', 'd', 'e', 'f'],
            ['c', 'e'],
            ['a', 'f'],
        ],
    },

    {
        name: 'Public Allies',
        info:
            'Public Allies Central Florida is a 10-month long full-time apprenticeship at a local nonprofit as well as a leadership development program. Best for individuals who want to promote social justice and social equity by engaging with their community. Members receive a $1,500 stipend, access to childcare, and eligibility for the Segal Education Award. This program is best suited for individuals with a passion for service-minded individuals who wish to serve marginalized people within their local community.',
        website: 'http://www.publicallies.org/',
        scores: [
            ['a', 'b', 'c', 'd', 'e'],
            ['a', 'b', 'c', 'd', 'e', 'f'],
            ['c', 'e'],
            ['c', 'f'],
        ],
    },

    {
        name: 'Arc Jacksonville',
        info: "The Arc Jacksonville’s AmeriCorps Youth Transaction Project (The Project) was structured to address the growing need to assist young adults with intellectual and developmental disabilities in their quest for independence while they attended school at the University of North Florida. The Project has since its beginning expanded to five Arc Jacksonville sites and engages 30+ Corps members in service annually to more than 200 individuals served. Members of The Arc Jacksonville's AmeriCorps Youth Transaction Project (The Project) provide a year of service as mentors, helping individuals with intellectual disabilities learn and perfect much needed independent living skills in a creative environment. Members hold one-on-one or small group mentoring sessions, focusing on financial literacy, wellness, and professionalism. Members also come together for service projects to benefit partnering agencies in the Jacksonville community.",
        website: 'https://arcjacksonville.org/americorps/',
        scores: [
            ['a', 'c'],
            ['b', 'f'],
            ['c'],
            ['a', 'f'],
        ]
    },

    {
        name: 'Girl Scout of West Central Orlando',
        info: 'The Girl Scouts of West Central Florida, Inc. (GSWCF) provides a safe, girl-only space where girls K-12 can develop their leadership skills through age-appropriate activities enabling them to discover their values and the world around them; connect with others in a multicultural environment; and take action to make a difference in their communities. Generously funded by United Way of Central Florida, this program serves 1,820 Polk county girls during school, after school, and in the summer providing Girl Scout experiences.  Program Specialists commit to serving 1,700 service hours providing mentorship, academic support, leadership development, among other duties and activities to girls and being the role models they need. Corps members earn a monthly living stipend ($1,189 gross) and are eligible to receive the $6195 Segal Americorps Education Award.',
        website: 'https://www.gswcf.org/en/about-girl-scouts/our-program.html',
        scores: [
            ['a'],
            ['e'],
            ['c'],
            ['e', 'f'],
        ]
    },

    {
        name: 'Metropolitan Ministries',
        info: "Metropolitan Ministries is a local, grassroots, donor and volunteer-fueled community nonprofit that provides services designed to help homeless and at-risk children and families throughout Tampa Bay. We have an AmeriCorps State program and an AmeriCorps VISTA program with about 30 total members. Through both programs our goal is to provide resources and services that help people improve health and access safe shelter or permanent housing; as well as to generate resources that expand our housing and supportive services for families experiencing poverty and homelessness. We are always looking for awesome AmeriCorps members to join our Metro Family! Metropolitan Ministries' AmeriCorps State program, Brigaide, and AmeriCorps VISTA program, Tampa Bay Homeless Outreach, may be a good fit for you. Brigaide provides concrete goods like food and clothing, system navigation services, and connection to housing, health, employment, and transportation resources to individuals experiencing homelessness in Hillsborough and Pasco Counties. The Americorps VISTA program, engages members in a variety of capacity building and sustainability activities, including: volunteer recruitment and management, program and partnership development, digital marketing, grant writing, and social enterprise development.",
        website: 'https://www.metromin.org/americorps/',
        scores: [
            ['c'],
            ['a'],
            ['b', 'd'],
            ['e'],
        ]
    },

    {
        name: 'Florida Conservation Corps (FLCC): Project A.N.T.',
        info: 'The Florida Conservation Corps (FLCC) AmeriCorps Program is administered by the Florida Department of Environmental Protection (DEP). Members serve 1700 hours in Florida State Parks. FLCC members will serve under one of three Project areas, Project A.N.T. (AmeriCorps Nonnative plant Terminators), Project T.R.E.C. (Trail Restoration and Enhancement Corps), or Project R.O.A.R (Regional Outreach and Awareness Recruiters). Members are required to work 35 – 40 hours per week and must have a positive attitude. Project A.N.T. focuses on habitat restoration by managing invasive exotic plants. Members reduce invasive exotic plant infestations through various land management techniques (herbicide use/manual removal), recruit volunteers, & educate park visitors about issues of invasive exotic plants in natural ecosystems.',
        website: 'https://www.floridastateparks.org/FLCC',
        scores: [
            ['b'],
            ['a'],
            ['a'],
            ['b'],
        ]
    },

    {
        name: 'Jumpstart',
        info: 'Jumpstart is a national early education organization that recruits and trains college students and community volunteers to work with preschool children in low-income neighborhoods. Through a proven curriculum, these children develop the language and literacy skills they need to be ready for school, setting them on a path to close the achievement gap before it is too late. Join us to work toward the day every child in America enters kindergarten prepared to succeed. Corps members must be at least 17 years old with High School Diploma or equivalent and commit to approximately 8-12 hours a week Monday-Friday. Students who participate can make an impact on educational equity while potentially earning funds to help pay for their college education through Federal Work Study or a Segal AmeriCorps Education Award (depending on where they serve).',
        website: 'https://www.jstart.org/our-work/corps-members/#positions',
        scores: [
            ['a'],
            ['b', 'c'],
            ['c'],
            ['a'],
        ]
    },

    {
        name: 'AMIkids',
        info: 'AMI Kids AmeriCorps members provide mentoring services in the areas of education and job skills training to youth currently involved with, or at risk of being involved with the Department of Juvenile Justice. Members will be mentoring youth one on one and group, hosting monthly job clubs, tutoring, recruiting volunteers to come speak to youth, and supporting follow-up efforts with youth exiting the program. All members will be required to attend regional training in or near service area with Career Coordinators to receive pre-service orientation to the AmeriCorps program. AmeriCorps members commit to serve a minimum 675 hours of mentor service and earn a Living Allowance of $480 per month. Contact Adalyn Hazelman Jaquez (Orlando-CC2@amikids.org) for more info.',
        website: 'https://www.jstart.org/our-work/corps-members/#positions',
        scores: [
            ['c'],
            ['c', 'e', 'f'],
            ['c'],
            ['a', 'f']
        ]
    }
];

export default orgInfo;
