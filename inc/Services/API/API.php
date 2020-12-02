<?php

namespace MAM\Services\API;

use MAM\Services\ServiceInterface;
use ORM;

class API implements ServiceInterface
{
    /**
     * @var ORM[] leads need to be sent
     */
    private $investments;

    /**
     * @var ORM[] leads need to be sent
     */
    private $leasing;

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->investments = ORM::for_table('red_x_investment')->where('status', '')->find_many();
        $this->send_investments_data();
        $this->leasing = ORM::for_table('red_x_leasing')->where('status', '')->find_many();
        $this->send_leasing_data();
    }

    /**
     * Send investments leads to the CRM using API data
     */
    private function send_investments_data()
    {
        foreach ($this->investments as $investment) {
            $purchased = '0';
            if ($investment['purchased'] == 'Yes') {
                $purchased = '1';
            }
            $country = 'United States';
            if (strpos($investment['campaign'], 'UK') !== false) {
                $country = 'United Kingdom';
            }
            $fields = array(
                'country' => $country,
                'date_created' => $investment['received'],
                'email' => $investment['email'],
                'fullname' => $investment['name'],
                'investment_level' => $investment['investment_level'],
                'phone' => $investment['phone'],
                'source' => 'SMM',
                'campaign' => $investment['campaign'],
                'ad_set' => $investment['ad_set'],
                'type' => '1',
                'purchased_art' => $purchased
            );
            $response = $this->api_request($fields);

            $investment->set('status', $response);
            $investment->save();
        }
    }
    /**
     * Send leasing leads to the CRM using API data
     */
    private function send_leasing_data()
    {
        foreach ($this->leasing as $leasing) {
            $country = 'United States';
            if (strpos($leasing['campaign'], 'UK') !== false) {
                $country = 'United Kingdom';
            }
            $fields = array(
                'country' => $country,
                'date_created' => $leasing['received'],
                'email' => $leasing['email'],
                'fullname' => $leasing['name'],
                'investment_level' => 0,
                'phone' => $leasing['phone'],
                'source' => 'CLMM',
                'ad_set' => $leasing['ad_set'],
                'campaign' => $leasing['campaign'],
                'type' => '1',
                'purchased_art' => '0'
            );
            $response = $this->api_request($fields);
            $leasing->set('status', $response);
            $leasing->save();
        }
    }


    /**
     * send API request
     * @param $fields array the post fields as requested by the client API
     * @return bool|string false on fail the response message on success
     */
    private function api_request($fields){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $_ENV['API_URL'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}