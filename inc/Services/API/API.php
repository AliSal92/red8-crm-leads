<?php

namespace MAM\Services\API;

use ORM;
use MAM\Services\ServiceInterface;

class API implements ServiceInterface
{
    /**
     * @var ORM[] leads need to be sent
     */
    private $investments;

    /**
     * @var ORM[] leads need to be sent
     */
    private $investments_b;

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
        $this->investments_b = ORM::for_table('red_x_investment_b')->where('status', '')->find_many();
        $this->send_investments_b_data();
    }

    /**
     * Send investments leads to the CRM using API data
     */
    private function send_investments_data()
    {
        foreach ($this->investments as $investment) {
            /** @noinspection DuplicatedCode */
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
     * Send investments B leads to the CRM using API data
     */
    private function send_investments_b_data()
    {
        foreach ($this->investments_b as $investments_b) {
            /** @noinspection DuplicatedCode */
            $purchased = '0';
            if ($investments_b['purchased'] == 'Yes') {
                $purchased = '1';
            }
            $country = 'United States';
            if (strpos($investments_b['campaign'], 'UK') !== false) {
                $country = 'United Kingdom';
            }
            $fields = array(
                'country' => $country,
                'date_created' => $investments_b['received'],
                'email' => $investments_b['email'],
                'fullname' => $investments_b['name'],
                'investment_level' => 'N\/A',
                'phone' => $investments_b['phone'],
                'source' => 'SM_FB',
                'for_investment' => $investments_b['for_investment'],
                'campaign' => $investments_b['campaign'],
                'ad_set' => $investments_b['ad_set'],
                'type' => '1',
                'purchased_art' => $purchased
            );
            $response = $this->api_request($fields);

            $investments_b->set('status', $response);
            $investments_b->save();
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