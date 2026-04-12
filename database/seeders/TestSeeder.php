<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\Material;
use App\Models\Question;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        $test = Test::create([
            'title' => 'IELTS Academic Mock Test 1',
            'description' => 'A comprehensive IELTS Academic mock test covering all four modules with authentic materials and questions.',
            'listening_time' => 30,
            'reading_time' => 60,
            'writing_time' => 60,
            'status' => 'active',
            'is_published' => true
        ]);

        // ==========================================
        // LISTENING MATERIALS — 4 parts, each with own audio
        // ==========================================
        $listeningMaterials = [];
        $listeningParts = [
            ['title' => 'Part 1 — Hotel Reservation', 'file_name' => 'listening_part1.mp3', 'content' => 'A conversation between a hotel receptionist and a customer making a reservation. The customer asks about room types, prices, and available dates.'],
            ['title' => 'Part 2 — City Tour Guide', 'file_name' => 'listening_part2.mp3', 'content' => 'A tour guide describes the main attractions of a city, including opening hours, ticket prices, and recommended routes for visitors.'],
            ['title' => 'Part 3 — University Project Discussion', 'file_name' => 'listening_part3.mp3', 'content' => 'Three university students discuss their research project on renewable energy, debating methodology and dividing tasks among themselves.'],
            ['title' => 'Part 4 — Lecture on Marine Biology', 'file_name' => 'listening_part4.mp3', 'content' => 'A university professor delivers a lecture about coral reef ecosystems, their importance, the threats they face, and conservation efforts.'],
        ];

        foreach ($listeningParts as $index => $part) {
            $listeningMaterials[$index + 1] = Material::create([
                'test_id' => $test->id,
                'type' => 'audio',
                'module' => 'listening',
                'part' => $index + 1,
                'title' => $part['title'],
                'content' => $part['content'],
                'file_name' => $part['file_name'],
                'mime_type' => 'audio/mpeg',
                'file_size' => '2048000',
                'order' => $index + 1
            ]);
        }

        // ==========================================
        // LISTENING PART 1 — Hotel Reservation (2 speakers, everyday context)
        // Questions 1-10: gap_filling, multiple_choice, short_answer
        // ==========================================
        $part1Questions = [
            ['question_text' => 'What is the customer\'s surname?', 'type' => 'gap_filling', 'correct_answers' => ['Henderson'], 'order' => 1],
            ['question_text' => 'What is the customer\'s phone number?', 'type' => 'gap_filling', 'correct_answers' => ['07845 392017'], 'order' => 2],
            ['question_text' => 'How many nights does the customer want to stay?', 'type' => 'short_answer', 'correct_answers' => ['3', 'three', '3 nights', 'three nights'], 'order' => 3],
            ['question_text' => 'Which type of room does the customer choose?', 'type' => 'multiple_choice', 'options' => ['Single room', 'Double room', 'Twin room', 'Suite'], 'correct_answers' => ['Double room'], 'order' => 4],
            ['question_text' => 'What date does the customer plan to arrive?', 'type' => 'gap_filling', 'correct_answers' => ['15 March', 'March 15', '15th March'], 'order' => 5],
            ['question_text' => 'The hotel is located near the _____.',  'type' => 'gap_filling', 'correct_answers' => ['railway station'], 'order' => 6],
            ['question_text' => 'What is the price per night for the chosen room?', 'type' => 'multiple_choice', 'options' => ['$85', '$95', '$110', '$125'], 'correct_answers' => ['$95'], 'order' => 7],
            ['question_text' => 'Is breakfast included in the room price?', 'type' => 'short_answer', 'correct_answers' => ['yes', 'Yes'], 'order' => 8],
            ['question_text' => 'What time is check-in?', 'type' => 'gap_filling', 'correct_answers' => ['2 PM', '2pm', '14:00'], 'order' => 9],
            ['question_text' => 'How will the customer pay for the reservation?', 'type' => 'multiple_choice', 'options' => ['Cash', 'Credit card', 'Bank transfer', 'Cheque'], 'correct_answers' => ['Credit card'], 'order' => 10],
        ];

        foreach ($part1Questions as $q) {
            Question::create([
                'test_id' => $test->id,
                'material_id' => $listeningMaterials[1]->id,
                'module' => 'listening',
                'part' => 1,
                'type' => $q['type'],
                'question_text' => $q['question_text'],
                'options' => $q['options'] ?? null,
                'correct_answers' => $q['correct_answers'],
                'points' => 1,
                'order' => $q['order'],
            ]);
        }

        // ==========================================
        // LISTENING PART 2 — City Tour (1 speaker, monologue)
        // Questions 11-20: multiple_choice, matching, diagram_labeling, sentence_completion
        // ==========================================
        $part2Questions = [
            ['question_text' => 'What is the first attraction the guide recommends?', 'type' => 'multiple_choice', 'options' => ['The National Museum', 'The Royal Palace', 'The City Park', 'The Old Market'], 'correct_answers' => ['The National Museum'], 'order' => 1],
            ['question_text' => 'The museum is open from _____ to 6 PM daily.', 'type' => 'gap_filling', 'correct_answers' => ['9 AM', '9am', '9:00'], 'order' => 2],
            ['question_text' => 'How much is the museum entrance fee for adults?', 'type' => 'short_answer', 'correct_answers' => ['$12', '12 dollars', '$12.00'], 'order' => 3],
            ['question_text' => 'Match each attraction with its main feature.', 'type' => 'matching', 'options' => ['Historical artifacts from the 15th century', 'Beautiful gardens and fountains', 'Fresh local produce and souvenirs', 'Panoramic views of the entire city'], 'correct_answers' => ['The National Museum', 'The Royal Palace', 'The Old Market', 'The Clock Tower'], 'order' => 4],
            ['question_text' => 'The guide recommends taking the _____ to reach the Clock Tower.', 'type' => 'sentence_completion', 'options' => ['bus', 'taxi', 'cable car', 'bicycle', 'train'], 'correct_answers' => ['cable car'], 'order' => 5],
            ['question_text' => 'Which day of the week is the Old Market closed?', 'type' => 'multiple_choice', 'options' => ['Monday', 'Tuesday', 'Wednesday', 'Sunday'], 'correct_answers' => ['Monday'], 'order' => 6],
            ['question_text' => 'The tour starts at what time?', 'type' => 'gap_filling', 'correct_answers' => ['10 AM', '10am', '10:00'], 'order' => 7],
            ['question_text' => 'What does the guide say about parking?', 'type' => 'multiple_choice', 'options' => ['It is free everywhere', 'It is expensive near the centre', 'There is no parking available', 'Only hotel guests can park'], 'correct_answers' => ['It is expensive near the centre'], 'order' => 8],
            ['question_text' => 'Put the tour stops in the order the guide recommends.', 'type' => 'ordering', 'correct_answers' => ['The National Museum', 'The Royal Palace', 'The Old Market', 'The Clock Tower'], 'order' => 9],
            ['question_text' => 'The best time to visit the gardens is during _____.', 'type' => 'sentence_completion', 'options' => ['spring', 'summer', 'autumn', 'winter', 'morning'], 'correct_answers' => ['spring'], 'order' => 10],
        ];

        foreach ($part2Questions as $q) {
            Question::create([
                'test_id' => $test->id,
                'material_id' => $listeningMaterials[2]->id,
                'module' => 'listening',
                'part' => 2,
                'type' => $q['type'],
                'question_text' => $q['question_text'],
                'options' => $q['options'] ?? null,
                'correct_answers' => $q['correct_answers'],
                'points' => 1,
                'order' => $q['order'],
            ]);
        }

        // ==========================================
        // LISTENING PART 3 — University Project Discussion (2-3 speakers, academic)
        // Questions 21-30: multiple_choice, matching, sentence_completion, select_options
        // ==========================================
        $part3Questions = [
            ['question_text' => 'What is the main topic of the students\' research project?', 'type' => 'multiple_choice', 'options' => ['Solar energy efficiency', 'Wind farm locations', 'Renewable energy policy', 'Electric vehicle adoption'], 'correct_answers' => ['Renewable energy policy'], 'order' => 1],
            ['question_text' => 'Which research method do the students decide to use?', 'type' => 'multiple_choice', 'options' => ['Laboratory experiments', 'Surveys and interviews', 'Case study analysis', 'Statistical modelling'], 'correct_answers' => ['Case study analysis'], 'order' => 2],
            ['question_text' => 'Match each student with their assigned task.', 'type' => 'matching', 'options' => ['Literature review', 'Data collection', 'Writing the conclusion'], 'correct_answers' => ['Sarah', 'Tom', 'Maria'], 'order' => 3],
            ['question_text' => 'The deadline for the first draft is _____.', 'type' => 'gap_filling', 'correct_answers' => ['March 20', '20 March', '20th March'], 'order' => 4],
            ['question_text' => 'What does Sarah think is the biggest challenge?', 'type' => 'multiple_choice', 'options' => ['Finding enough data', 'Meeting the word count', 'Getting access to journals', 'Agreeing on a focus'], 'correct_answers' => ['Finding enough data'], 'order' => 5],
            ['question_text' => 'The professor suggested they focus on _____ countries for their case studies.', 'type' => 'sentence_completion', 'options' => ['European', 'Asian', 'African', 'Scandinavian', 'South American'], 'correct_answers' => ['Scandinavian'], 'order' => 6],
            ['question_text' => 'Which TWO sources do the students plan to use?', 'type' => 'select_options', 'options' => ['Government reports', 'Social media posts', 'Academic journals', 'Newspaper articles', 'Blog posts'], 'correct_answers' => ['Government reports', 'Academic journals'], 'order' => 7],
            ['question_text' => 'How long should the final presentation be?', 'type' => 'short_answer', 'correct_answers' => ['15 minutes', '15 mins', 'fifteen minutes'], 'order' => 8],
            ['question_text' => 'Tom suggests meeting _____ a week to discuss progress.', 'type' => 'gap_filling', 'correct_answers' => ['twice', '2 times'], 'order' => 9],
            ['question_text' => 'What does Maria recommend about the project introduction?', 'type' => 'multiple_choice', 'options' => ['Keep it short and general', 'Include detailed statistics', 'Start with a personal story', 'Use a famous quotation'], 'correct_answers' => ['Keep it short and general'], 'order' => 10],
        ];

        foreach ($part3Questions as $q) {
            Question::create([
                'test_id' => $test->id,
                'material_id' => $listeningMaterials[3]->id,
                'module' => 'listening',
                'part' => 3,
                'type' => $q['type'],
                'question_text' => $q['question_text'],
                'options' => $q['options'] ?? null,
                'correct_answers' => $q['correct_answers'],
                'points' => 1,
                'order' => $q['order'],
            ]);
        }

        // ==========================================
        // LISTENING PART 4 — Academic Lecture on Marine Biology (1 speaker)
        // Questions 31-40: gap_filling, sentence_completion, true_false_notgiven, short_answer
        // ==========================================
        $part4Questions = [
            ['question_text' => 'Coral reefs cover less than _____ percent of the ocean floor.', 'type' => 'gap_filling', 'correct_answers' => ['1', 'one'], 'order' => 1],
            ['question_text' => 'Despite their small size, coral reefs support approximately _____ of all marine species.', 'type' => 'gap_filling', 'correct_answers' => ['25%', '25 percent', 'a quarter'], 'order' => 2],
            ['question_text' => 'The Great Barrier Reef is located off the coast of which country?', 'type' => 'short_answer', 'correct_answers' => ['Australia'], 'order' => 3],
            ['question_text' => 'Coral bleaching is caused primarily by _____ in ocean temperatures.', 'type' => 'sentence_completion', 'options' => ['a rise', 'a drop', 'fluctuations', 'stability', 'a decrease'], 'correct_answers' => ['a rise'], 'order' => 4],
            ['question_text' => 'The professor states that coral reefs provide natural protection against storms and coastal erosion.', 'type' => 'true_false_notgiven', 'correct_answers' => ['True'], 'order' => 5],
            ['question_text' => 'Ocean acidification makes it harder for corals to build their _____ structures.', 'type' => 'gap_filling', 'correct_answers' => ['calcium carbonate', 'skeleton'], 'order' => 6],
            ['question_text' => 'The economic value of coral reefs exceeds $30 billion annually.', 'type' => 'true_false_notgiven', 'correct_answers' => ['Not Given'], 'order' => 7],
            ['question_text' => 'The professor mentions that _____ fishing practices are a major threat to reef ecosystems.', 'type' => 'sentence_completion', 'options' => ['destructive', 'sustainable', 'traditional', 'commercial', 'recreational'], 'correct_answers' => ['destructive'], 'order' => 8],
            ['question_text' => 'What solution does the professor recommend as most effective for reef conservation?', 'type' => 'multiple_choice', 'options' => ['Banning all fishing', 'Creating marine protected areas', 'Building artificial reefs', 'Reducing tourism'], 'correct_answers' => ['Creating marine protected areas'], 'order' => 9],
            ['question_text' => 'By what year does the professor predict 90% of reefs could be at risk?', 'type' => 'gap_filling', 'correct_answers' => ['2050'], 'order' => 10],
        ];

        foreach ($part4Questions as $q) {
            Question::create([
                'test_id' => $test->id,
                'material_id' => $listeningMaterials[4]->id,
                'module' => 'listening',
                'part' => 4,
                'type' => $q['type'],
                'question_text' => $q['question_text'],
                'options' => $q['options'] ?? null,
                'correct_answers' => $q['correct_answers'],
                'points' => 1,
                'order' => $q['order'],
            ]);
        }

        // ==========================================
        // READING MATERIALS & QUESTIONS (3 passages, ~13 questions each = 40 total)
        // ==========================================
        $readingPassages = [
            [
                'title' => 'The Future of Renewable Energy',
                'content' => "Renewable energy sources have become increasingly important in the global effort to reduce carbon emissions and combat climate change. Solar power, wind energy, and hydroelectric power are leading the transition away from fossil fuels.\n\nSolar energy, harnessed through photovoltaic panels, has seen remarkable growth in recent years. The cost of solar panels has decreased significantly, making this technology more accessible to homeowners and businesses alike. Countries like Germany and China have invested heavily in solar infrastructure, leading to substantial increases in solar energy production.\n\nWind energy, another prominent renewable source, utilizes wind turbines to generate electricity. Offshore wind farms have become particularly popular due to their ability to capture stronger and more consistent winds. The United Kingdom and Denmark have been pioneers in offshore wind development.\n\nHydroelectric power, generated from flowing water, remains one of the most reliable renewable energy sources. Large-scale hydroelectric projects, such as the Three Gorges Dam in China, provide significant amounts of electricity to millions of people.\n\nDespite the progress made in renewable energy adoption, challenges remain. Energy storage technology needs to advance to address the intermittent nature of solar and wind power. Additionally, the initial investment required for renewable energy infrastructure can be substantial, though long-term savings often offset these costs.\n\nThe transition to renewable energy is not just an environmental imperative but also an economic opportunity. The renewable energy sector has created millions of jobs worldwide and continues to grow rapidly. As technology advances and costs decrease, renewable energy is expected to become the dominant source of electricity generation in the coming decades."
            ],
            [
                'title' => 'The Impact of Social Media on Society',
                'content' => "Social media has fundamentally transformed how people communicate, share information, and interact with the world around them. Platforms like Facebook, Twitter, Instagram, and TikTok have created new opportunities for connection while also presenting significant challenges to society.\n\nOne of the most significant positive impacts of social media is its ability to connect people across geographical boundaries. Families separated by distance can maintain regular contact, and individuals can form communities based on shared interests or experiences. Social media has also played a crucial role in social movements, allowing activists to organize protests and raise awareness about important issues.\n\nHowever, the rise of social media has also brought about several concerning developments. The spread of misinformation and fake news has become a major problem, with false information often spreading faster than accurate news. Social media algorithms, designed to maximize engagement, can create echo chambers where users are exposed only to information that confirms their existing beliefs.\n\nMental health concerns have also emerged as a significant issue related to social media use. Studies have shown correlations between heavy social media use and increased rates of anxiety, depression, and low self-esteem, particularly among young people. The constant comparison with others' curated online personas can lead to feelings of inadequacy and dissatisfaction.\n\nPrivacy concerns represent another major challenge. Social media companies collect vast amounts of user data, raising questions about how this information is used and protected. The Cambridge Analytica scandal highlighted the potential for misuse of personal data for political purposes.\n\nDespite these challenges, social media continues to evolve and adapt. Platforms are implementing new features to combat misinformation, improve user privacy, and promote positive mental health. The future of social media will likely involve greater regulation and more responsible design practices."
            ],
            [
                'title' => 'The Evolution of Artificial Intelligence',
                'content' => "Artificial Intelligence (AI) has evolved from a concept in science fiction to a transformative technology that is reshaping industries and societies worldwide. The journey of AI development spans several decades, marked by periods of rapid advancement and temporary setbacks known as 'AI winters.'\n\nThe field of AI began in earnest in the 1950s, with the development of the first neural networks and the creation of programs capable of playing games like chess. Early AI systems were rule-based and limited in their capabilities, but they laid the foundation for more sophisticated approaches.\n\nThe 1980s and 1990s saw the development of expert systems and machine learning algorithms. These systems could perform specific tasks, such as medical diagnosis or financial analysis, by following predefined rules and learning from data. However, the limitations of these approaches became apparent, leading to another AI winter in the late 1990s.\n\nThe current AI renaissance, beginning in the early 2000s, has been driven by several key factors. The availability of large datasets, increased computational power, and advances in deep learning algorithms have enabled AI systems to achieve remarkable performance in areas such as image recognition, natural language processing, and autonomous vehicles.\n\nMachine learning, particularly deep learning, has become the dominant approach in modern AI. Neural networks with multiple layers can now process complex patterns in data, leading to breakthroughs in computer vision, speech recognition, and language translation. Companies like Google, Facebook, and Tesla have invested heavily in AI research and development.\n\nAI applications are now widespread across various sectors. In healthcare, AI systems assist in medical diagnosis and drug discovery. In finance, AI algorithms detect fraudulent transactions and optimize investment portfolios. In transportation, autonomous vehicles promise to revolutionize how people and goods move.\n\nHowever, the rapid advancement of AI also raises important ethical and societal questions. Concerns about job displacement, algorithmic bias, and the potential for AI systems to make decisions that affect human lives require careful consideration. The development of AI governance frameworks and ethical guidelines has become increasingly important.\n\nThe future of AI will likely involve greater integration with human capabilities, leading to augmented intelligence rather than artificial intelligence. Collaborative systems that combine human expertise with AI capabilities may prove more effective than purely autonomous AI systems."
            ]
        ];

        foreach ($readingPassages as $index => $passage) {
            Material::create([
                'test_id' => $test->id,
                'type' => 'text',
                'module' => 'reading',
                'part' => $index + 1,
                'title' => $passage['title'],
                'content' => $passage['content'],
                'order' => $index + 1
            ]);
        }

        // Reading Part 1 — Renewable Energy (14 questions)
        $readingPart1 = [
            ['question_text' => 'What is the main advantage of solar energy mentioned in the passage?', 'type' => 'multiple_choice', 'options' => ['It is free', 'Cost has decreased', 'It works at night', 'It requires no maintenance'], 'correct_answers' => ['Cost has decreased'], 'order' => 1],
            ['question_text' => 'Which countries are mentioned as leaders in solar energy investment?', 'type' => 'select_options', 'options' => ['Germany', 'China', 'United States', 'Japan', 'United Kingdom'], 'correct_answers' => ['Germany', 'China'], 'order' => 2],
            ['question_text' => 'Offshore wind farms capture stronger and more consistent winds.', 'type' => 'true_false_notgiven', 'correct_answers' => ['True'], 'order' => 3],
            ['question_text' => 'Denmark produces more wind energy than the United Kingdom.', 'type' => 'true_false_notgiven', 'correct_answers' => ['Not Given'], 'order' => 4],
            ['question_text' => 'Match each energy source with its key characteristic.', 'type' => 'matching', 'options' => ['Cost has decreased significantly', 'Uses turbines for electricity', 'One of the most reliable sources'], 'correct_answers' => ['Solar energy', 'Wind energy', 'Hydroelectric power'], 'order' => 5],
            ['question_text' => 'The Three Gorges Dam is located in which country?', 'type' => 'short_answer', 'correct_answers' => ['China'], 'order' => 6],
            ['question_text' => 'Energy _____ technology needs to advance to address intermittency.', 'type' => 'gap_filling', 'correct_answers' => ['storage'], 'order' => 7],
            ['question_text' => 'The renewable energy sector has created millions of _____ worldwide.', 'type' => 'sentence_completion', 'options' => ['jobs', 'problems', 'patents', 'machines', 'factories'], 'correct_answers' => ['jobs'], 'order' => 8],
            ['question_text' => 'The initial investment for renewable energy is always affordable.', 'type' => 'true_false_notgiven', 'correct_answers' => ['False'], 'order' => 9],
            ['question_text' => 'Long-term savings often _____ the initial costs of renewable energy.', 'type' => 'gap_filling', 'correct_answers' => ['offset'], 'order' => 10],
            ['question_text' => 'Solar panels have become more accessible to which groups?', 'type' => 'select_options', 'options' => ['Homeowners', 'Governments', 'Businesses', 'Schools', 'Hospitals'], 'correct_answers' => ['Homeowners', 'Businesses'], 'order' => 11],
            ['question_text' => 'Nuclear energy is discussed as an alternative in the passage.', 'type' => 'true_false_notgiven', 'correct_answers' => ['Not Given'], 'order' => 12],
            ['question_text' => 'The transition to renewable energy is described as both environmental and _____.', 'type' => 'gap_filling', 'correct_answers' => ['economic'], 'order' => 13],
        ];

        foreach ($readingPart1 as $q) {
            Question::create(['test_id' => $test->id, 'module' => 'reading', 'part' => 1, 'type' => $q['type'], 'question_text' => $q['question_text'], 'options' => $q['options'] ?? null, 'correct_answers' => $q['correct_answers'], 'points' => 1, 'order' => $q['order']]);
        }

        // Reading Part 2 — Social Media (14 questions)
        $readingPart2 = [
            ['question_text' => 'What is one positive impact of social media mentioned in the passage?', 'type' => 'multiple_choice', 'options' => ['Increased privacy', 'Better mental health', 'Connection across distances', 'Reduced misinformation'], 'correct_answers' => ['Connection across distances'], 'order' => 1],
            ['question_text' => 'Social media has been linked to increased rates of _____ and depression.', 'type' => 'gap_filling', 'correct_answers' => ['anxiety'], 'order' => 2],
            ['question_text' => 'The writer believes social media companies should be shut down.', 'type' => 'yes_no_notgiven', 'correct_answers' => ['Not Given'], 'order' => 3],
            ['question_text' => 'What scandal is mentioned as an example of data misuse?', 'type' => 'short_answer', 'correct_answers' => ['Cambridge Analytica', 'the Cambridge Analytica scandal'], 'order' => 4],
            ['question_text' => 'Social media algorithms are designed to maximize _____.', 'type' => 'gap_filling', 'correct_answers' => ['engagement'], 'order' => 5],
            ['question_text' => 'Echo chambers expose users only to information that confirms their existing beliefs.', 'type' => 'true_false_notgiven', 'correct_answers' => ['True'], 'order' => 6],
            ['question_text' => 'Social media has played a role in organizing protests.', 'type' => 'yes_no_notgiven', 'correct_answers' => ['Yes'], 'order' => 7],
            ['question_text' => 'Match each concern with its description from the passage.', 'type' => 'matching', 'options' => ['False information spreads faster than truth', 'Correlations with anxiety and depression', 'Vast amounts of user data collected'], 'correct_answers' => ['Misinformation', 'Mental health', 'Privacy'], 'order' => 8],
            ['question_text' => 'Constant comparison with others\' online personas leads to feelings of _____.', 'type' => 'sentence_completion', 'options' => ['happiness', 'inadequacy', 'motivation', 'anger', 'boredom'], 'correct_answers' => ['inadequacy'], 'order' => 9],
            ['question_text' => 'The future of social media will involve greater _____ and more responsible design.', 'type' => 'gap_filling', 'correct_answers' => ['regulation'], 'order' => 10],
            ['question_text' => 'Which social media platform was created most recently according to the passage?', 'type' => 'multiple_choice', 'options' => ['Facebook', 'Twitter', 'Instagram', 'TikTok'], 'correct_answers' => ['TikTok'], 'order' => 11],
            ['question_text' => 'Young people are particularly affected by social media\'s negative effects.', 'type' => 'true_false_notgiven', 'correct_answers' => ['True'], 'order' => 12],
            ['question_text' => 'More than half of the world population uses social media.', 'type' => 'true_false_notgiven', 'correct_answers' => ['Not Given'], 'order' => 13],
        ];

        foreach ($readingPart2 as $q) {
            Question::create(['test_id' => $test->id, 'module' => 'reading', 'part' => 2, 'type' => $q['type'], 'question_text' => $q['question_text'], 'options' => $q['options'] ?? null, 'correct_answers' => $q['correct_answers'], 'points' => 1, 'order' => $q['order']]);
        }

        // Reading Part 3 — AI (13 questions)
        $readingPart3 = [
            ['question_text' => 'When did the current AI renaissance begin?', 'type' => 'multiple_choice', 'options' => ['1950s', '1980s', '1990s', 'Early 2000s'], 'correct_answers' => ['Early 2000s'], 'order' => 1],
            ['question_text' => 'What has been the dominant approach in modern AI?', 'type' => 'multiple_choice', 'options' => ['Rule-based systems', 'Expert systems', 'Machine learning', 'Quantum computing'], 'correct_answers' => ['Machine learning'], 'order' => 2],
            ['question_text' => 'AI systems can achieve remarkable performance in _____, natural language processing, and _____.', 'type' => 'sentence_completion', 'options' => ['image recognition', 'autonomous vehicles', 'cooking', 'painting', 'singing'], 'correct_answers' => ['image recognition', 'autonomous vehicles'], 'order' => 3],
            ['question_text' => 'The development of AI governance frameworks has become less important over time.', 'type' => 'true_false_notgiven', 'correct_answers' => ['False'], 'order' => 4],
            ['question_text' => 'Early AI systems in the 1950s were primarily _____ based.', 'type' => 'gap_filling', 'correct_answers' => ['rule'], 'order' => 5],
            ['question_text' => 'Match each decade with the key AI development.', 'type' => 'matching', 'options' => ['First neural networks', 'Expert systems', 'Deep learning revolution'], 'correct_answers' => ['1950s', '1980s-1990s', '2000s-present'], 'order' => 6],
            ['question_text' => 'Which companies are mentioned as heavy investors in AI?', 'type' => 'select_options', 'options' => ['Google', 'Facebook', 'Tesla', 'Amazon', 'Apple'], 'correct_answers' => ['Google', 'Facebook', 'Tesla'], 'order' => 7],
            ['question_text' => 'AI winter refers to a period of rapid technological advancement.', 'type' => 'true_false_notgiven', 'correct_answers' => ['False'], 'order' => 8],
            ['question_text' => 'In healthcare, AI assists in medical diagnosis and _____.', 'type' => 'gap_filling', 'correct_answers' => ['drug discovery'], 'order' => 9],
            ['question_text' => 'The future of AI involves augmented intelligence rather than _____ intelligence.', 'type' => 'gap_filling', 'correct_answers' => ['artificial'], 'order' => 10],
            ['question_text' => 'What ethical concern about AI is NOT mentioned in the passage?', 'type' => 'multiple_choice', 'options' => ['Job displacement', 'Algorithmic bias', 'Environmental impact', 'Decision-making affecting lives'], 'correct_answers' => ['Environmental impact'], 'order' => 11],
            ['question_text' => 'AI in finance is used to detect fraudulent transactions.', 'type' => 'true_false_notgiven', 'correct_answers' => ['True'], 'order' => 12],
            ['question_text' => 'Collaborative human-AI systems may be more effective than purely autonomous AI.', 'type' => 'yes_no_notgiven', 'correct_answers' => ['Yes'], 'order' => 13],
        ];

        foreach ($readingPart3 as $q) {
            Question::create(['test_id' => $test->id, 'module' => 'reading', 'part' => 3, 'type' => $q['type'], 'question_text' => $q['question_text'], 'options' => $q['options'] ?? null, 'correct_answers' => $q['correct_answers'], 'points' => 1, 'order' => $q['order']]);
        }

        // ==========================================
        // WRITING MATERIALS & QUESTIONS
        // ==========================================
        Material::create(['test_id' => $test->id, 'type' => 'text', 'module' => 'writing', 'part' => 1, 'title' => 'Writing Task 1', 'content' => 'The chart below shows the percentage of households in different income brackets in three countries in 2020. Summarize the information by selecting and reporting the main features, and make comparisons where relevant.', 'order' => 1]);
        Material::create(['test_id' => $test->id, 'type' => 'text', 'module' => 'writing', 'part' => 2, 'title' => 'Writing Task 2', 'content' => 'Some people believe that technology has made life more complex, while others argue that it has simplified our lives. Discuss both views and give your own opinion.', 'order' => 2]);

        Question::create(['test_id' => $test->id, 'module' => 'writing', 'part' => 1, 'type' => 'gap_filling', 'question_text' => 'Write at least 150 words describing the information shown in the chart about household income distribution.', 'correct_answers' => [''], 'points' => 1, 'order' => 1]);
        Question::create(['test_id' => $test->id, 'module' => 'writing', 'part' => 2, 'type' => 'gap_filling', 'question_text' => 'Write at least 250 words discussing whether technology has made life more complex or simplified it.', 'correct_answers' => [''], 'points' => 1, 'order' => 1]);

        $this->command->info('Sample IELTS test created successfully with 4 listening parts (40 questions) and 3 reading passages (40 questions)!');
    }
}
