<?php
use Illuminate\Support\Facades\Route;
session_start();
function data($quer,$mysqli,$nextquery){global $acco;
    if($nextquery==1){$_SESSION['acco'] = $quer;
    $quer="SELECT DISTINCT type FROM ".$quer;
    }else if($nextquery==2){$_SESSION['type']=$quer;
    $quer="SELECT name, price, quantity,id FROM ".$_SESSION['acco']." WHERE type ='".$quer."'";
    }
    $result = $mysqli->query($quer);
    
    $arr = array();
    $arr[]=array($nextquery+1);
    
    if ($result) {
        while ($row = $result->fetch_row()) {
            $it=array();
            
            $it[]=$row[0];
            if(count($row)== 4){
                $it[]=$row[2];$it[]=$row[1];$it[]=$row[3];
            }
            $arr[] = $it;
        }$arr[]=array('Add Category');
        $result->close();
    } else {
        echo "Error: " . $mysqli->error;
    }
    return $arr;
}
$host = 'localhost';
$dbname = 'grocery';
$username = 'root';
$password = 'Agn1d@@h';
$mysqli = new mysqli($host, $username, $password, $dbname);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
else{
Route::get('/', function ()use($mysqli) {
    return view('dashboard')->with('items',data("show tables",$mysqli,0));
});
Route::get("/category", function ()use($mysqli) {
    if(request('nextquery')==2){
        return view('dashboard', ['items' => data(request('tile'),$mysqli,request('nextquery')), 'type' => request('tile')]);}
    return view('dashboard')->with('items',data(request('tile'),$mysqli,request('nextquery')));
    
});
Route::get('/update', function ()use($mysqli) {$quer=request('qu');
        $quer="update ".$_SESSION['acco']." ".$quer;
        $mysqli->query($quer);
        return view('dashboard', ['items' => data($_SESSION['type'],$mysqli,2), 'type' => $_SESSION['type']]);
    });
Route::get('/crud', function ()use($mysqli) {$quer=request('queryy');
    if(request('nextquery') == 2 || request('nextquery')==3) {
        $quer="Insert into ".$_SESSION['acco']." ".$quer;
        $mysqli->query($quer);
    return view('dashboard')->with('items',data(request('nextquery')==2?$_SESSION['acco']:$_SESSION['type'],$mysqli,request('nextquery')-1));
    }
    else if(request('nextquery') == -1 && $quer[0]=='W'){
        $quer="delete from ".$_SESSION['acco']." ".$quer;
        $mysqli->query($quer);
        if($quer[6]=='t'){
    return view('dashboard')->with('items',data($_SESSION['acco'],$mysqli,1));}else{
        return view('dashboard')->with('items',data($_SESSION['type'],$mysqli,2));
    }
    }
    $mysqli->query($quer);
    return view('dashboard')->with('items',data("show tables",$mysqli,0));
});
Route::post('uploadimage', function (Illuminate\Http\Request $request) {
    // Validate the uploaded file
    $request->validate([
        'image' => 'required|image|max:2048', // Allow all image formats and limit the file size to 2MB
    ]);

    // Check if the request has a file
    if ($request->hasFile('image')) {
        // Get the file from the request
        $image = $request->file('image');

        // Define the file path
        $imagePath = 'images/' . $request->input('desired_filename');

        // Move the uploaded file to the specified path using the $imagePath
        $image->move(public_path($imagePath), 'a.jpeg');
        // Perform any additional actions you need (e.g., save the image path to the database)

        return back()->with('success', 'Image uploaded successfully');
    } else {
        return back()->with('error', 'No image file provided');
    }
})->name('uploadimage');



}
//$mysqli->close();
//two problems till now, one->it was asking for category when it was being deleted .two->it don't display any type when one type is deleted.