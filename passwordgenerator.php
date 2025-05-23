<?php
class PasswordGenerator
{
    public function generate($length, $lower, $upper, $numbers, $special)
    {
        $chars = [
            'lower' => 'abcdefghijklmnopqrstuvwxyz',
            'upper' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'numbers' => '0123456789',
            'special' => '!@#$%^&*()_-+=<>?/{}[]~'
        ];

        $password = '';

        $password .= $this->getRandomChars($chars['lower'], $lower);
        $password .= $this->getRandomChars($chars['upper'], $upper);
        $password .= $this->getRandomChars($chars['numbers'], $numbers);
        $password .= $this->getRandomChars($chars['special'], $special);

        $remaining = $length - strlen($password);
        $allChars = $chars['lower'] . $chars['upper'] . $chars['numbers'] . $chars['special'];
        $password .= $this->getRandomChars($allChars, $remaining);

        return str_shuffle($password);
    }

    private function getRandomChars($charset, $count)
    {
        $result = '';
        $max = strlen($charset) - 1;
        for ($i = 0; $i < $count; $i++) {
            $result .= $charset[random_int(0, $max)];
        }
        return $result;
    }
}