<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\Report;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ReportController extends BaseController
{
    //show all reports
    public function index(): JsonResponse
    {
        // $reports = Report::with(['user', 'book'])->get();
        $reports = Report::with(['user:id,user_name', 'book:id,title,file,cover'])->get();

        $response = [];
        foreach ($reports as $report) {
            $response[] = [
                'id' => $report->id,
                'body' => $report->body,
                'user_name' => $report->user->user_name,
                'book_title' => $report->book->title,
            ];
        }

        return $this->sendResponse($reports, 'Reports retrieved successfully.');
    }


    // Add a report
    public function store(ReportRequest $request, $bookId): JsonResponse
    {
        $request->validated();
        $user = Auth::user();
        $report = Report::create([
            'user_id' => (int) $request->user()->id,
            'book_id' => (int) $bookId,
            'body' => $request->body,
        ]);
        $adminUser = User::where('user_name', 'adminn')->first();

        if ($adminUser) {
            $notificationData = new \Illuminate\Http\Request();
            $notificationData->replace([
                'user_id' => $adminUser->id,
                'title' => 'New Report Submitted',
                'body' => "{$user->user_name} has submitted a new report for Book ID: $bookId.",
            ]);

            $notificationService = new NotificationService();
            $notificationService->sendFcmNotification($notificationData);
        }
        return $this->sendResponse($report, 'Report created successfully.');
    }

    //show report by its id for admin
    public function show($id): JsonResponse
    {

        //    $report = Report::with(['user', 'book'])->find($id);
        $report = Report::with(['user:id,user_name', 'book:id,title,cover,file'])->find($id);

        if (is_null($report)) {
            return $this->sendError('Report not found');
        }

        $response = [
            'id' => $report->id,
            'body' => $report->body,
            'user_name' => $report->user->user_name,
            'book_title' => $report->book->title,
        ];


        return $this->sendResponse($report, 'Report retrieved successfully');
    }

    //show my reports
    public function showMyReports(): JsonResponse
    {
        $user = Auth::user();
        $reports = Report::with(['user:id,user_name', 'book:id,title'])->get();

        $response = [];
        foreach ($reports as $report) {
            $response[] = [
                'id' => $report->id,
                'body' => $report->body,
                'user_name' => $report->user->user_name,
                'book_title' => $report->book->title,
            ];
        }

        return $this->sendResponse($reports, 'User\'s reports');
    }

    // Show book reports
    public function showBookReports($bookId): JsonResponse
    {
        $reports = DB::table('reports')
            ->join('users', 'reports.user_id', '=', 'users.id')
            ->join('books', 'reports.book_id', '=', 'books.id')
            ->where('reports.book_id', $bookId)
            ->select('reports.id', 'reports.body', 'users.user_name', 'books.title as book_title')
            ->get();

        $response = [];
        foreach ($reports as $report) {
            $response[] = [
                'id' => $report->id,
                'body' => $report->body,
                'user_name' => $report->user_name,
                'book_title' => $report->book_title,
            ];
        }

        return $this->sendResponse($reports, 'Reports fetched successfully');
    }

    // Remove a report
    public function removeReport($reportId): JsonResponse
    {
        $report = Report::findOrFail($reportId);
        $report->delete();

        return $this->sendResponse(null, 'Report removed successfully');
    }

    // Delete my reports
    public function deleteAllUserReports(): JsonResponse
    {
        $user = Auth::user();
        $user->reports()->detach();

        return $this->sendResponse(null, 'All reports removed successfully');
    }
}
