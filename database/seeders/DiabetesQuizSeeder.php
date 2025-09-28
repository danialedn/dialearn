<?php

namespace Database\Seeders;

use App\Models\Stage;
use App\Models\Question;
use Illuminate\Database\Seeder;

class DiabetesQuizSeeder extends Seeder
{
    public function run(): void
    {
        // Read questions from CSV file
        $csvFile = database_path('seeders/quiz_questions.csv');
        
        if (!file_exists($csvFile)) {
            $this->command->error('CSV file not found: ' . $csvFile);
            return;
        }

        $questionsData = $this->readQuestionsFromCSV($csvFile);
        
        if (empty($questionsData)) {
            $this->command->error('No questions found in CSV file');
            return;
        }

        // Group questions by stage
        $stageQuestions = [];
        foreach ($questionsData as $question) {
            $stageName = $question['stage_name'];
            if (!isset($stageQuestions[$stageName])) {
                $stageQuestions[$stageName] = [
                    'level' => $question['level'],
                    'difficulty' => $question['difficulty'],
                    'questions' => []
                ];
            }
            $stageQuestions[$stageName]['questions'][] = $question;
        }

        // Create stages and questions
        foreach ($stageQuestions as $stageName => $stageData) {
            $stage = Stage::updateOrCreate(
                ['level' => $stageData['level']], // Find by level
                [
                    'name' => $stageName,
                    'description' => 'مرحله ' . $stageData['level'] . ' از آموزش دیابت',
                    'difficulty' => $stageData['difficulty'],
                    'questions_count' => count($stageData['questions']),
                ]
            );

            // Delete existing questions for this stage to avoid duplicates
            $stage->questions()->delete();
            
            $this->createQuestionsForStage($stage, $stageData['questions']);
        }
    }

    private function readQuestionsFromCSV($filePath): array
    {
        $questions = [];
        
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Skip header row
            $header = fgetcsv($handle);
            
            while (($data = fgetcsv($handle)) !== false) {
                if (count($data) >= 10) { // Ensure we have all required columns
                    $questions[] = [
                        'stage_name' => $data[0],
                        'level' => (int)$data[1],
                        'difficulty' => (int)$data[2],
                        'question_text' => $data[3],
                        'option_a' => $data[4],
                        'option_b' => $data[5],
                        'option_c' => $data[6],
                        'option_d' => $data[7],
                        'correct_answer' => $data[8],
                        'explanation' => $data[9],
                    ];
                }
            }
            fclose($handle);
        }
        
        return $questions;
    }

    private function createQuestionsForStage(Stage $stage, array $questionsData): void
    {
        foreach ($questionsData as $questionData) {
            Question::create([
                'stage_id' => $stage->id,
                'question_text' => $questionData['question_text'],
                'option_a' => $questionData['option_a'],
                'option_b' => $questionData['option_b'],
                'option_c' => $questionData['option_c'],
                'option_d' => $questionData['option_d'],
                'correct_answer' => $this->convertAnswerToNumber($questionData['correct_answer']),
                'difficulty' => $questionData['difficulty'],
                'explanation' => $questionData['explanation'],
            ]);
        }
    }

    private function convertAnswerToNumber(string $answer): int
    {
        switch (strtoupper($answer)) {
            case 'A':
                return 0;
            case 'B':
                return 1;
            case 'C':
                return 2;
            case 'D':
                return 3;
            default:
                return 0;
        }
    }
}