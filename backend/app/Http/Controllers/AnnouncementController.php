<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
class AnnouncementController extends Controller
{
    public function announcements(){
        $announcements = Announcement::all();
    
        
        foreach ($announcements as $announcement) {
            if ($announcement->image) {
                $announcement->image = asset('storage/' . $announcement->image);
            }
        }
    
        return response()->json($announcements);
    }
    public function index()
    {
        $announcements = Announcement::all();
    
        
        foreach ($announcements as $announcement) {
            if ($announcement->image) {
                $announcement->image = asset('storage/' . $announcement->image);
            }
        }
    
        return response()->json($announcements);
    }
    public function store(Request $request)
    {
        $announcement = new Announcement();
        try {
            $announcement->title = $request->input('title');
            $announcement->context = $request->input('context');
            $announcement->status = $request->input('status');
    

            if ($request->hasFile('image')) {
                
                $request->validate([
                    'image' => 'image', 
                ]);
    
               
                $imagePath = $request->file('image')->store('announcements', 'public');
    
                
                $announcement->image = $imagePath;
            }
    
            $announcement->save();
    
            return response()->json(["message" => "Announcement Created Successfully", $announcement]);
        } catch (\Exception $e) {
            return response()->json(["message" => "Error Adding Data", "error" => $e->getMessage()]);
        }
    }
    
    public function show($id){
        try{
        $announcement = Announcement::find($id);
            if(!$announcement){
                return response()->json(["message"=>"No Announcement Found",404]);
            }
            else{
                return response()->json($announcement);
            }
        }
        catch(\Exception $e){
            return response()->json(["message"=>"Error Fetching Data","error"=>$e->getMessage()]);
        }
    }

    public function update(Request $request, $id){
        try{
            $announcement = Announcement::find($id);
            if(!$announcement) {
                return response()->json(["message"=>"No Id Found"]);
            }
    
            $announcement->title = $request->input('title');
            $announcement->context = $request->input('context');
            $announcement->status = $request->input('status');
    
            // Handle file upload if a new image is provided
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('announcements', 'public');
                $announcement->image = $imagePath;
            }
    
            $announcement->save(); // Save the updated announcement
    
            return response()->json(["message"=>"Updated Successfully", "announcement" => $announcement]);
        }
        catch(\Exception $e){
            return response()->json(["message"=>"Error Updating Data","error"=>$e->getMessage()]);
        }
    }
    public function destroy($id){
        try{
            $announcement = Announcement::find($id);
            if($announcement){
                $announcement->delete();
                return response()->json(["message"=>"Deleted Successfully"]);
            }
            else{
                return response()->json(["message"=>"No Id Found"]);
            }
        }
        catch(\Exception $e){
            return response()->json(["message"=>"Error Deleting Data","error"=>$e->getMessage()]);
        }
    }
    public function sortAnnouncement(Request $request) {
        try{
            $status = $request->input('status');
            if($status) {
                $announcement = Announcement::where('status',$status)->get();
                return response()->json($announcement);
            }
            else{
                return response()->json(["message"=>"NO Data Found!"]);
            }
        }
        catch(\Exception $e){
            return response()->json(["message"=>"Error Fething Data","error"=>$e->getMessage()]);
        }
    }
    public function searchAnnouncement(Request $request) {
        try{
            $text = $request->input('text');
            if($text) {
                $result = Announcement::where('title','like', "%$text%")
                                        ->orwhere('context','like',"%$text%")
                                        ->get();
            if($result->isEmpty()){
                return response()->json(["message"=>"No Results Found"]);
            }
            return response()->json($result);
            }
            else{
                return response()->json(["message"=>"No Data Found"]);
            }
        }
        catch(\Exception $e){
            return response()->json(["message"=>"Error Fething Data","error"=>$e->getMessage()]);
        }
    }
    public function getHistory(){
        $announcement = Announcement::where('status',"Done")
                                    ->get();

            if($announcement->isEmpty()){
                return response()->json(["message"=>"No Data"]);
            }
            else{
                return response()->json($announcement); 
            }
    }
    public function filterByStatus(Request $request){
        $request->validate([
            'status'
        ]);
        $status = $request->input('status');
        $announcements = Announcement::where('status',$status)->get();
        return response()->json($announcements);
    }
    
}