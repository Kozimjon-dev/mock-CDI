<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\Material;
use App\Models\Question;

class OlympiadTestSeeder extends Seeder
{
    public function run(): void
    {
        $test = Test::create([
            'title' => 'THE LANGUAGE GALAXY — Ingliz Tili Fan Olimpiadasi',
            'description' => "IIV Namangan Akademik Litseyi \"THE LANGUAGE GALAXY\" o'rtoqlik fan olimpiadasi. Grammar test: 1 ball, Listening & Reading: 1.5 ball har bir to'g'ri javob uchun.",
            'listening_time' => 20,
            'reading_time' => 60,
            'writing_time' => 1,
            'status' => 'active',
            'is_published' => true,
        ]);

        // =============================================
        // LISTENING — Part 1: Study Tips (10 MCQ)
        // =============================================
        $listeningMaterial = Material::create([
            'test_id' => $test->id,
            'type' => 'audio',
            'module' => 'listening',
            'part' => 1,
            'title' => 'Study Tips — Teacher\'s Advice',
            'content' => 'A teacher gives advice to students about how to prepare for exams. Listen carefully and answer the questions.',
            'file_name' => 'olympiad_listening.mp3',
            'mime_type' => 'audio/mpeg',
            'file_size' => '2048000',
            'order' => 1,
        ]);

        $listeningQs = [
            ['q' => 'The teacher wants the students to …', 'opts' => ['take notes after she has finished speaking', 'take notes while she is speaking', 'forget about taking notes'], 'ans' => 'take notes after she has finished speaking'],
            ['q' => 'The teacher suggests eating …', 'opts' => ['sugary snacks', 'only apples', 'fruit and cereals'], 'ans' => 'fruit and cereals'],
            ['q' => 'The teacher suggests finding a study place with a lot of …', 'opts' => ['light', 'space', 'books'], 'ans' => 'light'],
            ['q' => 'If students feel stressed they should …', 'opts' => ['go to bed', 'go out for a walk', 'drink some water'], 'ans' => 'go out for a walk'],
            ['q' => 'Students are advised to …', 'opts' => ['select the important things to learn', 'read through everything once', 'make notes about every topic'], 'ans' => 'select the important things to learn'],
            ['q' => 'The teacher understands that repeating things can be …', 'opts' => ['difficult', 'uninteresting', 'tiring'], 'ans' => 'uninteresting'],
            ['q' => 'Students can do past exam papers …', 'opts' => ['in the library only', 'at home if they take photocopies', 'in the after-school study group'], 'ans' => 'in the after-school study group'],
            ['q' => 'The teacher recommends a break of five minutes every …', 'opts' => ['hour', 'two hours', 'thirty minutes'], 'ans' => 'thirty minutes'],
            ['q' => 'It\'s important to …', 'opts' => ['eat regularly', 'sleep when you feel tired', 'keep hydrated'], 'ans' => 'keep hydrated'],
            ['q' => 'The teacher is sure that the students will …', 'opts' => ['pass their exams', 'fail their exams', 'do their best'], 'ans' => 'do their best'],
        ];

        foreach ($listeningQs as $i => $q) {
            Question::create([
                'test_id' => $test->id, 'material_id' => $listeningMaterial->id,
                'module' => 'listening', 'part' => 1, 'type' => 'multiple_choice',
                'question_text' => $q['q'], 'options' => $q['opts'],
                'correct_answers' => [$q['ans']], 'points' => 2, 'order' => $i + 1,
            ]);
        }

        // =============================================
        // READING PART 1 — Grammar Test (25 questions)
        // =============================================
        Material::create([
            'test_id' => $test->id, 'type' => 'text', 'module' => 'reading', 'part' => 1,
            'title' => 'Grammar Test',
            'content' => "Choose the correct answer for each question. Then fill in the gaps with the appropriate word or phrase.\n\nScoring: Each correct answer = 1 point.",
            'order' => 1,
        ]);

        // Grammar MCQ (1-17)
        $grammarMCQ = [
            ['q' => 'I think it …. tonight.', 'opts' => ['will rain', 'rains', 'it is raining'], 'ans' => 'will rain'],
            ['q' => 'They have lived here …… 5 years.', 'opts' => ['since', 'for', 'from'], 'ans' => 'for'],
            ['q' => 'Choose the correct sentence.', 'opts' => ['Did you saw the movie yesterday?', 'Have you seen the movie yesterday?', 'Did you see the movie yesterday?'], 'ans' => 'Did you see the movie yesterday?'],
            ['q' => 'Choose the correct sentence about birthdate.', 'opts' => ['I was born on June in 1995.', 'I was born in June 1995.', 'I was born in 1995 on June.'], 'ans' => 'I was born in June 1995.'],
            ['q' => 'Choose the correct sentence about advice.', 'opts' => ['Can you give me some advices?', 'Can you give me an advice?', 'Can you give me some advice?'], 'ans' => 'Can you give me some advice?'],
            ['q' => 'Choose the correct sentence about weather.', 'opts' => ['It is very hot today.', 'It very hot today.', 'It is very hot in today.'], 'ans' => 'It is very hot today.'],
            ['q' => 'Choose the correct sentence about a person.', 'opts' => ['He very kind.', 'He is very kindly.', 'He is very kind.'], 'ans' => 'He is very kind.'],
            ['q' => 'Choose the correct sentence (past tenses).', 'opts' => ['I watched TV when she was calling me.', 'I was watching TV when she called me.', 'I watched TV when she called me.'], 'ans' => 'I was watching TV when she called me.'],
            ['q' => 'Choose the correct sentence about a past trip.', 'opts' => ['I visit Paris in 2019.', 'I visited Paris in 2019.', 'I have visited Paris in 2019.'], 'ans' => 'I visited Paris in 2019.'],
            ['q' => 'Choose the correct sentence.', 'opts' => ['I wants more patience.', 'I am want more patience.', 'I want more patience.'], 'ans' => 'I want more patience.'],
            ['q' => 'Choose the correct conditional sentence.', 'opts' => ["If we walk slowly, we'd have been late.", "If we walk slowly, we'll be late.", "If we walk slowly, we'd be late."], 'ans' => "If we walk slowly, we'll be late."],
            ['q' => 'Choose the correct comparative sentence.', 'opts' => ['Anna speaks English best than her brother.', 'Anna speaks English good than her brother.', 'Anna speaks English better than her brother.'], 'ans' => 'Anna speaks English better than her brother.'],
            ['q' => 'Choose the correct sentence about hobbies.', 'opts' => ['To playing video games is my favorite hobby.', 'Playing video games is my favorite hobby.', 'To plays video games is my favorite hobby.'], 'ans' => 'Playing video games is my favorite hobby.'],
            ['q' => 'Choose the correct collocation about attention.', 'opts' => ['Janet never throws attention in class.', 'Janet never gives attention in class.', 'Janet never pays attention in class.'], 'ans' => 'Janet never pays attention in class.'],
            ['q' => 'Choose the correct passive voice sentence.', 'opts' => ['The cake was made by my mother.', 'The cake was made from my mother.', 'The cake is made by my mother yesterday.'], 'ans' => 'The cake was made by my mother.'],
            ['q' => 'Choose the correct relative pronoun.', 'opts' => ['The man whose lives next door is a chef.', 'The man which lives next door is a chef.', 'The man who lives next door is a chef.'], 'ans' => 'The man who lives next door is a chef.'],
            ['q' => 'Choose the correct word order.', 'opts' => ['She wore beautiful a dress.', 'She wore a beautiful dress.', 'She wore a dress beautiful.'], 'ans' => 'She wore a beautiful dress.'],
        ];

        foreach ($grammarMCQ as $i => $q) {
            Question::create([
                'test_id' => $test->id, 'module' => 'reading', 'part' => 1,
                'type' => 'multiple_choice', 'question_text' => $q['q'],
                'options' => $q['opts'], 'correct_answers' => [$q['ans']],
                'points' => 1, 'order' => $i + 1,
            ]);
        }

        // Fill in gaps (18-24)
        $fillGaps = [
            ['q' => 'He said, "I am hungry." → He said that he ________ hungry. (Reported Speech)', 'ans' => ['was']],
            ['q' => 'Yesterday, I ________ (go) to the market. (Past Simple)', 'ans' => ['went']],
            ['q' => 'There isn\'t ________ sugar left. Can you buy some? (Quantifiers)', 'ans' => ['any']],
            ['q' => '________ Eiffel Tower is one of the most visited landmarks. (Articles)', 'ans' => ['The']],
            ['q' => 'She stayed home ________ she was feeling unwell. (Linking word – reason)', 'ans' => ['because']],
            ['q' => 'I ________ my keys, so I can\'t open the door. (Appropriate tense)', 'ans' => ['have lost', "haven't found", 'lost']],
            ['q' => 'I usually wake up ________ 7 o\'clock. (Prepositions)', 'ans' => ['at']],
        ];

        foreach ($fillGaps as $i => $q) {
            Question::create([
                'test_id' => $test->id, 'module' => 'reading', 'part' => 1,
                'type' => 'short_answer', 'question_text' => $q['q'],
                'correct_answers' => $q['ans'], 'points' => 1, 'order' => 18 + $i,
            ]);
        }

        // Translation (25)
        Question::create([
            'test_id' => $test->id, 'module' => 'reading', 'part' => 1,
            'type' => 'short_answer',
            'question_text' => 'Translate into English: "Men kasal edim, lekin uy vazifamni bajardim."',
            'correct_answers' => ['I was ill, but I did my homework', 'I was sick, but I did my homework', 'I was ill but I did my homework'],
            'points' => 1, 'order' => 25,
        ]);

        // =============================================
        // READING PART 2 — Sports & Cat Myths (8 questions)
        // =============================================
        Material::create([
            'test_id' => $test->id, 'type' => 'text', 'module' => 'reading', 'part' => 2,
            'title' => 'Reading: Sports & Myths About Cats',
            'content' => "PASSAGE A: SPORTS\n\nThere are many different kinds of sports in the world. Some people do sports for their career and other people do sports for pleasure. Some sports, like cricket, require using a lot of equipment, while others need very little in order to play a game. Some people use public playing fields to play sport for free, while other sports such as squash, tennis, badminton, and table tennis are usually paid on an hourly basis. The games are often controlled by individuals known as umpires in cricket and referees in sports like football and rugby.\n\nMost sports usually last not more than a few hours. However, a game of cricket can last five days before a winner is announced. Some people prefer to watch sport games on TV. Some sports, such as football and cricket, are really popular in the UK but not in the USA. People often play basketball and baseball in the USA. However, most countries compete in world sporting events such as the Football World Cup or the Olympics, which occur every four years.\n\nPASSAGE B: MYTHS ABOUT CATS\n\nPeople believe things about cats that might not be true. These \"myths\" can confuse cat owners. If you like cats, learn the facts and fiction about them. Some people think that cats need to drink milk. That is not true. If a cat eats a good diet, it does not need to drink milk. Cats may like milk, but it can make them sick. Cats should only have milk in small amounts.\n\nHave you heard the one about garlic? People put garlic on cat food. They believe it will get rid of worms in the cat's body. Does it work? Garlic makes food taste richer. Garlic does nothing to worms. It will give the cat bad breath! If your cat has worms, take it to a vet and your cat will be given medicine. The medicine will take care of the worms.\n\nSome people think that cats' whiskers help them to balance. Whiskers serve as \"feelers.\" They do nothing at all for balance. \"Feelers\" help the cat know about its surroundings.\n\nHave you heard these myths before? Do not believe them. Learn how to care for cats. Read books and talk to your veterinarian. Cats need good owners to care for them.",
            'order' => 2,
        ]);

        // Sports questions (1-4)
        $sportsQs = [
            ['q' => 'According to the passage, which sport needs the use of much equipment?', 'opts' => ['Football', 'Athletics', 'Swimming', 'Cricket'], 'ans' => 'Cricket'],
            ['q' => 'According to the passage, what sport is popular in the United Kingdom?', 'opts' => ['Athletics', 'Football', 'Baseball', 'Basketball'], 'ans' => 'Football'],
            ['q' => 'According to the passage, what sport is popular in the United States?', 'opts' => ['Squash', 'Baseball', 'Football', 'Cricket'], 'ans' => 'Baseball'],
            ['q' => 'According to the passage, how often are the Olympics held?', 'opts' => ['every few hours', 'every four years', 'every five days', 'every ten weeks'], 'ans' => 'every four years'],
        ];

        foreach ($sportsQs as $i => $q) {
            Question::create([
                'test_id' => $test->id, 'module' => 'reading', 'part' => 2,
                'type' => 'multiple_choice', 'question_text' => $q['q'],
                'options' => $q['opts'], 'correct_answers' => [$q['ans']],
                'points' => 2, 'order' => $i + 1,
            ]);
        }

        // Cat myths questions (5-8)
        $catQs = [
            ['q' => 'The passage is mostly about ______.', 'opts' => ['creating a positive atmosphere for your cat', 'stories that are true about cats', 'making your cat have balanced food', 'stories that are not truthful about cats'], 'ans' => 'stories that are not truthful about cats'],
            ['q' => 'Choose the best title for the passage.', 'opts' => ['Safety rules for cats', 'The cat\'s advice', 'Myths about cats', 'A cat\'s life in the forest'], 'ans' => 'Myths about cats'],
            ['q' => 'All of the following statements are TRUE according to the passage, EXCEPT:', 'opts' => ['An animal doctor will help your pet get rid of parasites.', 'Cats are not recommended to have great amounts of milk.', 'Garlic helps to draw cat\'s last breath.', 'Milk can make cats feel bad.'], 'ans' => 'Garlic helps to draw cat\'s last breath.'],
            ['q' => 'The word "know" in the passage is closest in meaning to ______.', 'opts' => ['forget about', 'be aware of', 'mislead to', 'be recognized by'], 'ans' => 'be aware of'],
        ];

        foreach ($catQs as $i => $q) {
            Question::create([
                'test_id' => $test->id, 'module' => 'reading', 'part' => 2,
                'type' => 'multiple_choice', 'question_text' => $q['q'],
                'options' => $q['opts'], 'correct_answers' => [$q['ans']],
                'points' => 2, 'order' => 5 + $i,
            ]);
        }

        // =============================================
        // READING PART 3 — Photography (10 questions: 5 MCQ + 5 T/F/NG)
        // =============================================
        Material::create([
            'test_id' => $test->id, 'type' => 'text', 'module' => 'reading', 'part' => 3,
            'title' => 'The Story of the First Photographs',
            'content' => "Last summer, I visited a museum in my city. One of the rooms was full of very old photographs. Some of them were more than 150 years old. As I looked at the pictures, I started thinking: who took the first photograph? How did photography begin?\n\nThe first photograph was taken in 1826 by a French man named Joseph Nicéphore Niépce. He used a special camera and a metal plate covered with chemicals. The picture shows the view from a window in his house. It looks simple, but it was a huge discovery. Before this, nobody knew how to keep a real image forever.\n\nLater, in 1839, another Frenchman named Louis Daguerre improved the process. He created the daguerreotype, which made it easier to take photographs. These early photos were black and white and needed people to stay still for a long time. Sometimes they had to sit for ten minutes without moving! This is why people in old photos often have serious faces—they could not smile for so long.\n\nPhotography became more popular during the 19th century. In the 1850s, cameras became smaller and cheaper. This allowed more people to take photographs of their families, cities, and important events. For example, during the American Civil War, photographers took many pictures of soldiers and battlefields. These photos showed the reality of war in a way paintings could not.\n\nIn the 20th century, photography changed again. Color photography became available, and cameras became easier to use. People no longer needed professional photographers to take their pictures. Cameras like the Kodak became very popular with ordinary families.\n\nToday, almost everyone carries a camera in their pocket. With smartphones, people can take photos anytime, anywhere. Some people say this has changed the way we remember our lives. In the past, families kept photo albums at home. Now, many people keep their photos online or share them on social media.\n\nLooking at those old photographs in the museum, we see how important photography is. It allows us to keep memories and understand history better.",
            'order' => 3,
        ]);

        // MCQ (9-13)
        $photoMCQ = [
            ['q' => 'What does the first photograph show?', 'opts' => ['A group of people standing outside', 'A view from inside a house', 'The sky and the clouds', "The view from a window in Niépce's house"], 'ans' => "The view from a window in Niépce's house"],
            ['q' => 'Why do people in early photos look serious?', 'opts' => ['They were usually unhappy', 'Photographers told them not to smile', 'They had to stay still for a long time', 'Smiling was not popular in the past'], 'ans' => 'They had to stay still for a long time'],
            ['q' => 'What happened during the 1850s?', 'opts' => ['Cameras became more expensive', 'People stopped using photographs', 'Cameras became easier for people to buy and use', 'Photographs were only taken by artists'], 'ans' => 'Cameras became easier for people to buy and use'],
            ['q' => 'What does the writer say about modern photography?', 'opts' => ['People print more photos now than before', 'Cameras today are usually very heavy', 'People now need professional photographers again', 'Many people store photos online or share them on social media'], 'ans' => 'Many people store photos online or share them on social media'],
            ['q' => 'What is one way modern photography has changed memory keeping?', 'opts' => ['People print all their photos in albums', 'Photos are mainly painted by artists', 'Many people store and share photos online', 'Cameras are no longer used at home'], 'ans' => 'Many people store and share photos online'],
        ];

        foreach ($photoMCQ as $i => $q) {
            Question::create([
                'test_id' => $test->id, 'module' => 'reading', 'part' => 3,
                'type' => 'multiple_choice', 'question_text' => $q['q'],
                'options' => $q['opts'], 'correct_answers' => [$q['ans']],
                'points' => 2, 'order' => $i + 1,
            ]);
        }

        // True/False/Not Given (14-18)
        $photoTFNG = [
            ['q' => 'The writer first became interested in photography when visiting a museum.', 'ans' => 'True'],
            ['q' => 'People had to sit still for ten minutes when taking early photographs.', 'ans' => 'True'],
            ['q' => 'The first photograph was taken in 1826.', 'ans' => 'True'],
            ['q' => 'Early photographs often required long exposure times.', 'ans' => 'True'],
            ['q' => 'Modern photography is mainly digital.', 'ans' => 'Not Given'],
        ];

        foreach ($photoTFNG as $i => $q) {
            Question::create([
                'test_id' => $test->id, 'module' => 'reading', 'part' => 3,
                'type' => 'true_false_notgiven', 'question_text' => $q['q'],
                'correct_answers' => [$q['ans']], 'points' => 2, 'order' => 6 + $i,
            ]);
        }

        // =============================================
        // READING PART 4 — Reading Habits (7 questions: 4 gap + 3 MCQ)
        // =============================================
        Material::create([
            'test_id' => $test->id, 'type' => 'text', 'module' => 'reading', 'part' => 4,
            'title' => 'Changes in Reading Habits',
            'content' => "For centuries, reading has been one of the main ways people gain knowledge, enjoy stories, and explore new ideas. However, in recent years, reading habits have changed greatly due to the rise of technology and the fast pace of modern life.\n\nIn the past, people spent hours reading books, newspapers, or magazines. Today, many people still read, but they often do it in different ways. E-books and audiobooks have become very popular because they are easy to carry and use. Some people enjoy listening to books while driving or exercising, which saves time. E-books are convenient because readers can store hundreds of titles on a single device. However, some readers still prefer traditional paper books because they like the feeling of holding a book and turning its pages.\n\nThe internet has also changed the way people read. Instead of reading long books or articles, many now prefer to read short pieces of information online, such as blog posts, social media updates, or news headlines. This type of reading is often done quickly and is sometimes called \"skimming,\" which means reading only the most important parts. As a result, people may find it harder to concentrate on longer texts because they are used to reading short, simple messages.\n\nAnother change is the kind of content people choose. In the past, reading was mostly connected to education or personal interest. Now, reading is often done for entertainment. People read online comments, game instructions, or funny stories shared by friends. While this can be fun, some experts worry that it may reduce the time people spend reading more serious materials that help develop deeper thinking skills.\n\nYoung people today are growing up in a world full of technology, so their reading habits are often very different from those of their parents. Many schools are trying to encourage students to read books regularly, as this is believed to improve vocabulary, imagination, and understanding of the world.\n\nDespite these changes, reading will likely continue to be an important part of human life. The ways we read may change, but the need for stories, information, and ideas remains the same. Understanding how reading habits evolve helps us prepare for the future and find ways to keep reading meaningful in a digital world.",
            'order' => 4,
        ]);

        // Gap filling (19-22)
        $habitGaps = [
            ['q' => 'Some people choose __________ because they can listen to books while doing other activities.', 'ans' => ['audiobooks']],
            ['q' => 'Many readers now prefer to read short pieces of information on the __________.', 'ans' => ['internet']],
            ['q' => 'The habit of reading only the main points of a text is known as __________.', 'ans' => ['skimming']],
            ['q' => 'Reading books is thought to help students improve their imagination and __________.', 'ans' => ['vocabulary']],
        ];

        foreach ($habitGaps as $i => $q) {
            Question::create([
                'test_id' => $test->id, 'module' => 'reading', 'part' => 4,
                'type' => 'short_answer', 'question_text' => $q['q'],
                'correct_answers' => $q['ans'], 'points' => 2, 'order' => $i + 1,
            ]);
        }

        // MCQ (23-25)
        $habitMCQ = [
            ['q' => 'What is one reason why people like e-books?', 'opts' => ['They are cheaper than paper books', 'They are easier to carry and store', 'They help people read faster', 'They are recommended by experts'], 'ans' => 'They are easier to carry and store'],
            ['q' => 'What is the main purpose of the text?', 'opts' => ['To explain why people read fewer books today', 'To suggest ways to teach reading to young children', 'To compare reading books with watching videos', 'To describe how technology has changed reading habits'], 'ans' => 'To describe how technology has changed reading habits'],
            ['q' => 'According to the text, why might people find it harder to concentrate on longer texts today?', 'opts' => ['They no longer enjoy reading books', 'They are used to reading short, simple messages', 'Schools do not encourage reading anymore', 'E-books are difficult to use'], 'ans' => 'They are used to reading short, simple messages'],
        ];

        foreach ($habitMCQ as $i => $q) {
            Question::create([
                'test_id' => $test->id, 'module' => 'reading', 'part' => 4,
                'type' => 'multiple_choice', 'question_text' => $q['q'],
                'options' => $q['opts'], 'correct_answers' => [$q['ans']],
                'points' => 2, 'order' => 5 + $i,
            ]);
        }

        // =============================================
        // WRITING — Translation task (minimal)
        // =============================================
        Material::create([
            'test_id' => $test->id, 'type' => 'text', 'module' => 'writing', 'part' => 1,
            'title' => 'Writing / Translation', 'content' => 'No writing task in this olympiad. Click "Complete Writing Module" to finish.', 'order' => 1,
        ]);

        $this->command->info('THE LANGUAGE GALAXY olympiad test created! Grammar: 25, Listening: 10, Reading: 25 = 60 questions total.');
    }
}
