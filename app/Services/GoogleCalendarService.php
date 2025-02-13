<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;

class GoogleCalendarService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $this->client->setScopes([Google_Service_Calendar::CALENDAR_READONLY]);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function setAccessToken($token)
    {
        $this->client->setAccessToken($token);
    }

    public function getCalendarEvents()
    {
        if ($this->client->getAccessToken()) {
            $service = new Google_Service_Calendar($this->client);
            $calendarId = 'primary';
            $events = $service->events->listEvents($calendarId);
            return $events->getItems();
        }
        return [];
    }
}
