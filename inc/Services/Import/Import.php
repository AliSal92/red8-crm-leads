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
     * @inheritDoc
     */
    public function register()
    {
        $this->investment = array_map('str_getcsv', file($_ENV['SHEET_INVESTMENT']));
        $this->leasing = array_map('str_getcsv', file($_ENV['SHEET_LEASING']));

        $this->import_to_database();
    }


    /**
     * import the csv data to the database
     */
    private function import_to_database(){
        // INVESTMENT DATA
        array_shift($this->investment);
        foreach ($this->investment as $investment_row){
            $investment = ORM::for_table('red_x_investment')->where('received', $investment_row[0])->where('name', $investment_row[1])->find_one();
            if(!$investment){
                $investment = ORM::for_table('red_x_investment')->create();
                $investment->set('received', $investment_row[0]);
                $investment->set('name', $investment_row[1]);
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

        // LEASING DATA
        array_shift($this->leasing);
        foreach ($this->leasing as $leasing_row){
            $leasing = ORM::for_table('red_x_leasing')->where('received', $leasing_row[0])->where('name', $leasing_row[1])->find_one();
            if(!$leasing){
                $leasing = ORM::for_table('red_x_leasing')->create();
                $leasing->set('received', $investment_row[0]);
                $leasing->set('name', $investment_row[1]);
                $leasing->set('email', $investment_row[2]);
                $leasing->set('phone', $investment_row[3]);
                $leasing->set('company', $investment_row[4]);
                $leasing->set('city', $investment_row[5]);
                $leasing->set('ad_set', $investment_row[6]);
                $leasing->set('campaign', $investment_row[7]);
                $leasing->set('status', '');
                $leasing->save();
            }
        }

    }
}