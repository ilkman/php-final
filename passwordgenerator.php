<?php
class PasswordGenerator
{
    public function generate($length, $lower, $upper, $numbers, $special)
    {
        //character bank
        $chars = [
            'lower' => 'abcdefghijklmnopqrstuvwxyz',
            'upper' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'numbers' => '0123456789',
            'special' => '!@#$%^&*()_-+=<>?/{}[]~'
        ];

        //clear the password input
        $password = '';

        //add the given number of characters
        $password .= $this->getRandomChars($chars['lower'], $lower);
        $password .= $this->getRandomChars($chars['upper'], $upper);
        $password .= $this->getRandomChars($chars['numbers'], $numbers);
        $password .= $this->getRandomChars($chars['special'], $special);

        $remaining = $length - strlen($password);
        $allChars = $chars['lower'] . $chars['upper'] . $chars['numbers'] . $chars['special'];
        $password .= $this->getRandomChars($allChars, $remaining);

        //mix the character
        return str_shuffle($password);
    }

    private function getRandomChars($charset, $count)
    {
        $result = '';
        $max = strlen($charset) - 1;
        //loop for characters
        for ($i = 0; $i < $count; $i++) {
            $result .= $charset[random_int(0, $max)];
        }
        return $result;
    }
}