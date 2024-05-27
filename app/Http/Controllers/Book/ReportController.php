<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;


class ReportController extends Controller
{
    //show all reports
    public function index(): JsonResponse
    {
        $reports = Report::all();
        return $this->sendResponse($reports, 'Reports retrieved successfully.');
    }


    // Add a report
    public function store(ReportRequest $request, $bookId): JsonResponse
    {
        $request->validated();
        $user = Auth::user();
        $report = Report::create ([
            'user_id'=>$request->user()->id,
            'book_id'=>$request->$bookId,
            'body'=>$request->body,
        ]);

        return $this->sendResponse($report, 'Report created successfully.');
    }

    //show book by its id for admin
    public function show($id): JsonResponse
    {
        $report = Report::find($id);

        if (is_null($report)) {
            return $this->sendError('Report not found');
        }

        return $this->sendResponse($report, 'Report retrieved successfully');
    }

    //show my reports
    public function showUserReports(): JsonResponse
    {
        $user = Auth::user();
        $reports = $user->reports;

        return $this->sendResponse($reports, 'User\'s reports');
    }

    // Show book reports
    public function showReportsByBookId($bookId): JsonResponse
    {
        $reports = Report::where('book_id', $bookId)->get();

        return $this->sendResponse($reports, 'Reports fetched successfully');
    }

    // Remove a report
    public function removeReport($reportId): JsonResponse
    {
        $report = Report::findOrFail($reportId);
        $report->delete(); // Delete the report

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
