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
        // Create a sample IELTS test
        $test = Test::create([
            'title' => 'IELTS Academic Mock Test 1',
            'description' => 'A comprehensive IELTS Academic mock test covering all four modules with authentic materials and questions.',
            'listening_time' => 30,
            'reading_time' => 60,
            'writing_time' => 60,
            'status' => 'active',
            'is_published' => true
        ]);

        // Create listening materials
        $listeningMaterial = Material::create([
            'test_id' => $test->id,
            'type' => 'audio',
            'module' => 'listening',
            'part' => 1,
            'title' => 'IELTS Listening Audio',
            'file_name' => 'listening_audio.mp3',
            'mime_type' => 'audio/mpeg',
            'file_size' => '2048000', // 2MB
            'order' => 1
        ]);

        // Create reading materials
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

        // Create writing materials
        $writingTasks = [
            [
                'title' => 'Writing Task 1',
                'content' => 'The chart below shows the percentage of households in different income brackets in three countries in 2020. Summarize the information by selecting and reporting the main features, and make comparisons where relevant.'
            ],
            [
                'title' => 'Writing Task 2',
                'content' => 'Some people believe that technology has made life more complex, while others argue that it has simplified our lives. Discuss both views and give your own opinion.'
            ]
        ];

        foreach ($writingTasks as $index => $task) {
            Material::create([
                'test_id' => $test->id,
                'type' => 'text',
                'module' => 'writing',
                'part' => $index + 1,
                'title' => $task['title'],
                'content' => $task['content'],
                'order' => $index + 1
            ]);
        }

        // Create listening questions (Part 1)
        $listeningQuestions = [
            [
                'question_text' => 'What is the main topic of the conversation?',
                'type' => 'multiple_choice',
                'options' => ['Weather', 'Travel plans', 'Work schedule', 'Shopping'],
                'correct_answers' => ['Travel plans'],
                'points' => 1,
                'order' => 1
            ],
            [
                'question_text' => 'When does the speaker plan to leave?',
                'type' => 'multiple_choice',
                'options' => ['Monday morning', 'Tuesday afternoon', 'Wednesday evening', 'Thursday morning'],
                'correct_answers' => ['Tuesday afternoon'],
                'points' => 1,
                'order' => 2
            ],
            [
                'question_text' => 'Fill in the blanks: The destination is located in the _____ part of the country.',
                'type' => 'gap_filling',
                'correct_answers' => ['northern'],
                'points' => 1,
                'order' => 3
            ]
        ];

        foreach ($listeningQuestions as $question) {
            Question::create([
                'test_id' => $test->id,
                'material_id' => $listeningMaterial->id,
                'module' => 'listening',
                'part' => 1,
                'type' => $question['type'],
                'question_text' => $question['question_text'],
                'options' => $question['options'] ?? null,
                'correct_answers' => $question['correct_answers'],
                'points' => $question['points'],
                'order' => $question['order']
            ]);
        }

        // Create reading questions
        $readingQuestions = [
            // Part 1 questions
            [
                'question_text' => 'What is the main advantage of solar energy mentioned in the passage?',
                'type' => 'multiple_choice',
                'options' => ['It is free', 'Cost has decreased', 'It works at night', 'It requires no maintenance'],
                'correct_answers' => ['Cost has decreased'],
                'points' => 1,
                'order' => 1,
                'part' => 1
            ],
            [
                'question_text' => 'Which countries are mentioned as leaders in solar energy investment?',
                'type' => 'select_options',
                'options' => ['Germany', 'China', 'United States', 'Japan', 'United Kingdom'],
                'correct_answers' => ['Germany', 'China'],
                'points' => 1,
                'order' => 2,
                'part' => 1
            ],
            // Part 2 questions
            [
                'question_text' => 'What is one positive impact of social media mentioned in the passage?',
                'type' => 'multiple_choice',
                'options' => ['Increased privacy', 'Better mental health', 'Connection across distances', 'Reduced misinformation'],
                'correct_answers' => ['Connection across distances'],
                'points' => 1,
                'order' => 1,
                'part' => 2
            ],
            [
                'question_text' => 'Fill in the blank: Social media has been linked to increased rates of _____ and depression.',
                'type' => 'gap_filling',
                'correct_answers' => ['anxiety'],
                'points' => 1,
                'order' => 2,
                'part' => 2
            ],
            // Part 3 questions
            [
                'question_text' => 'When did the current AI renaissance begin?',
                'type' => 'multiple_choice',
                'options' => ['1950s', '1980s', '1990s', 'Early 2000s'],
                'correct_answers' => ['Early 2000s'],
                'points' => 1,
                'order' => 1,
                'part' => 3
            ],
            [
                'question_text' => 'What has been the dominant approach in modern AI?',
                'type' => 'multiple_choice',
                'options' => ['Rule-based systems', 'Expert systems', 'Machine learning', 'Neural networks'],
                'correct_answers' => ['Machine learning'],
                'points' => 1,
                'order' => 2,
                'part' => 3
            ]
        ];

        foreach ($readingQuestions as $question) {
            Question::create([
                'test_id' => $test->id,
                'module' => 'reading',
                'part' => $question['part'],
                'type' => $question['type'],
                'question_text' => $question['question_text'],
                'options' => $question['options'] ?? null,
                'correct_answers' => $question['correct_answers'],
                'points' => $question['points'],
                'order' => $question['order']
            ]);
        }

        // Create writing questions
        $writingQuestions = [
            [
                'question_text' => 'Write at least 150 words describing the information shown in the chart about household income distribution.',
                'type' => 'multiple_choice', // Placeholder type for writing tasks
                'correct_answers' => [''],
                'points' => 1,
                'order' => 1,
                'part' => 1
            ],
            [
                'question_text' => 'Write at least 250 words discussing whether technology has made life more complex or simplified it.',
                'type' => 'multiple_choice', // Placeholder type for writing tasks
                'correct_answers' => [''],
                'points' => 1,
                'order' => 1,
                'part' => 2
            ]
        ];

        foreach ($writingQuestions as $question) {
            Question::create([
                'test_id' => $test->id,
                'module' => 'writing',
                'part' => $question['part'],
                'type' => $question['type'],
                'question_text' => $question['question_text'],
                'correct_answers' => $question['correct_answers'],
                'points' => $question['points'],
                'order' => $question['order']
            ]);
        }

        $this->command->info('Sample IELTS test created successfully!');
    }
}
