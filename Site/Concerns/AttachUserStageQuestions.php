<?php

namespace ServiceBoiler\Prf\Site\Concerns;

trait AttachUserStageQuestions
{
    /**
     * Attach multiple questions to a user
     *
     * @param mixed $questions
     */
    public function AttachUserStageQuestions(array $questions, AcademyProgram $program)
    {
        foreach ($questions as $question) {
            $this->attachQuestion($question);
        }
    }

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param mixed $question
     */
    public function attachUserStageQuestion($question, $answer)
    {
       /* if (is_object($question)) {
            $question = $question->getKey();
        }
        if (is_array($question)) {
            $question = $question['id'];
        }
        */
        $this->userStageQuestions()->attach($question, $answer);
    }

    /**
     * Detach multiple questions from a user
     *
     * @param mixed $questions
     */
    public function detachQuestions(array $questions)
    {
        if (!$questions) {
            $questions = $this->questions()->get();
        }
        foreach ($questions as $question) {
            $this->detachQuestion($question);
        }
    }

    /**
     * @param $questions
     */
    public function syncQuestions($questions)
    {
        if (!is_array($questions)) {
            $questions = [$questions];
        }
        $this->questions()->sync($questions, true);
    }


    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param mixed $question
     */
    public function detachQuestion($question)
    {
        if (is_object($question)) {
            $question = $question->getKey();
        }
        if (is_array($question)) {
            $question = $question['id'];
        }
        $this->questions()->detach($question);
    }
}