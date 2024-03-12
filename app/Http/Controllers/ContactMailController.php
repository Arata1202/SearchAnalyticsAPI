<?php
namespace App\Http\Controllers;

use App\Services\GmailService;
use Illuminate\Http\Request;

class ContactMailController extends Controller {
    protected $gmailService;

    public function __construct(GmailService $gmailService) {
        $this->gmailService = $gmailService;
    }

    public function getRealUniversityStudentEmails() {
        try {
            $emails = $this->gmailService->getMessagesFromRealUniversityStudent();
            return response()->json($emails);
        } catch (\Exception $e) {
            // Gmail APIの呼び出しに失敗した場合など
            return response()->json(['error' => 'メールの取得に失敗しました。'], 500);
        }
    }
}
