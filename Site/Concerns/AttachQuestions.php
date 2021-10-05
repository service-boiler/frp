<?php

namespace ServiceBoiler\Prf\Site\Concerns;

trait AttachQuestions
{
    /**
     * Attach multiple questions to a user
     *
     * @param mixed $questions
     */
    public function attachQuestions(array $questions)
    {
        foreach ($questions as $question) {
           
            if($this->questions()->where('academy_test_questions.question_id',$question)->count() == '0') {
                $this->attachQuestion($question);
            }
        }
    }

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param mixed $question
     */
    public function attachQuestion($question)
    {
        if (is_object($question)) {
            $question = $question->getKey();
        }
        if (is_array($question)) {
            $question = $question['id'];
        }
        $this->questions()->attach($question);
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

        $this->detachQuestions($this->questions()->pluck('id')->toArray());
        $this->attachQuestions($questions);
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