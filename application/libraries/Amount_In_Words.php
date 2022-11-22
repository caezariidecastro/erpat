<?php

    /**
     * English Number Converter - Collection of PHP Functions to convert a number
     *                            into English text.
     *
     * This exact code is licensed under CC-Wiki on Stackoverflow.
     * http://creativecommons.org/licenses/by-sa/3.0/
     *
     * @link     http://stackoverflow.com/q/277569/367456
     * @question Is there an easy way to convert a number to a word in PHP?
     *
     * This file incorporates work covered by the following copyright and
     * permission notice:
     *
     *   Copyright 2007-2008 Brenton Fletcher. http://bloople.net/num2text
     *   You can use this freely and modify it however you want.
     */

    class Amount_In_Words {

        public function convertNumber($number) {
            $number = number_format((float)$number, 2, '.', '');
            list($integer, $fraction) = explode(".", (string) $number);

            $output = "";

            if ($integer{0} == "-")
            {
                $output = "negative ";
                $integer    = ltrim($integer, "-");
            }
            else if ($integer{0} == "+")
            {
                $output = "positive ";
                $integer    = ltrim($integer, "+");
            }

            if ($integer{0} == "0")
            {
                $output .= "zero";
            }
            else
            {
                $integer = str_pad($integer, 36, "0", STR_PAD_LEFT);
                $group   = rtrim(chunk_split($integer, 3, " "), " ");
                $groups  = explode(" ", $group);

                $groups2 = array();
                foreach ($groups as $g)
                {
                    $groups2[] = $this->convertThreeDigit($g{0}, $g{1}, $g{2});
                }

                for ($z = 0; $z < count($groups2); $z++)
                {
                    if ($groups2[$z] != "")
                    {
                        $output .= $groups2[$z] . $this->convertGroup(11 - $z) . (
                                $z < 11
                                && !array_search('', array_slice($groups2, $z + 1, -1))
                                && $groups2[11] != ''
                                && $groups[11]{0} == '0'
                                    ? " and "
                                    : ", "
                            );
                    }
                }

                $output = rtrim($output, ", ");
            }

            if ($fraction > 0)
            {
                $output .= " & ";
                for ($i = 0; $i < strlen($fraction); $i++)
                {
                    $output .= $fraction{$i};
                    //$output .= $this->convertDigit($fraction{$i});
                }
                $output .= "/100 only";
            }

            return ucwords($output);
        }

        public function convertGroup($index) {
            switch ($index)
            {
                case 11:
                    return " decillion";
                case 10:
                    return " nonillion";
                case 9:
                    return " octillion";
                case 8:
                    return " septillion";
                case 7:
                    return " sextillion";
                case 6:
                    return " quintrillion";
                case 5:
                    return " quadrillion";
                case 4:
                    return " trillion";
                case 3:
                    return " billion";
                case 2:
                    return " million";
                case 1:
                    return " thousand";
                case 0:
                    return "";
            }
        }

        public function convertThreeDigit($digit1, $digit2, $digit3) {
            $buffer = "";

            if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0")
            {
                return "";
            }

            if ($digit1 != "0")
            {
                $buffer .= $this->convertDigit($digit1) . " hundred";
                if ($digit2 != "0" || $digit3 != "0")
                {
                    $buffer .= " and ";
                }
            }

            if ($digit2 != "0")
            {
                $buffer .= $this->convertTwoDigit($digit2, $digit3);
            }
            else if ($digit3 != "0")
            {
                $buffer .= $this->convertDigit($digit3);
            }

            return $buffer;
        }

        public function convertTwoDigit($digit1, $digit2) {
            if ($digit2 == "0")
            {
                switch ($digit1)
                {
                    case "1":
                        return "ten";
                    case "2":
                        return "twenty";
                    case "3":
                        return "thirty";
                    case "4":
                        return "forty";
                    case "5":
                        return "fifty";
                    case "6":
                        return "sixty";
                    case "7":
                        return "seventy";
                    case "8":
                        return "eighty";
                    case "9":
                        return "ninety";
                }
            } else if ($digit1 == "1")
            {
                switch ($digit2)
                {
                    case "1":
                        return "eleven";
                    case "2":
                        return "twelve";
                    case "3":
                        return "thirteen";
                    case "4":
                        return "fourteen";
                    case "5":
                        return "fifteen";
                    case "6":
                        return "sixteen";
                    case "7":
                        return "seventeen";
                    case "8":
                        return "eighteen";
                    case "9":
                        return "nineteen";
                }
            } else
            {
                $temp = $this->convertDigit($digit2);
                switch ($digit1)
                {
                    case "2":
                        return "twenty-$temp";
                    case "3":
                        return "thirty-$temp";
                    case "4":
                        return "forty-$temp";
                    case "5":
                        return "fifty-$temp";
                    case "6":
                        return "sixty-$temp";
                    case "7":
                        return "seventy-$temp";
                    case "8":
                        return "eighty-$temp";
                    case "9":
                        return "ninety-$temp";
                }
            }
        }

        public function convertDigit($digit) {
            switch ($digit)
            {
                case "0":
                    return "zero";
                case "1":
                    return "one";
                case "2":
                    return "two";
                case "3":
                    return "three";
                case "4":
                    return "four";
                case "5":
                    return "five";
                case "6":
                    return "six";
                case "7":
                    return "seven";
                case "8":
                    return "eight";
                case "9":
                    return "nine";
            }
        }
    }
