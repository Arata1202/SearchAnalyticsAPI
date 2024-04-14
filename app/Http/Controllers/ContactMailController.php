<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Gmail;

class ContactMailController extends Controller
{
    public function index()
    {
        $client = new Google_Client();
        $client->setApplicationName('Gmail API Laravel Application');
        $client->setScopes([Google_Service_Gmail::GMAIL_READONLY]);
        $client->setAuthConfig(storage_path(env('GOOGLE_APPLICATION_CREDENTIALS')));
        $client->setAccessType('offline');

        $gmail = new Google_Service_Gmail($client);
        $userId = 'me';

        // メッセージリストを取得（最初の10件）
        $messagesResponse = $gmail->users_messages->listUsersMessages($userId, ['maxResults' => 10]);
        $messages = $messagesResponse->getMessages();

        $emails = [];

        foreach ($messages as $message) {
            $emailDetail = $gmail->users_messages->get($userId, $message->getId());
            array_push($emails, $emailDetail);
        }

        return response()->json($emails);
    }
}
