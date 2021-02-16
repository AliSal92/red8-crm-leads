<?php

namespace MAM\Services\Import;

use ORM;
use MAM\Services\ServiceInterface;

class Import implements ServiceInterface
{

    /**
     * @var array the csv data as an array
     */
    private $investment;

    /**
     * @var array the csv data as an array
     */
    private $leasing;

    /**
     * @var array the csv data as an array
     */
    private $investment_b;

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->investment = array_map('str_getcsv', file($_ENV['SHEET_INVESTMENT']));
        $this->leasing = array_map('str_getcsv', file($_ENV['SHEET_LEASING']));
        $this->investment_b = array_map('str_getcsv', file($_ENV['SHEET_INVESTMENT_B']));

        $this->import_to_database();
    }

    /**
     * #to remove 4byte characters like emojis etc..
     * @param $string string the text that you want to remove the 4bye (emojis) from
     * @return string|string[]|null the text without emojis
     */
    private function replace_4byte($string)
    {
        return preg_replace('/[^A-Za-z0-9\- ]/', '', $string); // Removes special chars.

    }

    /**
     * import the csv data to the database
     */
    private function import_to_database()
    {
        // INVESTMENT DATA
        array_shift($this->investment);
        foreach ($this->investment as $investment_row) {
            $investment = ORM::for_table('red_x_investment')->where('received', $investment_row[0])->where('name', replace_4byte($investment_row[1]))->find_one();
            if (!$investment) {
                $investment = ORM::for_table('red_x_investment')->create();
                $investment->set('received', $investment_row[0]);
                $investment->set('name', $this->replace_4byte($investment_row[1]));
                $investment->set('email', $investment_row[2]);
                $investment->set('phone', $investment_row[3]);
                $investment->set('purchased', $investment_row[4]);
                $investment->set('investment_level', $investment_row[5]);
                $investment->set('ad_set', $investment_row[6]);
                $investment->set('campaign', $investment_row[7]);
                $investment->set('status', '');
                $investment->save();
            }
        }

        // INVESTMENT_B DATA
        array_shift($this->investment_b);
        foreach ($this->investment_b as $investment_row) {
            $investment_b = ORM::for_table('red_x_investment_b')->where('received', $investment_row[0])->where('name', replace_4byte($investment_row[1]))->find_one();
            if (!$investment_b) {
                $investment_b = ORM::for_table('red_x_investment_b')->create();
                $investment_b->set('received', $investment_row[0]);
                $investment_b->set('name', $this->replace_4byte($investment_row[1]));
                $investment_b->set('email', $investment_row[2]);
                $investment_b->set('phone', $investment_row[3]);
                $investment_b->set('purchased', $investment_row[4]);
                $investment_b->set('for_investment', $investment_row[5]);
                $investment_b->set('ad_set', $investment_row[6]);
                $investment_b->set('campaign', $investment_row[7]);
                $investment_b->set('status', '');
                $investment_b->save();
            }
        }

        // LEASING DATA
        array_shift($this->leasing);
        foreach ($this->leasing as $leasing_row) {
            $leasing = ORM::for_table('red_x_leasing')->where('received', $leasing_row[0])->where('name', replace_4byte($leasing_row[1]))->find_one();
            if (!$leasing) {
                $leasing = ORM::for_table('red_x_leasing')->create();
                $leasing->set('received', $leasing_row[0]);
                $leasing->set('name', $this->replace_4byte($leasing_row[1]));
                $leasing->set('email', $leasing_row[2]);
                $leasing->set('phone', $leasing_row[3]);
                $leasing->set('company', $leasing_row[4]);
                $leasing->set('city', $leasing_row[5]);
                $leasing->set('ad_set', $leasing_row[6]);
                $leasing->set('campaign', $leasing_row[7]);
                $leasing->set('status', '');
                $leasing->save();
            }
        }

    }
}